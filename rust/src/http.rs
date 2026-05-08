use reqwest::{Client, Response};
use std::collections::HashMap;
use url::Url;

pub struct FetchResult {
    pub status: u16,
    pub content_type: String,
    pub headers: HashMap<String, String>,
    pub body: String,
    pub size_bytes: usize,
    pub is_markdown: bool,
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
        let resp = self
            .client
            .get(url)
            .header("Accept", "text/markdown, text/html;q=0.9, */*;q=0.8")
            .send()
            .await
            .map_err(|e| e.to_string())?;
        self.process_response(resp).await
    }

    pub async fn fetch_frontmatter_only(&self, url: &str) -> Result<FetchResult, String> {
        let resp = self
            .client
            .get(url)
            .header("Accept", "text/markdown")
            .header("Range", "x-frontmatter")
            .send()
            .await
            .map_err(|e| e.to_string())?;
        self.process_response(resp).await
    }

    pub async fn fetch_html(&self, url: &str) -> Result<FetchResult, String> {
        let resp = self
            .client
            .get(url)
            .header("Accept", "text/html, */*;q=0.8")
            .send()
            .await
            .map_err(|e| e.to_string())?;
        self.process_response(resp).await
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
        })
    }
}
