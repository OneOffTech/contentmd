use crate::agent;
use crate::http::HttpClient;
use crate::output;
use crate::tokens;
use std::fs;
use std::fs::OpenOptions;
use std::io::Write;
use std::path::Path;
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
                    let path = Path::new(dir).join(&filename);
                    let bytes = result.raw_bytes.as_deref().unwrap_or(&[]);
                    let mut f = OpenOptions::new()
                        .write(true)
                        .create_new(true)
                        .open(&path)
                        .map_err(|e| {
                            if e.kind() == std::io::ErrorKind::AlreadyExists {
                                format!("refusing to overwrite existing file {}", path.display())
                            } else {
                                format!("failed to create {}: {}", path.display(), e)
                            }
                        })?;
                    f.write_all(bytes)
                        .map_err(|e| format!("failed to write {}: {}", path.display(), e))?;
                    println!(
                        "Saved: {} ({})",
                        path.display(),
                        output::format_size(result.size_bytes)
                    );
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
            let raw = u
                .path_segments()
                .and_then(|mut s| s.next_back())
                .filter(|s| !s.is_empty())
                .unwrap_or("download");
            sanitize_filename(raw)
        })
        .unwrap_or_else(|_| "download".to_string())
}

fn sanitize_filename(raw: &str) -> String {
    let decoded = percent_decode_lossy(raw);

    let replaced: String = decoded
        .chars()
        .map(|c| match c {
            '/' | '\\' | ':' | '*' | '?' | '"' | '<' | '>' | '|' => '_',
            c if c.is_control() => '_',
            c => c,
        })
        .collect();

    let mut s = replaced.as_str();
    while let Some(rest) = s.strip_prefix('.') {
        s = rest;
    }

    let s = s.trim_end_matches(|c: char| c == '.' || c.is_whitespace());

    if s.is_empty() || s == "." || s == ".." {
        return "download".to_string();
    }

    let stem_lower = s
        .split('.')
        .next()
        .unwrap_or(s)
        .to_ascii_lowercase();
    if is_windows_reserved(&stem_lower) {
        return "download".to_string();
    }

    if s.len() <= 128 {
        return s.to_string();
    }

    if let Some(dot_pos) = s.rfind('.') {
        let stem = &s[..dot_pos];
        let ext = &s[dot_pos..];
        if ext.len() < 128 {
            let max_stem_bytes = 128 - ext.len();
            let stem_trunc = truncate_to_char_boundary(stem, max_stem_bytes);
            return format!("{}{}", stem_trunc, ext);
        }
    }

    truncate_to_char_boundary(s, 128).to_string()
}

fn is_windows_reserved(name: &str) -> bool {
    matches!(
        name,
        "con" | "prn"
            | "aux"
            | "nul"
            | "com1"
            | "com2"
            | "com3"
            | "com4"
            | "com5"
            | "com6"
            | "com7"
            | "com8"
            | "com9"
            | "lpt1"
            | "lpt2"
            | "lpt3"
            | "lpt4"
            | "lpt5"
            | "lpt6"
            | "lpt7"
            | "lpt8"
            | "lpt9"
    )
}

fn truncate_to_char_boundary(s: &str, max_bytes: usize) -> &str {
    if s.len() <= max_bytes {
        return s;
    }
    let mut end = max_bytes;
    while end > 0 && !s.is_char_boundary(end) {
        end -= 1;
    }
    &s[..end]
}

fn percent_decode_lossy(input: &str) -> String {
    let bytes = input.as_bytes();
    let mut out: Vec<u8> = Vec::with_capacity(bytes.len());
    let mut i = 0;
    while i < bytes.len() {
        if bytes[i] == b'%' && i + 2 < bytes.len() {
            let hi = (bytes[i + 1] as char).to_digit(16);
            let lo = (bytes[i + 2] as char).to_digit(16);
            if let (Some(h), Some(l)) = (hi, lo) {
                out.push((h * 16 + l) as u8);
                i += 3;
                continue;
            }
        }
        out.push(bytes[i]);
        i += 1;
    }
    String::from_utf8_lossy(&out).into_owned()
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

    #[test]
    fn sanitize_strips_leading_dots() {
        assert_eq!(sanitize_filename(".bashrc"), "bashrc");
        assert_eq!(sanitize_filename("..env"), "env");
    }

    #[test]
    fn sanitize_replaces_path_separators() {
        assert_eq!(sanitize_filename("a/b"), "a_b");
        assert_eq!(sanitize_filename("a\\b"), "a_b");
    }

    #[test]
    fn sanitize_replaces_windows_reserved_chars() {
        assert_eq!(sanitize_filename("a:b*c?d"), "a_b_c_d");
    }

    #[test]
    fn sanitize_replaces_control_chars() {
        assert_eq!(sanitize_filename("a\0b"), "a_b");
        assert_eq!(sanitize_filename("a\nb"), "a_b");
    }

    #[test]
    fn sanitize_strips_trailing_dot_and_space() {
        assert_eq!(sanitize_filename("name. "), "name");
    }

    #[test]
    fn sanitize_empty_becomes_download() {
        assert_eq!(sanitize_filename(""), "download");
        assert_eq!(sanitize_filename("."), "download");
        assert_eq!(sanitize_filename(".."), "download");
        assert_eq!(sanitize_filename("...   "), "download");
    }

    #[test]
    fn sanitize_windows_reserved_name_becomes_download() {
        assert_eq!(sanitize_filename("CON"), "download");
        assert_eq!(sanitize_filename("con"), "download");
        assert_eq!(sanitize_filename("COM1"), "download");
        assert_eq!(sanitize_filename("lpt9"), "download");
        assert_eq!(sanitize_filename("CON.txt"), "download");
    }

    #[test]
    fn sanitize_truncates_long_names_preserving_extension() {
        let stem = "a".repeat(300);
        let input = format!("{}.pdf", stem);
        let out = sanitize_filename(&input);
        assert!(out.len() <= 128, "expected <= 128 bytes, got {}", out.len());
        assert!(out.ends_with(".pdf"), "expected .pdf suffix, got {}", out);
    }

    #[test]
    fn sanitize_percent_decodes() {
        assert_eq!(sanitize_filename("file%2Ename"), "file.name");
    }

    #[test]
    fn binary_filename_from_url_dotfile() {
        let result = binary_filename_from_url("https://example.com/.env");
        assert!(!result.starts_with('.'), "got: {}", result);
        assert!(!result.is_empty());
    }

    #[test]
    fn binary_filename_from_url_invalid_url_falls_back() {
        assert_eq!(binary_filename_from_url("not a url"), "download");
    }

    #[test]
    fn binary_filename_from_url_trailing_slash() {
        assert_eq!(
            binary_filename_from_url("https://example.com/foo/"),
            "download"
        );
    }

    #[test]
    fn no_clobber_create_new_errors_when_file_exists() {
        use std::fs;
        use std::process;
        use std::time::{SystemTime, UNIX_EPOCH};

        let nanos = SystemTime::now()
            .duration_since(UNIX_EPOCH)
            .unwrap()
            .as_nanos();
        let mut path = std::env::temp_dir();
        path.push(format!(
            "contentmd_clobber_test_{}_{}.bin",
            process::id(),
            nanos
        ));

        fs::write(&path, b"existing").expect("setup write failed");

        let result = OpenOptions::new()
            .write(true)
            .create_new(true)
            .open(&path);

        let err = result.expect_err("expected AlreadyExists error");
        assert_eq!(err.kind(), std::io::ErrorKind::AlreadyExists);

        let _ = fs::remove_file(&path);
    }
}
