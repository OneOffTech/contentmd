use crate::agent;
use crate::http::HttpClient;
use crate::output;
use crate::tokens;
use std::fs;
use url::Url;

pub async fn run(
    urls: Vec<String>,
    agent: bool,
    frontmatter_only: bool,
    use_sitemap: bool,
    output_dir: Option<String>,
    follow_redirect: bool,
) -> Result<(), String> {
    let client = HttpClient::new(follow_redirect);
    let agent = agent::effective(agent);

    let resolved_urls = if use_sitemap {
        if urls.len() != 1 {
            return Err("--sitemap requires exactly one base URL".into());
        }
        fetch_sitemap_urls(&client, &urls[0]).await?
    } else {
        urls
    };

    if resolved_urls.len() > 1 && output_dir.is_none() {
        return Err("multiple URLs require --output <FOLDER>".into());
    }

    if let Some(ref dir) = output_dir {
        fs::create_dir_all(dir).map_err(|e| format!("failed to create output dir: {}", e))?;
    }

    for url in &resolved_urls {
        let result = if frontmatter_only {
            client.fetch_frontmatter_only(url).await?
        } else {
            client.fetch_markdown(url).await?
        };

        if result.is_binary {
            match output_dir {
                None => {
                    return Err(format!(
                        "{} returned a binary file ({}); provide --output <FOLDER> to save it",
                        url, result.content_type
                    ));
                }
                Some(ref dir) => {
                    let filename = binary_filename_from_url(url);
                    let path = format!("{}/{}", dir, filename);
                    let bytes = result.raw_bytes.as_deref().unwrap_or(&[]);
                    fs::write(&path, bytes)
                        .map_err(|e| format!("failed to write {}: {}", path, e))?;
                    println!("Saved: {} ({})", path, output::format_size(result.size_bytes));
                }
            }
            continue;
        }

        let tokens = tokens::estimate(&result.body);

        if let Some(ref dir) = output_dir {
            let filename = url_to_filename(url);
            let path = format!("{}/{}", dir, filename);
            fs::write(&path, &result.body)
                .map_err(|e| format!("failed to write {}: {}", path, e))?;
            println!("Saved: {}", path);
        } else {
            output::print_browse_result(url, &result.body, result.size_bytes, tokens, agent);
        }
    }

    Ok(())
}

async fn fetch_sitemap_urls(client: &HttpClient, base_url: &str) -> Result<Vec<String>, String> {
    let parsed = Url::parse(base_url).map_err(|e| format!("invalid URL: {}", e))?;
    let host = parsed
        .host_str()
        .ok_or_else(|| format!("invalid URL: missing host in {}", base_url))?;
    let sitemap_url = format!("{}://{}/sitemap.xml", parsed.scheme(), host);

    let result = client.fetch_markdown(&sitemap_url).await?;
    filter_sitemap_urls(&sitemap_url, &result.body)
}

fn filter_sitemap_urls(sitemap_url: &str, body: &str) -> Result<Vec<String>, String> {
    let sitemap_parsed = Url::parse(sitemap_url)
        .map_err(|e| format!("invalid sitemap URL {}: {}", sitemap_url, e))?;
    let expected_host = sitemap_parsed
        .host_str()
        .ok_or_else(|| format!("invalid sitemap URL: missing host in {}", sitemap_url))?
        .to_lowercase();

    let mut urls = Vec::new();
    let mut rejected: Vec<String> = Vec::new();
    let mut found_any = false;
    let mut remaining = body;
    while let Some(start) = remaining.find("<loc>") {
        let after_open = &remaining[start + 5..];
        let Some(end) = after_open.find("</loc>") else {
            break;
        };
        let loc = after_open[..end].trim().to_string();
        remaining = &after_open[end + 6..];
        found_any = true;

        match Url::parse(&loc) {
            Ok(parsed_loc) => {
                let scheme = parsed_loc.scheme();
                if scheme != "http" && scheme != "https" {
                    rejected.push(loc);
                    continue;
                }
                match parsed_loc.host_str() {
                    Some(h) if h.to_lowercase() == expected_host => urls.push(loc),
                    _ => rejected.push(loc),
                }
            }
            Err(url::ParseError::RelativeUrlWithoutBase) => match sitemap_parsed.join(&loc) {
                Ok(resolved) => urls.push(resolved.to_string()),
                Err(_) => rejected.push(loc),
            },
            Err(_) => rejected.push(loc),
        }
    }

    if !found_any {
        return Err(format!("no <loc> entries found in {}", sitemap_url));
    }

    if !rejected.is_empty() {
        const MAX_SHOWN: usize = 5;
        let shown = rejected
            .iter()
            .take(MAX_SHOWN)
            .map(String::as_str)
            .collect::<Vec<_>>()
            .join(", ");
        let suffix = if rejected.len() > MAX_SHOWN {
            format!(", and {} more", rejected.len() - MAX_SHOWN)
        } else {
            String::new()
        };
        return Err(format!(
            "sitemap at {} contains URLs whose host does not match {}: {}{}",
            sitemap_url, expected_host, shown, suffix
        ));
    }

    Ok(urls)
}

