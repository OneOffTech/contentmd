use scraper::{Html, Selector};
use std::collections::HashMap;
use std::fs;
use crate::agent;
use crate::http::HttpClient;
use crate::output::{Check, OutputFormat, ValidationReport, print_validation_report, report_to_json_value};
use crate::parser::{self, Frontmatter};
use crate::tokens;

pub async fn run(url: &str, format: &str, save: Option<&str>, follow_redirect: bool, explicit_agent: bool) -> Result<(), String> {
    let agent = agent::effective(explicit_agent);
    let client = HttpClient::new(follow_redirect);

    let md_result = client.fetch_markdown(url).await?;
    let html_result = client.fetch_html(url).await.ok();
    let robots_result = client.fetch_robots_txt(url).await;

    let parse_result = parser::parse(&md_result.body);
    let raw_fm = parser::extract_frontmatter_raw(&md_result.body).unwrap_or_default();

    let mut checks: Vec<Check> = Vec::new();

    checks.extend(content_negotiation_checks(
        md_result.is_markdown,
        md_result.status,
        &md_result.content_type,
        &md_result.headers,
    ));

    match client.fetch_frontmatter_only(url).await {
        Err(_) => checks.push(Check::warn(
            "range-frontmatter",
            "Range: x-frontmatter request failed",
        )),
        Ok(ref r) if !r.is_markdown => checks.push(Check::warn(
            "range-frontmatter",
            format!(
                "server does not support Range: x-frontmatter (got content-type: {})",
                r.content_type
            ),
        )),
        Ok(ref r) => match parser::parse(&r.body) {
            Err(_) => checks.push(Check::warn(
                "range-frontmatter",
                "Range: x-frontmatter response is not valid content-md",
            )),
            Ok(ref cm) if cm.body.trim().is_empty() => checks.push(Check::pass(
                "range-frontmatter",
                "Range: x-frontmatter returns frontmatter only",
            )),
            Ok(_) => checks.push(Check::fail(
                "range-frontmatter",
                "Range: x-frontmatter returned the full document instead of frontmatter only",
            )),
        },
    }

    if let Some(ref html) = html_result {
        checks.extend(html_alternate_checks(&html.body));
    }

    match parse_result {
        Err(ref e) => {
            checks.push(Check::fail("frontmatter-parse", e.clone()));
        }
        Ok(ref cm) => {
            checks.extend(frontmatter_required_checks(&cm.frontmatter));
            checks.extend(frontmatter_optional_checks(&cm.frontmatter));
            checks.extend(length_and_token_checks(&cm.frontmatter, &raw_fm));
            checks.extend(heading_checks(&cm.body));
            if let Some(ref html) = html_result {
                checks.extend(html_match_checks(&cm.frontmatter, &html.body));
            }
        }
    }

    checks.extend(robots_checks(&robots_result));

    let token_count = parse_result.as_ref().ok().map(|cm| {
        tokens::estimate(&cm.body) + tokens::estimate(&raw_fm)
    });

    let report = ValidationReport {
        url: url.to_string(),
        checks,
        html_size: html_result.as_ref().map(|r| r.size_bytes),
        markdown_size: Some(md_result.size_bytes),
        token_count,
    };

    if let Some(path) = save {
        let json_val = report_to_json_value(&report);
        let json_str = serde_json::to_string_pretty(&json_val)
            .map_err(|e| format!("failed to serialize report: {}", e))?;
        fs::write(path, json_str).map_err(|e| format!("failed to write {}: {}", path, e))?;
    }

    let fmt = if agent {
        OutputFormat::Json
    } else {
        OutputFormat::from_str(format)
    };
    print_validation_report(&report, &fmt);
    Ok(())
}

