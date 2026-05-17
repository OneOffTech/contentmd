# contentmd CLI

Command-line tool for browsing and validating [content-md](https://content-md.org) formatted web resources.

## Install

Pre-built binaries are available [under releases](https://github.com/OneOffTech/contentmd/releases) for major platforms.

## Commands

### Browse

Fetch a URL as an AI agent would — requesting `text/markdown` via content negotiation, falling back to HTML on 406.

```sh
contentmd https://contentmd.org/specification/
```

| Flag | Description |
|---|---|
| `--agent` | Raw markdown only, no size/token header |
| `--sitemap` | Fetch `/sitemap.xml` and iterate every URL in it |
| `--output <folder>` | Save each response as a `.md` file (required when multiple URLs are given) |
| `--follow-redirect` | Follow HTTP redirects (by default redirects are reported as an error) |
| `--frontmatter-only` | Send `Range: x-frontmatter` to fetch only the frontmatter |

```sh
# Multiple URLs saved to a folder
contentmd --output ./pages https://contentmd.org/specification/ https://contentmd.org/consumers/

# Entire site via sitemap
contentmd --sitemap --output ./pages https://contentmd.org
```

---

### Validate

Check that a URL correctly serves content-md and report compliance.

```sh
contentmd validate https://contentmd.org/specification/
```

Checks performed:

| Check | What it verifies |
|---|---|
| `content-negotiation` | Server returns `text/markdown` for `Accept: text/markdown` |
| `vary-accept` | Response includes `Vary: Accept` |
| `link-header` | HTTP `Link` header references the markdown alternate |
| `range-frontmatter` | `Range: x-frontmatter` returns only the frontmatter |
| `html-alternate-link` | HTML `<link rel="alternate" type="text/markdown">` present |
| `frontmatter-title` | `title` field present and non-empty |
| `frontmatter-description` | `description` field present and non-empty |
| `frontmatter-date/license/author` | Encouraged fields present |
| `title-length` | Title 25–60 characters |
| `description-length` | Description 25–160 characters |
| `frontmatter-tokens` | Frontmatter ≤ 100 tokens |
| `heading-h1` | Markdown body starts with an H1 |
| `heading-hierarchy` | No heading level skips |
| `title-html-match` | Frontmatter title matches HTML `<title>` |
| `description-html-match` | Frontmatter description matches HTML meta description |
| `robots-txt` | `robots.txt` accessible |
| `sitemap-in-robots` | `robots.txt` contains a `Sitemap:` directive |

Each check is rated **pass**, **warn**, or **fail**. The report includes a **score from 0 to 100** based on the share of passing checks.

```sh
# Machine-readable output
contentmd validate --format json https://contentmd.org/specification/

# Save a snapshot for later comparison
contentmd validate --save baseline.json https://contentmd.org/specification/

# Markdown table (useful in CI comments)
contentmd validate --format markdown https://contentmd.org/specification/
```

| Flag | Description |
|---|---|
| `--format plain\|markdown\|json` | Output format (default: `plain`) |
| `--save <file>` | Write JSON report to file |
| `--follow-redirect` | Follow HTTP redirects |
| `--agent` | Force JSON output for machine consumption |

---

### Skill

Convert a content-md page into an [Agent Skill](https://agentskills.io) (`SKILL.md`).

```sh
contentmd skill https://contentmd.org/specification/
```

Transformations applied to the frontmatter:

- `title` → `name` (slugified, lowercase, max 64 chars)
- `author` / `date` / `license` → nested under `metadata:`
- `source:` added to `metadata:` with the original URL

```sh
# Write to a file
contentmd skill --output SKILL.md https://contentmd.org/specification/
```

| Flag | Description |
|---|---|
| `--output <file>` | Write to file instead of stdout |
| `--follow-redirect` | Follow HTTP redirects |
| `--agent` | Output JSON `{"name", "description", "content"}` instead of raw SKILL.md |

---

## Agent mode

All three commands support `--agent`. When active:

- **browse** — outputs raw markdown with no size/token header
- **validate** — outputs JSON regardless of `--format`
- **skill** — outputs `{"name": "…", "description": "…", "content": "…"}` instead of SKILL.md text

Agent mode is also **auto-detected** from the environment. If any of the following are set, `--agent` is implied automatically:

`CLAUDECODE`, `CLAUDE_CODE`, `CURSOR_AGENT`, `AI_AGENT`, `GEMINI_CLI`, `CODEX_SANDBOX`, `CODEX_CI`, `CODEX_THREAD_ID`, `AUGMENT_AGENT`, `AMP_CURRENT_THREAD_ID`, `OPENCODE_CLIENT`, `OPENCODE`, `REPL_ID`, `ANTIGRAVITY_AGENT`, `PI_CODING_AGENT`, `KIRO_AGENT_PATH`, `COPILOT_MODEL`, `COPILOT_ALLOW_ALL`, `COPILOT_GITHUB_TOKEN`, `COPILOT_CLI`, `CLAUDE_CODE_IS_COWORK`

---

## Redirects

By default the CLI reports redirects rather than following them silently:

```
Error: server redirected to https://www.contentmd.org/ (HTTP 301) — use --follow-redirect to follow
```

Pass `--follow-redirect` to follow up to 10 hops automatically.

---

## Development

```sh
# Run the test suite
cargo test

# Build a release binary
cargo build --release
# binary at target/release/contentmd

# Lint (warnings are errors)
cargo clippy -- -D warnings
```

---

## Exit codes

| Code | Meaning |
|---|---|
| `0` | Success |
| `1` | Error (network failure, redirect without `--follow-redirect`, parse error, etc.) |
