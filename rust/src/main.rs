mod agent;
mod commands;
mod http;
mod output;
mod parser;
mod tokens;

use clap::{Parser, Subcommand};

#[derive(Parser)]
#[command(name = "contentmd", about = "Browse and validate content-md formatted web resources", version)]
struct Cli {
    /// URLs to fetch
    urls: Vec<String>,

    /// Output raw markdown only, no size/token metadata (also auto-detected from agent env)
    #[arg(long)]
    agent: bool,

    /// Request only the frontmatter section (Range: x-frontmatter)
    #[arg(long)]
    frontmatter_only: bool,

    /// Fetch sitemap and iterate all its URLs
    #[arg(long)]
    sitemap: bool,

    /// Output folder for saving files (required when multiple URLs are given)
    #[arg(long, value_name = "FOLDER")]
    output: Option<String>,

    /// Follow HTTP redirects instead of reporting them
    #[arg(long)]
    follow_redirect: bool,

    /// Delay in milliseconds between requests when fetching multiple URLs (default: 500)
    #[arg(long, value_name = "MS", default_value_t = 500)]
    delay: u64,

    /// Maximum number of URLs to fetch in one invocation (default: 100)
    #[arg(long, value_name = "N", default_value_t = 100)]
    max_urls: usize,

    #[command(subcommand)]
    command: Option<Commands>,
}

#[derive(Subcommand)]
enum Commands {
    /// Validate content-md compliance for a URL
    Validate {
        url: String,
        /// Output format: plain, markdown, json (overridden to json in agent mode)
        #[arg(long, default_value = "plain")]
        format: String,
        /// Save JSON report to file for later comparison
        #[arg(long, value_name = "FILE")]
        save: Option<String>,
        /// Follow HTTP redirects instead of reporting them
        #[arg(long)]
        follow_redirect: bool,
        /// Output compact JSON for machine consumption (also auto-detected from agent env)
        #[arg(long)]
        agent: bool,
    },
    /// Convert a content-md page to an Agent Skill (SKILL.md)
    Skill {
        url: String,
        /// Write output to file instead of stdout
        #[arg(long, value_name = "FILE")]
        output: Option<String>,
        /// Follow HTTP redirects instead of reporting them
        #[arg(long)]
        follow_redirect: bool,
        /// Output JSON for machine consumption (also auto-detected from agent env)
        #[arg(long)]
        agent: bool,
    },
}

#[tokio::main]
async fn main() {
    let cli = Cli::parse();

    let result = match cli.command {
        Some(Commands::Validate { url, format, save, follow_redirect, agent }) => {
            commands::validate::run(&url, &format, save.as_deref(), follow_redirect, agent).await
        }
        Some(Commands::Skill { url, output, follow_redirect, agent }) => {
            commands::skill::run(&url, output.as_deref(), follow_redirect, agent).await
        }
        None => {
            if cli.urls.is_empty() {
                eprintln!("Provide at least one URL, or use a subcommand (validate, skill).");
                eprintln!("Run with --help for usage.");
                std::process::exit(1);
            }
            commands::browse::run(commands::browse::BrowseOptions {
                urls: cli.urls,
                agent: cli.agent,
                frontmatter_only: cli.frontmatter_only,
                use_sitemap: cli.sitemap,
                output_dir: cli.output,
                follow_redirect: cli.follow_redirect,
                delay_ms: cli.delay,
                max_urls: cli.max_urls,
            })
            .await
        }
    };

    if let Err(e) = result {
        eprintln!("Error: {}", e);
        std::process::exit(1);
    }
}
