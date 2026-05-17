use reqwest::{Client, RequestBuilder, Response};
use std::collections::HashMap;
use std::time::Duration;
use url::Url;

pub struct FetchResult {
    pub status: u16,
    pub content_type: String,
    pub headers: HashMap<String, String>,
    pub body: String,
    pub size_bytes: usize,
    pub is_markdown: bool,
    pub is_binary: bool,
    pub raw_bytes: Option<Vec<u8>>,
}

pub struct HttpClient {
    client: Client,
}

impl HttpClient {
    pub fn new(follow_redirects: bool) -> Self {
        let redirect_policy = if follow_redirects {
            reqwest::redirect::Policy::limited(10)
        } else {
            reqwest::redirect::Policy::none()
        };
        let client = Client::builder()
            .user_agent("contentmd-cli/0.1 (content-md validator; https://content-md.org)")
            .redirect(redirect_policy)
            .build()
            .expect("failed to build HTTP client");
        Self { client }
    }

    pub async fn fetch_markdown(&self, url: &str) -> Result<FetchResult, String> {
        let req = self
            .client
            .get(url)
            .header("Accept", "text/markdown, text/html;q=0.9, */*;q=0.8");
        let resp = self.send_with_retry(req, url).await?;
        self.process_response(resp).await
    }

    pub async fn fetch_frontmatter_only(&self, url: &str) -> Result<FetchResult, String> {
        let req = self
            .client
            .get(url)
            .header("Accept", "text/markdown")
            .header("Range", "x-frontmatter");
        let resp = self.send_with_retry(req, url).await?;
        self.process_response(resp).await
    }

    pub async fn fetch_html(&self, url: &str) -> Result<FetchResult, String> {
        let req = self
            .client
            .get(url)
            .header("Accept", "text/html, */*;q=0.8");
        let resp = self.send_with_retry(req, url).await?;
        self.process_response(resp).await
    }

    async fn send_with_retry(
        &self,
        req: RequestBuilder,
        url: &str,
    ) -> Result<Response, String> {
        let retry_req = req.try_clone();
        let resp = req.send().await.map_err(|e| e.to_string())?;
        let status = resp.status().as_u16();

        if status != 429 && status != 503 {
            return Ok(resp);
        }

        let Some(retry_req) = retry_req else {
            return Ok(resp);
        };

        let retry_after_raw = resp
            .headers()
            .get("retry-after")
            .and_then(|v| v.to_str().ok())
            .map(|s| s.to_string());
        let wait = parse_retry_after(retry_after_raw.as_deref());

        drop(resp);

        if !wait.is_zero() {
            tokio::time::sleep(wait).await;
        }

        let resp2 = retry_req.send().await.map_err(|e| e.to_string())?;
        let status2 = resp2.status().as_u16();
        if status2 == 429 || status2 == 503 {
            return Err(format!(
                "{} {} for {} (retried once with Retry-After={}s)",
                status2,
                status_reason(status2),
                url,
                wait.as_secs()
            ));
        }
        Ok(resp2)
    }

    pub async fn fetch_robots_txt(&self, url: &str) -> Result<String, String> {
        let base = Url::parse(url).map_err(|e| e.to_string())?;
        let robots_url = format!(
            "{}://{}/robots.txt",
            base.scheme(),
            base.host_str().unwrap_or("")
        );
        let resp = self
            .client
            .get(&robots_url)
            .send()
            .await
            .map_err(|e| e.to_string())?;
        if resp.status().is_success() {
            resp.text().await.map_err(|e| e.to_string())
        } else {
            Err(format!("robots.txt returned {}", resp.status()))
        }
    }