fn binary_filename_from_url(url: &str) -> String {
    Url::parse(url)
        .map(|u| {
            u.path_segments()
                .and_then(|mut s| s.next_back())
                .filter(|s| !s.is_empty())
                .unwrap_or("download")
                .to_string()
        })
        .unwrap_or_else(|_| "download".to_string())
}

pub(crate) fn url_to_filename(url: &str) -> String {
    let path = Url::parse(url)
        .map(|u| u.path().to_string())
        .unwrap_or_else(|_| "/".to_string());

    let trimmed = path.trim_matches('/');

    if trimmed.is_empty() {
        return "index.md".to_string();
    }

    let name = trimmed.replace('/', "_");
    format!("{}.md", name)
}

#[cfg(test)]
mod tests {
    use super::*;

    #[test]
    fn filename_root_slash() {
        assert_eq!(url_to_filename("https://example.com/"), "index.md");
    }

    #[test]
    fn filename_bare_host() {
        assert_eq!(url_to_filename("https://example.com"), "index.md");
    }

    #[test]
    fn filename_single_segment() {
        assert_eq!(url_to_filename("https://example.com/about"), "about.md");
    }

    #[test]
    fn filename_nested_path() {
        assert_eq!(url_to_filename("https://example.com/blog/my-post"), "blog_my-post.md");
    }

    #[test]
    fn filename_ignores_query_string() {
        assert_eq!(url_to_filename("https://example.com/page?foo=bar"), "page.md");
    }

    #[test]
    fn filter_sitemap_urls_accepts_same_host() {
        let body = "<loc>https://example.com/a</loc><loc>https://example.com/b/c</loc>";
        let result = filter_sitemap_urls("https://example.com/sitemap.xml", body).unwrap();
        assert_eq!(
            result,
            vec!["https://example.com/a", "https://example.com/b/c"]
        );
    }

    #[test]
    fn filter_sitemap_urls_rejects_different_host() {
        let body = "<loc>https://evil.example/x</loc>";
        let err = filter_sitemap_urls("https://example.com/sitemap.xml", body).unwrap_err();
        assert!(err.contains("evil.example"), "error was: {}", err);
        assert!(err.contains("example.com"), "error was: {}", err);
    }

    #[test]
    fn filter_sitemap_urls_rejects_subdomain_mismatch() {
        let body = "<loc>https://www.example.com/x</loc>";
        let err = filter_sitemap_urls("https://example.com/sitemap.xml", body).unwrap_err();
        assert!(err.contains("www.example.com"), "error was: {}", err);
    }

    #[test]
    fn filter_sitemap_urls_case_insensitive_host() {
        let body = "<loc>https://example.com/x</loc>";
        let result = filter_sitemap_urls("https://Example.COM/sitemap.xml", body).unwrap();
        assert_eq!(result, vec!["https://example.com/x"]);
    }

    #[test]
    fn filter_sitemap_urls_rejects_non_http_scheme() {
        let body = "<loc>file:///etc/passwd</loc>";
        let err = filter_sitemap_urls("https://example.com/sitemap.xml", body).unwrap_err();
        assert!(err.contains("file:///etc/passwd"), "error was: {}", err);

        let body = "<loc>ftp://example.com/x</loc>";
        let err = filter_sitemap_urls("https://example.com/sitemap.xml", body).unwrap_err();
        assert!(err.contains("ftp://example.com/x"), "error was: {}", err);
    }

    #[test]
    fn filter_sitemap_urls_resolves_relative_paths() {
        let body = "<loc>/about</loc><loc>blog/post</loc>";
        let result = filter_sitemap_urls("https://example.com/sitemap.xml", body).unwrap();
        let expected_about = Url::parse("https://example.com/sitemap.xml")
            .unwrap()
            .join("/about")
            .unwrap()
            .to_string();
        let expected_post = Url::parse("https://example.com/sitemap.xml")
            .unwrap()
            .join("blog/post")
            .unwrap()
            .to_string();
        assert_eq!(result, vec![expected_about, expected_post]);
        assert!(result.iter().all(|u| u.starts_with("https://example.com/")));
    }

    #[test]
    fn filter_sitemap_urls_reports_multiple_bad_entries() {
        let body =
            "<loc>https://evil.example/x</loc><loc>https://other.example/y</loc>";
        let err = filter_sitemap_urls("https://example.com/sitemap.xml", body).unwrap_err();
        assert!(err.contains("evil.example"), "error was: {}", err);
        assert!(err.contains("other.example"), "error was: {}", err);
    }

    #[test]
    fn filter_sitemap_urls_empty_body() {
        let err = filter_sitemap_urls("https://example.com/sitemap.xml", "").unwrap_err();
        assert!(err.contains("no <loc> entries found"), "error was: {}", err);
    }

    #[test]
    fn filter_sitemap_urls_one_bad_aborts_whole_call() {
        let body = "<loc>https://example.com/a</loc><loc>https://evil.example/x</loc>";
        let result = filter_sitemap_urls("https://example.com/sitemap.xml", body);
        assert!(result.is_err());
    }
}
