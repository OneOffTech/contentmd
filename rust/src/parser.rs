use pulldown_cmark::{Event, HeadingLevel, Parser as MdParser, Tag, TagEnd};
use serde::{Deserialize, Serialize};

#[derive(Debug, Deserialize, Serialize, Clone, Default)]
pub struct Frontmatter {
    pub title: Option<String>,
    pub description: Option<String>,
    pub date: Option<String>,
    pub license: Option<String>,
    pub author: Option<String>,
}

#[derive(Debug, Clone)]
pub struct ContentMd {
    pub frontmatter: Frontmatter,
    pub body: String,
}

pub fn parse(content: &str) -> Result<ContentMd, String> {
    let content = content.trim_start();

    if !content.starts_with("---") {
        return Err("no YAML frontmatter found (content must start with ---)".into());
    }

    let rest = &content[3..];
    let end = rest
        .find("\n---")
        .ok_or("frontmatter closing --- not found")?;
    let yaml = &rest[..end];
    let body = rest[end + 4..].trim_start().to_string();

    let frontmatter: Frontmatter =
        serde_yaml::from_str(yaml).map_err(|e| format!("YAML parse error: {}", e))?;

    Ok(ContentMd { frontmatter, body })
}

/// Returns the raw frontmatter block including delimiters.
pub fn extract_frontmatter_raw(content: &str) -> Option<String> {
    let content = content.trim_start();
    if !content.starts_with("---") {
        return None;
    }
    let rest = &content[3..];
    let end = rest.find("\n---")?;
    Some(format!("---{}---", &rest[..end + 1]))
}

pub struct HeadingCheck {
    pub starts_with_h1: bool,
    pub first_h1_text: Option<String>,
    pub hierarchy_errors: Vec<String>,
}

pub fn check_headings(markdown: &str) -> HeadingCheck {
    let parser = MdParser::new(markdown);
    let mut headings: Vec<(u32, String)> = Vec::new();
    let mut current_text = String::new();
    let mut in_heading = false;
    let mut current_level = 0u32;

    for event in parser {
        match event {
            Event::Start(Tag::Heading { level, .. }) => {
                in_heading = true;
                current_level = heading_level_to_u32(level);
                current_text.clear();
            }
            Event::End(TagEnd::Heading(_)) => {
                if in_heading {
                    headings.push((current_level, current_text.clone()));
                    in_heading = false;
                }
            }
            Event::Text(text) if in_heading => {
                current_text.push_str(&text);
            }
            _ => {}
        }
    }

    let starts_with_h1 = headings.first().map(|(l, _)| *l == 1).unwrap_or(false);
    let first_h1_text = headings
        .first()
        .and_then(|(l, t)| if *l == 1 { Some(t.clone()) } else { None });

    let mut hierarchy_errors = Vec::new();
    let mut prev_level = 0u32;
    for (i, (level, text)) in headings.iter().enumerate() {
        if i == 0 {
            prev_level = *level;
            continue;
        }
        if *level > prev_level + 1 {
            hierarchy_errors.push(format!(
                "heading \"{}\" skips from H{} to H{}",
                text, prev_level, level
            ));
        }
        prev_level = *level;
    }

    HeadingCheck {
        starts_with_h1,
        first_h1_text,
        hierarchy_errors,
    }
}

fn heading_level_to_u32(level: HeadingLevel) -> u32 {
    match level {
        HeadingLevel::H1 => 1,
        HeadingLevel::H2 => 2,
        HeadingLevel::H3 => 3,
        HeadingLevel::H4 => 4,
        HeadingLevel::H5 => 5,
        HeadingLevel::H6 => 6,
    }
}

#[cfg(test)]
mod tests {
    use super::*;

    const FULL_DOC: &str = "\
---
title: My Article Title Here
description: A short description of the article content
date: 2024-01-15
author: Alice
license: MIT
---

# My Article Title Here

## Introduction

Some content here.
";

    #[test]
    fn parse_returns_all_fields() {
        let cm = parse(FULL_DOC).unwrap();
        assert_eq!(cm.frontmatter.title.as_deref(), Some("My Article Title Here"));
        assert_eq!(cm.frontmatter.description.as_deref(), Some("A short description of the article content"));
        assert_eq!(cm.frontmatter.date.as_deref(), Some("2024-01-15"));
        assert_eq!(cm.frontmatter.author.as_deref(), Some("Alice"));
        assert_eq!(cm.frontmatter.license.as_deref(), Some("MIT"));
    }

    #[test]
    fn parse_extracts_body() {
        let cm = parse(FULL_DOC).unwrap();
        assert!(cm.body.starts_with("# My Article Title Here"));
    }

    #[test]
    fn parse_fails_without_opening_delim() {
        assert!(parse("# Just markdown\n\nNo frontmatter.").is_err());
    }

    #[test]
    fn parse_fails_without_closing_delim() {
        assert!(parse("---\ntitle: Unclosed\n").is_err());
    }

    #[test]
    fn parse_with_required_fields_only() {
        let content = "---\ntitle: Title\ndescription: Description\n---\n\n# Title\n";
        let cm = parse(content).unwrap();
        assert!(cm.frontmatter.date.is_none());
        assert!(cm.frontmatter.author.is_none());
        assert!(cm.frontmatter.license.is_none());
    }

    #[test]
    fn parse_strips_leading_whitespace() {
        let content = "\n\n---\ntitle: Title\ndescription: Desc\n---\n\n# Title\n";
        assert!(parse(content).is_ok());
    }

    #[test]
    fn extract_frontmatter_raw_returns_delimited_block() {
        let raw = extract_frontmatter_raw(FULL_DOC).unwrap();
        assert!(raw.starts_with("---"));
        assert!(raw.ends_with("---"));
        assert!(raw.contains("title:"));
        assert!(!raw.contains("# My Article"));
    }

    #[test]
    fn extract_frontmatter_raw_returns_none_without_delimiters() {
        assert!(extract_frontmatter_raw("# No frontmatter here").is_none());
    }

    #[test]
    fn check_headings_detects_h1_and_text() {
        let hc = check_headings("# My Title\n\n## Section\n");
        assert!(hc.starts_with_h1);
        assert_eq!(hc.first_h1_text.as_deref(), Some("My Title"));
    }

    #[test]
    fn check_headings_missing_h1() {
        let hc = check_headings("## Section\n\nContent.\n");
        assert!(!hc.starts_with_h1);
        assert!(hc.first_h1_text.is_none());
    }

    #[test]
    fn check_headings_valid_hierarchy_no_errors() {
        let hc = check_headings("# Title\n\n## Section\n\n### Sub\n\n#### Deep\n");
        assert!(hc.hierarchy_errors.is_empty());
    }

    #[test]
    fn check_headings_detects_skip_h1_to_h3() {
        let hc = check_headings("# Title\n\n### Skipped H2\n");
        assert!(!hc.hierarchy_errors.is_empty());
        assert!(hc.hierarchy_errors[0].contains("H3"));
    }

    #[test]
    fn check_headings_empty_body() {
        let hc = check_headings("");
        assert!(!hc.starts_with_h1);
        assert!(hc.hierarchy_errors.is_empty());
    }
}