    async fn process_response(&self, resp: Response) -> Result<FetchResult, String> {
        let status = resp.status().as_u16();

        if (300..400).contains(&status) {
            let location = resp
                .headers()
                .get("location")
                .and_then(|v| v.to_str().ok())
                .unwrap_or("(no Location header)")
                .to_string();
            return Err(format!(
                "server redirected to {} (HTTP {}) — use --follow-redirect to follow",
                location, status
            ));
        }

        let content_type = resp
            .headers()
            .get("content-type")
            .and_then(|v| v.to_str().ok())
            .unwrap_or("")
            .to_string();

        let mut headers: HashMap<String, String> = HashMap::new();
        for (name, value) in resp.headers() {
            if let Ok(v) = value.to_str() {
                headers.insert(name.as_str().to_lowercase(), v.to_string());
            }
        }

        if is_binary_content_type(&content_type) {
            let bytes = resp.bytes().await.map_err(|e| e.to_string())?;
            let size_bytes = bytes.len();
            return Ok(FetchResult {
                status,
                content_type,
                headers,
                body: String::new(),
                size_bytes,
                is_markdown: false,
                is_binary: true,
                raw_bytes: Some(bytes.to_vec()),
            });
        }

        let body = resp.text().await.map_err(|e| e.to_string())?;
        let size_bytes = body.len();
        let is_markdown =
            content_type.contains("text/markdown") || body.trim_start().starts_with("---");

        Ok(FetchResult {
            status,
            content_type,
            headers,
            body,
            size_bytes,
            is_markdown,
            is_binary: false,
            raw_bytes: None,
        })
    }
}

fn parse_retry_after(value: Option<&str>) -> Duration {
    let Some(v) = value else {
        return Duration::ZERO;
    };
    let trimmed = v.trim();
    let Ok(secs) = trimmed.parse::<u64>() else {
        return Duration::ZERO;
    };
    Duration::from_secs(secs.min(60))
}

fn status_reason(status: u16) -> &'static str {
    match status {
        429 => "Too Many Requests",
        503 => "Service Unavailable",
        _ => "",
    }
}

fn is_binary_content_type(ct: &str) -> bool {
    let base = ct.split(';').next().unwrap_or("").trim().to_lowercase();
    base.starts_with("image/")
        || base.starts_with("audio/")
        || base.starts_with("video/")
        || matches!(
            base.as_str(),
            "application/pdf"
                | "application/octet-stream"
                | "application/zip"
                | "application/x-zip-compressed"
                | "application/gzip"
                | "application/x-tar"
                | "application/x-rar-compressed"
                | "font/woff"
                | "font/woff2"
                | "font/ttf"
                | "font/otf"
        )
}

#[cfg(test)]
mod tests {
    use super::*;

    #[test]
    fn pdf_is_binary() {
        assert!(is_binary_content_type("application/pdf"));
    }

    #[test]
    fn pdf_with_charset_is_binary() {
        assert!(is_binary_content_type("application/pdf; charset=utf-8"));
    }

    #[test]
    fn image_is_binary() {
        assert!(is_binary_content_type("image/png"));
        assert!(is_binary_content_type("image/jpeg"));
    }

    #[test]
    fn audio_video_are_binary() {
        assert!(is_binary_content_type("audio/mpeg"));
        assert!(is_binary_content_type("video/mp4"));
    }

    #[test]
    fn html_is_not_binary() {
        assert!(!is_binary_content_type("text/html"));
        assert!(!is_binary_content_type("text/html; charset=utf-8"));
    }

    #[test]
    fn markdown_is_not_binary() {
        assert!(!is_binary_content_type("text/markdown"));
    }

    #[test]
    fn json_is_not_binary() {
        assert!(!is_binary_content_type("application/json"));
    }

    #[test]
    fn retry_after_parses_delta_seconds() {
        assert_eq!(parse_retry_after(Some("5")), Duration::from_secs(5));
        assert_eq!(parse_retry_after(Some("abc")), Duration::ZERO);
        assert_eq!(parse_retry_after(Some("9999")), Duration::from_secs(60));
        assert_eq!(parse_retry_after(None), Duration::ZERO);
        assert_eq!(parse_retry_after(Some("0")), Duration::ZERO);
        assert_eq!(parse_retry_after(Some(" 7 ")), Duration::from_secs(7));
    }
}
