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
    let sitemap_url = format!(
        "{}://{}/sitemap.xml",
        parsed.scheme(),
        parsed.host_str().unwrap_or("")
    );

    let result = client.fetch_markdown(&sitemap_url).await?;

    let mut urls = Vec::new();
    let mut remaining = result.body.as_str();
    while let Some(start) = remaining.find("<loc>") {
        let after_open = &remaining[start + 5..];
        if let Some(end) = after_open.find("</loc>") {
            urls.push(after_open[..end].trim().to_string());
            remaining = &after_open[end + 6..];
        } else {
            break;
        }
    }

    if urls.is_empty() {
        return Err(format!("no <loc> entries found in {}", sitemap_url));
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
}
