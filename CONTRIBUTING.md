# Contributing to content-md

Thank you for your interest in contributing to content-md! This document explains how to contribute and where different types of feedback belong.

## Types of Contributions

### Documentation Improvements

We welcome improvements to the [documentation site](https://contentmd.org) — typo fixes, clarity improvements, better examples, and new guides. Documentation lives in the `source/` directory as `.blade.php` files.

### Bug Reports

Found a bug in the spec or documentation? [Open an issue](https://github.com/OneOffTech/contentmd/issues).

### Proposals, Questions, and Feedback

Have a feature request, spec design question, or general feedback? [Start a discussion](https://github.com/OneOffTech/contentmd/discussions). We use Discussions for proposals and open-ended conversation, and reserve Issues for concrete bugs and problems.

Proposals should address real implementation challenges you've encountered, not theoretical concerns. Show us the problem you faced and how your proposal addresses it.

We maintain a high bar for additions to the spec — it is much easier to add things to a specification than to remove them. Every new feature adds complexity that all implementers must understand and support. When in doubt, leave it out.

> [!NOTE]
> **Not sure where to post?** Default to [Discussions](https://github.com/OneOffTech/contentmd/discussions). If it turns out to be a bug, we'll convert it to an issue.

### Ecosystem Listings

If your product or platform has implemented content-md support, you can request to be listed on [contentmd.org](https://contentmd.org). Your implementation must be publicly available — we do not list products that have only announced intent to support content-md or are still in private beta.

Submit a pull request with:

1. **Logo files** — SVG preferred; PNG acceptable (min 200×200px). Provide light and dark variants.
2. **An ecosystem entry** — Add your product to the ecosystem section in the docs.
3. **Product information** — In your PR description, include your product name, a link to your product, and a link to documentation showing your content-md implementation.

We may ask for a demo or screenshot to verify the implementation.

### What We're Not Accepting (Yet)

To keep the project focused during this early stage, we are currently not accepting:

- **Major architectural changes** — We're still iterating on the core specification. Large-scale redesigns are premature.

If you're unsure whether your contribution fits, open a [Discussion](https://github.com/OneOffTech/contentmd/discussions) before investing significant effort.

## Development Setup

### Documentation Site

The documentation site is built with [Jigsaw](https://jigsaw.tighten.com/) and [Tailwind CSS](https://tailwindcss.com/).

```bash
# Install PHP dependencies
composer install

# Install JS dependencies
npm ci

# Run local dev server
npm run dev
```

In a separate terminal, serve the static site:

```bash
./vendor/bin/jigsaw serve
```

Local preview will be available at `http://localhost:8000`.

## Submitting Changes

1. [Fork the repository](https://docs.github.com/en/pull-requests/collaborating-with-pull-requests/working-with-forks/fork-a-repo)
2. Create a branch for your changes
3. Make your changes and verify they work locally
4. Submit a pull request

Keep PRs focused on one logical change and link any related issues.

## AI Contributions

> [!IMPORTANT]
> If you are using **any kind of AI assistance** to contribute to content-md, please disclose it in the pull request or issue.

We welcome the use of AI tools to help improve content-md. That said, if you used AI assistance (e.g., Claude Code, ChatGPT) while contributing, **disclose it in the pull request or issue**, along with the extent of AI involvement.

As an exception, trivial spacing or typo fixes don't need to be disclosed.

## License

By contributing, you agree that your contributions will be licensed under the [Apache License 2.0](LICENSE) for code, and [CC-BY 4.0](https://creativecommons.org/licenses/by/4.0/) for documentation and specification files.