fn content_negotiation_checks(
    is_markdown: bool,
    status: u16,
    content_type: &str,
    headers: &HashMap<String, String>,
) -> Vec<Check> {
    let mut checks = Vec::new();

    if is_markdown {
        checks.push(Check::pass(
            "content-negotiation",
            "server returned text/markdown",
        ));
    } else if status == 406 {
        checks.push(Check::fail(
            "content-negotiation",
            "server returned 406 Not Acceptable",
        ));
    } else {
        checks.push(Check::warn(
            "content-negotiation",
            format!("server returned content-type: {}", content_type),
        ));
    }

    let vary = headers
        .get("vary")
        .map(|s| s.to_lowercase())
        .unwrap_or_default();
    if vary.contains("accept") {
        checks.push(Check::pass("vary-accept", "Vary: accept header present"));
    } else {
        checks.push(Check::warn(
            "vary-accept",
            "Vary header does not include 'accept'",
        ));
    }

    let link = headers.get("link").map(|s| s.as_str()).unwrap_or("");
    if !link.is_empty() && (link.contains("text/markdown") || link.contains("alternate")) {
        checks.push(Check::pass(
            "link-header",
            "Link header references text/markdown or alternate",
        ));
    } else {
        checks.push(Check::warn(
            "link-header",
            "Link header missing or does not reference text/markdown",
        ));
    }

    checks
}

fn html_alternate_checks(html_body: &str) -> Vec<Check> {
    let document = Html::parse_document(html_body);
    let selector = Selector::parse("link[rel~='alternate']").unwrap();
    let found = document.select(&selector).any(|el| {
        el.value()
            .attr("type")
            .map(|t| t.contains("text/markdown"))
            .unwrap_or(false)
    });
    if found {
        vec![Check::pass(
            "html-alternate-link",
            "HTML has <link rel=\"alternate\" type=\"text/markdown\">",
        )]
    } else {
        vec![Check::warn(
            "html-alternate-link",
            "HTML does not have <link rel=\"alternate\" type=\"text/markdown\">",
        )]
    }
}

fn frontmatter_required_checks(fm: &Frontmatter) -> Vec<Check> {
    let mut checks = Vec::new();
    if fm.title.as_deref().map(|s| !s.is_empty()).unwrap_or(false) {
        checks.push(Check::pass("frontmatter-title", "title present"));
    } else {
        checks.push(Check::fail(
            "frontmatter-title",
            "required field 'title' is missing or empty",
        ));
    }
    if fm
        .description
        .as_deref()
        .map(|s| !s.is_empty())
        .unwrap_or(false)
    {
        checks.push(Check::pass("frontmatter-description", "description present"));
    } else {
        checks.push(Check::fail(
            "frontmatter-description",
            "required field 'description' is missing or empty",
        ));
    }
    checks
}

fn frontmatter_optional_checks(fm: &Frontmatter) -> Vec<Check> {
    let mut checks = Vec::new();
    if fm.date.is_some() {
        checks.push(Check::pass("frontmatter-date", "date present"));
    } else {
        checks.push(Check::warn(
            "frontmatter-date",
            "encouraged field 'date' missing",
        ));
    }
    if fm.license.is_some() {
        checks.push(Check::pass("frontmatter-license", "license present"));
    } else {
        checks.push(Check::warn(
            "frontmatter-license",
            "encouraged field 'license' missing",
        ));
    }
    if fm.author.is_some() {
        checks.push(Check::pass("frontmatter-author", "author present"));
    } else {
        checks.push(Check::warn(
            "frontmatter-author",
            "encouraged field 'author' missing",
        ));
    }
    checks
}

fn length_and_token_checks(fm: &Frontmatter, raw_fm: &str) -> Vec<Check> {
    let mut checks = Vec::new();

    if let Some(title) = &fm.title {
        if !title.is_empty() {
            let n = title.chars().count();
            let msg = format!("{} chars (target: 25–60)", n);
            if n >= 25 && n <= 60 {
                checks.push(Check::pass("title-length", msg));
            } else {
                checks.push(Check::warn("title-length", msg));
            }
        }
    }

    if let Some(desc) = &fm.description {
        if !desc.is_empty() {
            let n = desc.chars().count();
            let msg = format!("{} chars (target: 25–160)", n);
            if n >= 25 && n <= 160 {
                checks.push(Check::pass("description-length", msg));
            } else {
                checks.push(Check::warn("description-length", msg));
            }
        }
    }

    let fm_tokens = tokens::estimate(raw_fm);
    let msg = format!("{} tokens (target: ≤100)", fm_tokens);
    if fm_tokens <= 100 {
        checks.push(Check::pass("frontmatter-tokens", msg));
    } else {
        checks.push(Check::warn("frontmatter-tokens", msg));
    }

    checks
}

