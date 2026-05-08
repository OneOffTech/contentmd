use crate::http::HttpClient;
use crate::parser;
use std::fs;

pub async fn run(url: &str, output: Option<&str>) -> Result<(), String> {
    let client = HttpClient::new();
    let result = client.fetch_markdown(url).await?;

    if !result.is_markdown {
        return Err(format!(
            "URL did not return content-md (got {})",
            result.content_type
        ));
    }

    let content_md = parser::parse(&result.body)?;
    let fm = &content_md.frontmatter;

    let title = fm
        .title
        .as_deref()
        .unwrap_or("")
        .to_string();
    let name = slugify(&title);

    let description = fm.description.as_deref().unwrap_or("").to_string();

    let mut metadata_lines = Vec::new();
    if let Some(ref author) = fm.author {
        metadata_lines.push(format!("  author: {}", author));
    }
    if let Some(ref date) = fm.date {
        metadata_lines.push(format!("  date: {}", date));
    }
    if let Some(ref license) = fm.license {
        metadata_lines.push(format!("  license: {}", license));
    }
    metadata_lines.push(format!("  source: {}", url));

    let mut out = String::new();
    out.push_str("---\n");
    out.push_str(&format!("name: {}\n", name));
    out.push_str(&format!("description: {}\n", description));
    out.push_str("metadata:\n");
    for line in &metadata_lines {
        out.push_str(line);
        out.push('\n');
    }
    out.push_str("---\n");
    if !content_md.body.is_empty() {
        out.push('\n');
        out.push_str(&content_md.body);
        out.push('\n');
    }

    match output {
        Some(path) => {
            fs::write(path, &out).map_err(|e| format!("failed to write {}: {}", path, e))?;
            println!("Skill written to {}", path);
        }
        None => {
            print!("{}", out);
        }
    }

    Ok(())
}

pub(crate) fn slugify(text: &str) -> String {
    let lower = text.to_lowercase();
    let mut slug = String::new();
    for ch in lower.chars() {
        if ch.is_ascii_alphanumeric() {
            slug.push(ch);
        } else if ch == '-' || ch.is_whitespace() || ch == '_' {
            if !slug.ends_with('-') {
                slug.push('-');
            }
        }
    }
    let slug = slug.trim_matches('-').to_string();
    if slug.len() > 64 {
        let truncated = &slug[..64];
        truncated.trim_end_matches('-').to_string()
    } else {
        slug
    }
}

#[cfg(test)]
mod tests {
    use super::*;

    #[test]
    fn slugify_lowercases_and_hyphenates() {
        assert_eq!(slugify("Hello World"), "hello-world");
    }

    #[test]
    fn slugify_strips_special_chars() {
        assert_eq!(slugify("Hello, World!"), "hello-world");
    }

    #[test]
    fn slugify_collapses_multiple_separators() {
        assert_eq!(slugify("hello   world"), "hello-world");
    }

    #[test]
    fn slugify_trims_leading_trailing_hyphens() {
        assert_eq!(slugify("  hello world  "), "hello-world");
    }

    #[test]
    fn slugify_truncates_to_64_chars() {
        let slug = slugify(&"a".repeat(70));
        assert!(slug.len() <= 64);
    }

    #[test]
    fn slugify_no_trailing_hyphen_after_truncation() {
        // position 64 falls on a hyphen — must be trimmed
        let title = format!("{} {}", "a".repeat(63), "b".repeat(10));
        let slug = slugify(&title);
        assert!(!slug.ends_with('-'));
    }

    #[test]
    fn slugify_empty_input() {
        assert_eq!(slugify(""), "");
    }
}
