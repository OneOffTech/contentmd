use colored::Colorize;
use serde_json::json;

#[derive(Debug, Clone, PartialEq)]
pub enum CheckStatus {
    Pass,
    Warn,
    Fail,
}

#[derive(Debug, Clone)]
pub struct Check {
    pub name: String,
    pub status: CheckStatus,
    pub message: String,
}

impl Check {
    pub fn pass(name: impl Into<String>, message: impl Into<String>) -> Self {
        Check { name: name.into(), status: CheckStatus::Pass, message: message.into() }
    }
    pub fn warn(name: impl Into<String>, message: impl Into<String>) -> Self {
        Check { name: name.into(), status: CheckStatus::Warn, message: message.into() }
    }
    pub fn fail(name: impl Into<String>, message: impl Into<String>) -> Self {
        Check { name: name.into(), status: CheckStatus::Fail, message: message.into() }
    }
}

pub struct ValidationReport {
    pub url: String,
    pub checks: Vec<Check>,
    pub html_size: Option<usize>,
    pub markdown_size: Option<usize>,
    pub token_count: Option<usize>,
}

pub enum OutputFormat {
    Plain,
    Markdown,
    Json,
}

impl OutputFormat {
    pub fn from_str(s: &str) -> Self {
        match s.to_lowercase().as_str() {
            "markdown" | "md" => OutputFormat::Markdown,
            "json" => OutputFormat::Json,
            _ => OutputFormat::Plain,
        }
    }
}

pub(crate) fn score(checks: &[Check]) -> u32 {
    if checks.is_empty() {
        return 0;
    }
    let pass = checks.iter().filter(|c| c.status == CheckStatus::Pass).count() as u32;
    pass * 100 / checks.len() as u32
}

pub fn report_to_json_value(report: &ValidationReport) -> serde_json::Value {
    let checks: Vec<_> = report
        .checks
        .iter()
        .map(|c| {
            json!({
                "name": c.name,
                "status": match c.status {
                    CheckStatus::Pass => "pass",
                    CheckStatus::Warn => "warn",
                    CheckStatus::Fail => "fail",
                },
                "message": c.message,
            })
        })
        .collect();

    json!({
        "url": report.url,
        "score": score(&report.checks),
        "checks": checks,
        "html_size_bytes": report.html_size,
        "markdown_size_bytes": report.markdown_size,
        "estimated_tokens": report.token_count,
    })
}

pub fn print_validation_report(report: &ValidationReport, format: &OutputFormat) {
    match format {
        OutputFormat::Plain => print_plain(report),
        OutputFormat::Markdown => print_markdown(report),
        OutputFormat::Json => println!("{}", serde_json::to_string_pretty(&report_to_json_value(report)).unwrap()),
    }
}

fn print_plain(report: &ValidationReport) {
    println!("Validation: {}", report.url);
    println!("{}", "─".repeat(60));

    for check in &report.checks {
        let (icon, line) = match check.status {
            CheckStatus::Pass => (
                "✓",
                format!("✓ [{}] {}", check.name, check.message).green().to_string(),
            ),
            CheckStatus::Warn => (
                "⚠",
                format!("⚠ [{}] {}", check.name, check.message).yellow().to_string(),
            ),
            CheckStatus::Fail => (
                "✗",
                format!("✗ [{}] {}", check.name, check.message).red().to_string(),
            ),
        };
        let _ = icon;
        println!("{}", line);
    }

    println!("{}", "─".repeat(60));

    if let (Some(html), Some(md)) = (report.html_size, report.markdown_size) {
        let reduction = if html > 0 { 100 - (md * 100 / html) } else { 0 };
        println!("Size: {}B (HTML) → {}B (markdown), {}% smaller", html, md, reduction);
    }
    if let Some(tokens) = report.token_count {
        println!("Estimated tokens: {}", tokens);
    }

    let pass = report.checks.iter().filter(|c| c.status == CheckStatus::Pass).count();
    let warn = report.checks.iter().filter(|c| c.status == CheckStatus::Warn).count();
    let fail = report.checks.iter().filter(|c| c.status == CheckStatus::Fail).count();
    let s = score(&report.checks);
    let score_str = format!("Score: {}/100", s);
    let score_colored = if s >= 80 {
        score_str.green()
    } else if s >= 50 {
        score_str.yellow()
    } else {
        score_str.red()
    };
    println!(
        "\n{}  —  {}  {}  {}",
        score_colored,
        format!("{} passed", pass).green(),
        format!("{} warnings", warn).yellow(),
        format!("{} failed", fail).red(),
    );
}

