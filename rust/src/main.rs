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

    /// Return raw markdown without size/token metadata
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

    #[command(subcommand)]
    command: Option<Commands>,
}

#[derive(Subcommand)]
enum Commands {
    /// Validate content-md compliance for a URL
    Validate {
        url: String,
        /// Output format: plain, markdown, json
        #[arg(long, default_value = "plain")]
        format: String,
        /// Save JSON report to file for later comparison
        #[arg(long, value_name = "FILE")]
        save: Option<String>,
        /// Follow HTTP redirects instead of reporting them
        #[arg(long)]
        follow_redirect: bool,
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
    },
}

#[tokio::main]
async fn main() {
    let cli = Cli::parse();

    let result = match cli.command {
        Some(Commands::Validate { url, format, save, follow_redirect }) => {
            commands::validate::run(&url, &format, save.as_deref(), follow_redirect).await
        }
        Some(Commands::Skill { url, output, follow_redirect }) => {
            commands::skill::run(&url, output.as_deref(), follow_redirect).await
        }
        None => {
            if cli.urls.is_empty() {
                eprintln!("Provide at least one URL, or use a subcommand (validate, skill).");
                eprintln!("Run with --help for usage.");
                std::process::exit(1);
            }
            commands::browse::run(
                cli.urls,
                cli.agent,
                cli.frontmatter_only,
                cli.sitemap,
                cli.output,
                cli.follow_redirect,
            ).await
        }
    };

    if let Err(e) = result {
        eprintln!("Error: {}", e);
        std::process::exit(1);
    }
}