fn heading_checks(body: &str) -> Vec<Check> {
    let mut checks = Vec::new();
    let hc = parser::check_headings(body);
    if hc.starts_with_h1 {
        let h1 = hc.first_h1_text.as_deref().unwrap_or("");
        checks.push(Check::pass(
            "heading-h1",
            format!("starts with H1: \"{}\"", h1),
        ));
    } else {
        checks.push(Check::fail(
            "heading-h1",
            "body does not start with an H1 heading",
        ));
    }
    if hc.hierarchy_errors.is_empty() {
        checks.push(Check::pass("heading-hierarchy", "heading hierarchy is valid"));
    } else {
        for err in &hc.hierarchy_errors {
            checks.push(Check::fail("heading-hierarchy", err.clone()));
        }
    }
    checks
}

fn html_match_checks(fm: &Frontmatter, html_body: &str) -> Vec<Check> {
    let mut checks = Vec::new();
    let document = Html::parse_document(html_body);

    let title_sel = Selector::parse("title").unwrap();
    let html_title = document
        .select(&title_sel)
        .next()
        .map(|el| el.text().collect::<String>());

    let meta_sel = Selector::parse("meta[name='description']").unwrap();
    let html_desc = document
        .select(&meta_sel)
        .next()
        .and_then(|el| el.value().attr("content").map(|s| s.to_string()));

    if let Some(fm_title) = &fm.title {
        if !fm_title.is_empty() {
            match &html_title {
                Some(ht) => {
                    let fm_lower = fm_title.to_lowercase();
                    let ht_lower = ht.to_lowercase();
                    if ht_lower == fm_lower || ht_lower.contains(&fm_lower) {
                        checks.push(Check::pass(
                            "title-html-match",
                            "frontmatter title matches HTML title",
                        ));
                    } else {
                        checks.push(Check::warn(
                            "title-html-match",
                            format!(
                                "frontmatter title does not match HTML title: \"{}\"",
                                ht
                            ),
                        ));
                    }
                }
                None => checks.push(Check::warn(
                    "title-html-match",
                    "HTML <title> element not found",
                )),
            }
        }
    }

    if let Some(fm_desc) = &fm.description {
        if !fm_desc.is_empty() {
            match &html_desc {
                Some(hd) => {
                    if fm_desc.to_lowercase() == hd.to_lowercase() {
                        checks.push(Check::pass(
                            "description-html-match",
                            "frontmatter description matches HTML meta description",
                        ));
                    } else {
                        checks.push(Check::warn(
                            "description-html-match",
                            format!(
                                "frontmatter description does not match HTML meta description: \"{}\"",
                                hd
                            ),
                        ));
                    }
                }
                None => checks.push(Check::warn(
                    "description-html-match",
                    "HTML meta description not found",
                )),
            }
        }
    }

    checks
}

fn robots_checks(result: &Result<String, String>) -> Vec<Check> {
    match result {
        Ok(content) => {
            let mut checks = vec![Check::pass("robots-txt", "robots.txt is accessible")];
            if content.to_lowercase().contains("sitemap:") {
                checks.push(Check::pass(
                    "sitemap-in-robots",
                    "robots.txt references a sitemap",
                ));
            } else {
                checks.push(Check::warn(
                    "sitemap-in-robots",
                    "robots.txt does not contain a Sitemap: directive",
                ));
            }
            checks
        }
        Err(e) => vec![Check::warn("robots-txt", e.clone())],
    }
}