fn print_markdown(report: &ValidationReport) {
    println!("## Validation: {} — Score: {}/100\n", report.url, score(&report.checks));
    println!("| Status | Check | Details |");
    println!("|--------|-------|---------|");

    for check in &report.checks {
        let icon = match check.status {
            CheckStatus::Pass => "✅",
            CheckStatus::Warn => "⚠️",
            CheckStatus::Fail => "❌",
        };
        println!("| {} | `{}` | {} |", icon, check.name, check.message);
    }

    if let (Some(html), Some(md)) = (report.html_size, report.markdown_size) {
        let reduction = if html > 0 { 100 - (md * 100 / html) } else { 0 };
        println!("\n**Size:** `{}B` HTML → `{}B` markdown ({}% reduction)", html, md, reduction);
    }
    if let Some(tokens) = report.token_count {
        println!("**Estimated tokens:** {}", tokens);
    }
}

#[cfg(test)]
mod tests {
    use super::*;

    #[test]
    fn score_all_pass_is_100() {
        let checks = vec![Check::pass("a", "ok"), Check::pass("b", "ok"), Check::pass("c", "ok")];
        assert_eq!(score(&checks), 100);
    }

    #[test]
    fn score_all_fail_is_zero() {
        let checks = vec![Check::fail("a", "x"), Check::fail("b", "x")];
        assert_eq!(score(&checks), 0);
    }

    #[test]
    fn score_empty_is_zero() {
        assert_eq!(score(&[]), 0);
    }

    #[test]
    fn score_two_of_three_pass() {
        let checks = vec![Check::pass("a", "ok"), Check::pass("b", "ok"), Check::fail("c", "x")];
        assert_eq!(score(&checks), 66);
    }

    #[test]
    fn score_warns_do_not_count_as_pass() {
        let checks = vec![Check::pass("a", "ok"), Check::warn("b", "?"), Check::warn("c", "?")];
        assert_eq!(score(&checks), 33);
    }

    #[test]
    fn json_value_includes_score_field() {
        let report = ValidationReport {
            url: "https://example.com".into(),
            checks: vec![Check::pass("a", "ok"), Check::fail("b", "x")],
            html_size: None,
            markdown_size: None,
            token_count: None,
        };
        let val = report_to_json_value(&report);
        assert_eq!(val["score"], 50);
    }

    #[test]
    fn json_value_includes_all_top_level_fields() {
        let report = ValidationReport {
            url: "https://example.com".into(),
            checks: vec![],
            html_size: Some(1000),
            markdown_size: Some(200),
            token_count: Some(50),
        };
        let val = report_to_json_value(&report);
        assert_eq!(val["url"], "https://example.com");
        assert_eq!(val["html_size_bytes"], 1000);
        assert_eq!(val["markdown_size_bytes"], 200);
        assert_eq!(val["estimated_tokens"], 50);
    }
}

pub fn print_browse_result(url: &str, content: &str, size_bytes: usize, tokens: usize, agent_mode: bool) {
    if agent_mode {
        print!("{}", content);
        return;
    }
    println!("URL:    {}", url);
    println!("Size:   {}B  |  Estimated tokens: {}", size_bytes, tokens);
    println!("{}", "─".repeat(60));
    println!("{}", content);
}