#[cfg(test)]
mod tests {
    use super::*;

    fn headers(pairs: &[(&str, &str)]) -> HashMap<String, String> {
        pairs
            .iter()
            .map(|(k, v)| (k.to_string(), v.to_string()))
            .collect()
    }

    fn fm(title: Option<&str>, description: Option<&str>) -> Frontmatter {
        Frontmatter {
            title: title.map(str::to_string),
            description: description.map(str::to_string),
            ..Default::default()
        }
    }

    // ── content_negotiation_checks ────────────────────────────────────────

    #[test]
    fn content_negotiation_pass_when_markdown() {
        let checks = content_negotiation_checks(true, 200, "text/markdown", &headers(&[]));
        let c = checks.iter().find(|c| c.name == "content-negotiation").unwrap();
        assert_eq!(c.status, crate::output::CheckStatus::Pass);
    }

    #[test]
    fn content_negotiation_fail_on_406() {
        let checks = content_negotiation_checks(false, 406, "text/html", &headers(&[]));
        let c = checks.iter().find(|c| c.name == "content-negotiation").unwrap();
        assert_eq!(c.status, crate::output::CheckStatus::Fail);
    }

    #[test]
    fn content_negotiation_warn_on_html() {
        let checks = content_negotiation_checks(false, 200, "text/html", &headers(&[]));
        let c = checks.iter().find(|c| c.name == "content-negotiation").unwrap();
        assert_eq!(c.status, crate::output::CheckStatus::Warn);
    }

    #[test]
    fn vary_accept_pass() {
        let checks = content_negotiation_checks(
            true,
            200,
            "text/markdown",
            &headers(&[("vary", "Accept, Content-Type")]),
        );
        let c = checks.iter().find(|c| c.name == "vary-accept").unwrap();
        assert_eq!(c.status, crate::output::CheckStatus::Pass);
    }

    #[test]
    fn vary_accept_warn_when_absent() {
        let checks = content_negotiation_checks(false, 200, "text/html", &headers(&[]));
        let c = checks.iter().find(|c| c.name == "vary-accept").unwrap();
        assert_eq!(c.status, crate::output::CheckStatus::Warn);
    }

    #[test]
    fn link_header_pass() {
        let checks = content_negotiation_checks(
            true,
            200,
            "text/markdown",
            &headers(&[("link", r#"<https://x.com/p>; rel="alternate"; type="text/markdown""#)]),
        );
        let c = checks.iter().find(|c| c.name == "link-header").unwrap();
        assert_eq!(c.status, crate::output::CheckStatus::Pass);
    }

    #[test]
    fn link_header_warn_when_absent() {
        let checks = content_negotiation_checks(false, 200, "text/html", &headers(&[]));
        let c = checks.iter().find(|c| c.name == "link-header").unwrap();
        assert_eq!(c.status, crate::output::CheckStatus::Warn);
    }

    // ── html_alternate_checks ─────────────────────────────────────────────

    #[test]
    fn html_alternate_link_pass() {
        let html = r#"<html><head><link rel="alternate" type="text/markdown" href="/p.md"></head></html>"#;
        let checks = html_alternate_checks(html);
        assert_eq!(checks[0].status, crate::output::CheckStatus::Pass);
    }

    #[test]
    fn html_alternate_link_warn_when_missing() {
        let checks = html_alternate_checks("<html><head></head></html>");
        assert_eq!(checks[0].status, crate::output::CheckStatus::Warn);
    }

    // ── frontmatter_required_checks ───────────────────────────────────────

    #[test]
    fn frontmatter_required_pass_both_present() {
        let checks = frontmatter_required_checks(&fm(Some("Title"), Some("Description")));
        assert!(checks.iter().all(|c| c.status == crate::output::CheckStatus::Pass));
    }

    #[test]
    fn frontmatter_required_fail_missing_title() {
        let checks = frontmatter_required_checks(&fm(None, Some("Description")));
        let c = checks.iter().find(|c| c.name == "frontmatter-title").unwrap();
        assert_eq!(c.status, crate::output::CheckStatus::Fail);
    }

    #[test]
    fn frontmatter_required_fail_missing_description() {
        let checks = frontmatter_required_checks(&fm(Some("Title"), None));
        let c = checks.iter().find(|c| c.name == "frontmatter-description").unwrap();
        assert_eq!(c.status, crate::output::CheckStatus::Fail);
    }

    #[test]
    fn frontmatter_required_fail_empty_title() {
        let checks = frontmatter_required_checks(&fm(Some(""), Some("Description")));
        let c = checks.iter().find(|c| c.name == "frontmatter-title").unwrap();
        assert_eq!(c.status, crate::output::CheckStatus::Fail);
    }

    // ── frontmatter_optional_checks ───────────────────────────────────────

    #[test]
    fn frontmatter_optional_three_warns_when_all_missing() {
        let checks = frontmatter_optional_checks(&Frontmatter::default());
        assert_eq!(checks.len(), 3);
        assert!(checks.iter().all(|c| c.status == crate::output::CheckStatus::Warn));
    }

    #[test]
    fn frontmatter_optional_pass_when_all_present() {
        let full = Frontmatter {
            date: Some("2024-01-01".into()),
            license: Some("MIT".into()),
            author: Some("Alice".into()),
            ..Default::default()
        };
        let checks = frontmatter_optional_checks(&full);
        assert!(checks.iter().all(|c| c.status == crate::output::CheckStatus::Pass));
    }

    // ── length_and_token_checks ───────────────────────────────────────────

    #[test]
    fn title_length_pass_within_range() {
        // 39 chars — inside 25–60
        let f = fm(Some("A title that is between 25 and 60 chars"), None);
        let checks = length_and_token_checks(&f, "---\ntitle: x\n---");
        let c = checks.iter().find(|c| c.name == "title-length").unwrap();
        assert_eq!(c.status, crate::output::CheckStatus::Pass);
    }

    #[test]
    fn title_length_warn_too_short() {
        let f = fm(Some("Short"), None);
        let checks = length_and_token_checks(&f, "---\ntitle: Short\n---");
        let c = checks.iter().find(|c| c.name == "title-length").unwrap();
        assert_eq!(c.status, crate::output::CheckStatus::Warn);
    }

    #[test]
    fn description_length_pass_within_range() {
        // 57 chars — inside 25–160
        let f = fm(None, Some("A description that is between 25 and 160 characters."));
        let checks = length_and_token_checks(&f, "---\ndescription: x\n---");
        let c = checks.iter().find(|c| c.name == "description-length").unwrap();
        assert_eq!(c.status, crate::output::CheckStatus::Pass);
    }

    #[test]
    fn description_length_warn_too_short() {
        let f = fm(None, Some("Too short"));
        let checks = length_and_token_checks(&f, "---\ndescription: x\n---");
        let c = checks.iter().find(|c| c.name == "description-length").unwrap();
        assert_eq!(c.status, crate::output::CheckStatus::Warn);
    }

    #[test]
    fn frontmatter_tokens_warn_when_over_100() {
        // Each line is 21 chars; 30 lines = 630 chars → ~158 tokens (>100)
        let long_fm = "---\n".to_string() + &"key_name: value_here\n".repeat(30) + "---";
        let checks = length_and_token_checks(&Frontmatter::default(), &long_fm);
        let c = checks.iter().find(|c| c.name == "frontmatter-tokens").unwrap();
        assert_eq!(c.status, crate::output::CheckStatus::Warn);
    }

    // ── heading_checks ────────────────────────────────────────────────────

    #[test]
    fn heading_h1_pass() {
        let checks = heading_checks("# Title\n\n## Section\n");
        let c = checks.iter().find(|c| c.name == "heading-h1").unwrap();
        assert_eq!(c.status, crate::output::CheckStatus::Pass);
    }

    #[test]
    fn heading_h1_fail_when_starts_with_h2() {
        let checks = heading_checks("## Section\n\nContent.\n");
        let c = checks.iter().find(|c| c.name == "heading-h1").unwrap();
        assert_eq!(c.status, crate::output::CheckStatus::Fail);
    }

    #[test]
    fn heading_hierarchy_pass() {
        let checks = heading_checks("# Title\n\n## Section\n\n### Sub\n");
        let c = checks.iter().find(|c| c.name == "heading-hierarchy").unwrap();
        assert_eq!(c.status, crate::output::CheckStatus::Pass);
    }

    #[test]
    fn heading_hierarchy_fail_on_skip() {
        let checks = heading_checks("# Title\n\n### Skipped H2\n");
        let c = checks.iter().find(|c| c.name == "heading-hierarchy").unwrap();
        assert_eq!(c.status, crate::output::CheckStatus::Fail);
    }

    // ── html_match_checks ─────────────────────────────────────────────────

    #[test]
    fn html_title_match_pass_exact() {
        let f = fm(Some("My Page Title"), None);
        let html = "<html><head><title>My Page Title</title></head></html>";
        let checks = html_match_checks(&f, html);
        let c = checks.iter().find(|c| c.name == "title-html-match").unwrap();
        assert_eq!(c.status, crate::output::CheckStatus::Pass);
    }

    #[test]
    fn html_title_match_pass_contained() {
        let f = fm(Some("My Page"), None);
        let html = "<html><head><title>My Page | Site Name</title></head></html>";
        let checks = html_match_checks(&f, html);
        let c = checks.iter().find(|c| c.name == "title-html-match").unwrap();
        assert_eq!(c.status, crate::output::CheckStatus::Pass);
    }

    #[test]
    fn html_title_match_warn_when_different() {
        let f = fm(Some("My Page Title"), None);
        let html = "<html><head><title>Completely Different</title></head></html>";
        let checks = html_match_checks(&f, html);
        let c = checks.iter().find(|c| c.name == "title-html-match").unwrap();
        assert_eq!(c.status, crate::output::CheckStatus::Warn);
    }

    #[test]
    fn html_description_match_pass() {
        let f = fm(None, Some("Exact description text"));
        let html = r#"<html><head><meta name="description" content="Exact description text"></head></html>"#;
        let checks = html_match_checks(&f, html);
        let c = checks.iter().find(|c| c.name == "description-html-match").unwrap();
        assert_eq!(c.status, crate::output::CheckStatus::Pass);
    }

    #[test]
    fn html_description_match_warn_when_missing() {
        let f = fm(None, Some("Some description"));
        let checks = html_match_checks(&f, "<html><head></head></html>");
        let c = checks.iter().find(|c| c.name == "description-html-match").unwrap();
        assert_eq!(c.status, crate::output::CheckStatus::Warn);
    }

    // ── robots_checks ─────────────────────────────────────────────────────

    #[test]
    fn robots_pass_with_sitemap() {
        let result = Ok::<_, String>("User-agent: *\nSitemap: https://example.com/sitemap.xml\n".into());
        let checks = robots_checks(&result);
        assert_eq!(checks[0].status, crate::output::CheckStatus::Pass);
        let c = checks.iter().find(|c| c.name == "sitemap-in-robots").unwrap();
        assert_eq!(c.status, crate::output::CheckStatus::Pass);
    }

    #[test]
    fn robots_warn_when_inaccessible() {
        let result = Err::<String, _>("404".into());
        let checks = robots_checks(&result);
        assert_eq!(checks.len(), 1);
        assert_eq!(checks[0].status, crate::output::CheckStatus::Warn);
    }

    #[test]
    fn robots_warn_missing_sitemap_directive() {
        let result = Ok::<_, String>("User-agent: *\nDisallow:\n".into());
        let checks = robots_checks(&result);
        let c = checks.iter().find(|c| c.name == "sitemap-in-robots").unwrap();
        assert_eq!(c.status, crate::output::CheckStatus::Warn);
    }
}
