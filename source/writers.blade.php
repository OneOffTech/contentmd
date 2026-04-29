---
title: content-md for writers
description: Best practices and guidelines for writing content-md documents — how to structure frontmatter, markdown body, and custom blocks for optimal AI agent consumption.
---

@extends('_layouts.main')

@section('body')

    {{-- Page header --}}
    <div class="relative overflow-hidden agent:contents grid grid-cols-1 grid-rows-1">

        <div class="col-start-1 row-start-1 grid grid-cols-5 grid-flow-row-dense" aria-hidden="true">
            <div class="col-start-3 w-52 h-52 hatch text-orange-500/40 dark:text-orange-400/40 pointer-events-none agent:hidden" aria-hidden="true"></div>
            <div class="col-start-4 place-self-end w-24 h-24 hatch text-zinc-500/40 dark:text-zinc-400/40 pointer-events-none agent:hidden" aria-hidden="true"></div>
        </div>

        <div class="relative mt-8 pb-12 col-start-1 row-start-1">
            <x-container class="mb-6">
                <div class="flex flex-col gap-6">
                    <div class="flex flex-col items-start gap-6">
                        <x-eyebrow>For Content Producers</x-eyebrow>
                        <h1 class="text-balance text-zinc-950 dark:text-white font-black text-5xl sm:text-6xl m-0 leading-[1.05] tracking-tight max-w-4xl agent:max-w-none">Write content AI agents can actually use.</h1>
                        <p class="flex max-w-2xl flex-col gap-4 text-zinc-600 dark:text-zinc-300 text-base/7">
                            Best practices for writing content-md documents — from describing your content clearly in frontmatter to structuring a Markdown body that works well within AI context windows.
                        </p>
                    </div>
                </div>
            </x-container>
        </div>
    </div>


    {{-- Frontmatter best practices --}}
    <div class="pt-8">

        <x-section>
            <x-slot name="eyebrow"><x-eyebrow>Frontmatter</x-eyebrow></x-slot>

            The first ~100 tokens.

            <x-slot name="subheadline">The frontmatter block is the first thing an AI agent reads. It decides whether to fetch the full document based on this alone — so every field matters.</x-slot>
        </x-section>

        <x-container class="mt-4 mb-12">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 agent:flex agent:flex-col">

                <div class="flex flex-col gap-6">

                    <div class="rounded-lg p-5 bg-zinc-950/2.5 dark:bg-white/5 ring-1 ring-zinc-200 dark:ring-zinc-700">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="inline-block text-xs font-medium px-2 py-0.5 rounded-full bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200">Required</span>
                            <h3 class="text-sm font-semibold text-zinc-950 dark:text-white">Write a precise description</h3>
                        </div>
                        <p class="text-xs/5 text-zinc-600 dark:text-zinc-400">The <code class="bg-zinc-200 dark:bg-zinc-700 px-1 rounded">description</code> field is the most important signal for relevance. Aim for ~200 characters. Be specific — avoid generic phrases like "a blog post about X". State what the reader will learn or get from the content.</p>
                    </div>

                    <div class="rounded-lg p-5 bg-zinc-950/2.5 dark:bg-white/5 ring-1 ring-zinc-200 dark:ring-zinc-700">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="inline-block text-xs font-medium px-2 py-0.5 rounded-full bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200">Encouraged</span>
                            <h3 class="text-sm font-semibold text-zinc-950 dark:text-white">Always include a date</h3>
                        </div>
                        <p class="text-xs/5 text-zinc-600 dark:text-zinc-400">AI agents use <code class="bg-zinc-200 dark:bg-zinc-700 px-1 rounded">date</code> to assess freshness. A guide published in 2019 may have outdated advice — agents knowing this can weight it accordingly or flag it. Use ISO 8601: <code class="bg-zinc-200 dark:bg-zinc-700 px-1 rounded">2024-03-15</code>.</p>
                    </div>

                    <div class="rounded-lg p-5 bg-zinc-950/2.5 dark:bg-white/5 ring-1 ring-zinc-200 dark:ring-zinc-700">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="inline-block text-xs font-medium px-2 py-0.5 rounded-full bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200">Encouraged</span>
                            <h3 class="text-sm font-semibold text-zinc-950 dark:text-white">Declare a license</h3>
                        </div>
                        <p class="text-xs/5 text-zinc-600 dark:text-zinc-400">The <code class="bg-zinc-200 dark:bg-zinc-700 px-1 rounded">license</code> field signals how your content may be used. Use an <a href="https://spdx.org/licenses/" target="_blank" rel="noopener noreferrer" class="underline hover:text-zinc-950 dark:hover:text-white">SPDX identifier</a> like <code class="bg-zinc-200 dark:bg-zinc-700 px-1 rounded">CC-BY-4.0</code> or <code class="bg-zinc-200 dark:bg-zinc-700 px-1 rounded">CC-BY-NC-4.0</code>. Omitting it leaves agents without guidance on permissible use.</p>
                    </div>

                    <div class="rounded-lg p-5 bg-zinc-950/2.5 dark:bg-white/5 ring-1 ring-zinc-200 dark:ring-zinc-700">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="inline-block text-xs font-medium px-2 py-0.5 rounded-full bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400">Tip</span>
                            <h3 class="text-sm font-semibold text-zinc-950 dark:text-white">Keep frontmatter within ~540 characters</h3>
                        </div>
                        <p class="text-xs/5 text-zinc-600 dark:text-zinc-400">The frontmatter is designed to fit in ~100 tokens. Beyond that, it stops being a lightweight index and starts eating into the agent's context budget unnecessarily. Keep field values concise.</p>
                    </div>

                </div>

                <div class="rounded-lg overflow-hidden self-start agent:hidden">
                    <div class="bg-zinc-800 dark:bg-zinc-900 px-4 py-2 flex items-center justify-between">
                        <span class="text-xs text-zinc-400">Good frontmatter</span>
                        <span class="text-xs text-emerald-400">✓</span>
                    </div>
                    <div class="bg-zinc-950 p-5 font-mono text-xs leading-relaxed text-zinc-100 overflow-x-auto">
<span class="text-zinc-500">---</span><br>
<span class="text-purple-400">title:</span>       <span class="text-yellow-300">How to Set Up PostgreSQL Connection Pooling with PgBouncer</span><br>
<span class="text-purple-400">description:</span> <span class="text-yellow-300">Step-by-step guide to installing and</span><br>
<span class="text-yellow-300">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; configuring PgBouncer for transaction-mode</span><br>
<span class="text-yellow-300">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; pooling on Ubuntu. Covers pool_mode,</span><br>
<span class="text-yellow-300">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; max_client_conn, and auth_type settings.</span><br>
<span class="text-purple-400">date:</span>        <span class="text-yellow-300">2024-11-02</span><br>
<span class="text-purple-400">author:</span>      <span class="text-yellow-300">Maria López</span><br>
<span class="text-purple-400">license:</span>     <span class="text-yellow-300">CC-BY-4.0</span><br>
<span class="text-zinc-500">---</span>
                    </div>
                    <div class="bg-zinc-800 dark:bg-zinc-900 px-4 py-2 flex items-center justify-between mt-4">
                        <span class="text-xs text-zinc-400">Avoid</span>
                        <span class="text-xs text-red-400">✗</span>
                    </div>
                    <div class="bg-zinc-950 p-5 font-mono text-xs leading-relaxed text-zinc-100 overflow-x-auto">
<span class="text-zinc-500">---</span><br>
<span class="text-purple-400">title:</span>       <span class="text-red-400">PgBouncer</span><br>
<span class="text-purple-400">description:</span> <span class="text-red-400">A post about database connection pooling.</span><br>
<span class="text-zinc-500">---</span>
                    </div>
                </div>

            </div>
        </x-container>
    </div>


    {{-- Markdown body best practices --}}
    <div class="bg-zinc-200 dark:bg-zinc-700 pt-8">

        <x-section>
            <x-slot name="eyebrow"><x-eyebrow>Markdown Body</x-eyebrow></x-slot>

            Structure over decoration.

            <x-slot name="subheadline">The Markdown body is what agents actually read and reason over. Well-structured content reduces hallucination, improves retrieval accuracy, and makes your content reliably useful.</x-slot>
        </x-section>

        <x-container class="pb-12 mt-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 agent:flex agent:flex-col">

                <div class="flex flex-col gap-4">

                    <div class="rounded-lg p-5 bg-white/50 dark:bg-white/10 ring-1 ring-zinc-300 dark:ring-zinc-500">
                        <h3 class="text-sm font-semibold text-zinc-950 dark:text-white mb-2">Open with a level-one heading</h3>
                        <p class="text-xs/5 text-zinc-600 dark:text-zinc-400">The first line of the body must be an <code class="bg-zinc-200 dark:bg-zinc-700 px-1 rounded"># H1</code> heading matching the document title. This anchors the document hierarchy and confirms to the agent it has the right resource.</p>
                    </div>

                    <div class="rounded-lg p-5 bg-white/50 dark:bg-white/10 ring-1 ring-zinc-300 dark:ring-zinc-500">
                        <h3 class="text-sm font-semibold text-zinc-950 dark:text-white mb-2">Use headings to create a scannable outline</h3>
                        <p class="text-xs/5 text-zinc-600 dark:text-zinc-400">Agents scan heading structure to understand document scope before reading in full. Use <code class="bg-zinc-200 dark:bg-zinc-700 px-1 rounded">##</code> for main sections and <code class="bg-zinc-200 dark:bg-zinc-700 px-1 rounded">###</code> for subsections. Don't skip levels — jump from <code class="bg-zinc-200 dark:bg-zinc-700 px-1 rounded">##</code> to <code class="bg-zinc-200 dark:bg-zinc-700 px-1 rounded">####</code> creates a broken hierarchy.</p>
                    </div>

                    <div class="rounded-lg p-5 bg-white/50 dark:bg-white/10 ring-1 ring-zinc-300 dark:ring-zinc-500">
                        <h3 class="text-sm font-semibold text-zinc-950 dark:text-white mb-2">Prefer prose over tables for prose-heavy content</h3>
                        <p class="text-xs/5 text-zinc-600 dark:text-zinc-400">Tables work well for reference data (fields, parameters, options). For explanatory content, plain paragraphs are lower noise and easier to embed. Don't convert prose into tables just to appear structured.</p>
                    </div>

                    <div class="rounded-lg p-5 bg-white/50 dark:bg-white/10 ring-1 ring-zinc-300 dark:ring-zinc-500">
                        <h3 class="text-sm font-semibold text-zinc-950 dark:text-white mb-2">Keep the document within a reasonable token budget</h3>
                        <p class="text-xs/5 text-zinc-600 dark:text-zinc-400">Aim for under 4,000 tokens (~16,000 characters) for most articles. Very long documents may exceed an agent's working context. If your content is long, use a <code class="bg-zinc-200 dark:bg-zinc-700 px-1 rounded">&lt;nav&gt;</code> block to link to related sub-pages rather than cramming everything in one file.</p>
                    </div>

                </div>

                <div class="flex flex-col gap-4">

                    <div class="rounded-lg p-5 bg-white/50 dark:bg-white/10 ring-1 ring-zinc-300 dark:ring-zinc-500">
                        <h3 class="text-sm font-semibold text-zinc-950 dark:text-white mb-2">Replace images with text descriptions</h3>
                        <p class="text-xs/5 text-zinc-600 dark:text-zinc-400">Binary image embeds are unusable in a text response. For decorative images, omit them. For informational images — charts, diagrams, screenshots — use a <code class="bg-zinc-200 dark:bg-zinc-700 px-1 rounded">&lt;figure&gt;</code> block with a description, plus a link to the original image URL.</p>
                    </div>

                    <div class="rounded-lg p-5 bg-white/50 dark:bg-white/10 ring-1 ring-zinc-300 dark:ring-zinc-500">
                        <h3 class="text-sm font-semibold text-zinc-950 dark:text-white mb-2">Use a &lt;nav&gt; block for document context</h3>
                        <p class="text-xs/5 text-zinc-600 dark:text-zinc-400">Place a <code class="bg-zinc-200 dark:bg-zinc-700 px-1 rounded">&lt;nav&gt;</code> block near the top or bottom of the document (not inside section headings) linking to previous/next pages, a table of contents, or related resources. This helps agents navigate multi-page content.</p>
                    </div>

                    <div class="rounded-lg p-5 bg-white/50 dark:bg-white/10 ring-1 ring-zinc-300 dark:ring-zinc-500">
                        <h3 class="text-sm font-semibold text-zinc-950 dark:text-white mb-2">Use code blocks for all code</h3>
                        <p class="text-xs/5 text-zinc-600 dark:text-zinc-400">Always fence code samples with triple backticks and a language identifier. <code class="bg-zinc-200 dark:bg-zinc-700 px-1 rounded">```python</code>, <code class="bg-zinc-200 dark:bg-zinc-700 px-1 rounded">```bash</code>, etc. Inline code for identifiers, commands, and values. Agents use this to correctly quote and attribute code.</p>
                    </div>

                    <div class="rounded-lg p-5 bg-white/50 dark:bg-white/10 ring-1 ring-zinc-300 dark:ring-zinc-500">
                        <h3 class="text-sm font-semibold text-zinc-950 dark:text-white mb-2">Avoid HTML except for content-md blocks</h3>
                        <p class="text-xs/5 text-zinc-600 dark:text-zinc-400">Inline HTML adds noise that parsers may handle inconsistently. Reserve HTML tags for the defined content-md blocks: <code class="bg-zinc-200 dark:bg-zinc-700 px-1 rounded">&lt;nav&gt;</code>, <code class="bg-zinc-200 dark:bg-zinc-700 px-1 rounded">&lt;figure&gt;</code>, <code class="bg-zinc-200 dark:bg-zinc-700 px-1 rounded">&lt;abstract&gt;</code>. Use standard Markdown for everything else.</p>
                    </div>

                </div>

            </div>
        </x-container>
    </div>


    {{-- Existing content migration --}}
    <div class="pt-8">

        <x-section>
            <x-slot name="eyebrow"><x-eyebrow>Existing Content</x-eyebrow></x-slot>

            Converting what you already have.

            <x-slot name="subheadline">You don't need to rewrite your content. content-md is designed to layer on top of existing CMS content with minimal changes.</x-slot>
        </x-section>

        <x-container class="mb-16 mt-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 agent:flex agent:flex-col">

                <div class="flex flex-col gap-3">
                    <div class="counter-industrial text-zinc-300 dark:text-zinc-600 agent:hidden" aria-hidden="true">01</div>
                    <div class="spec-label text-zinc-400 dark:text-zinc-500 -mt-1 mb-0.5 agent:hidden">Source</div>
                    <h3 class="text-sm font-semibold text-zinc-950 dark:text-white">Start with your title and excerpt</h3>
                    <p class="text-sm/7 text-zinc-700 dark:text-zinc-400">Most CMS platforms already store a title, excerpt or meta description, publication date, and author. These map directly to the required and encouraged frontmatter fields with no additional writing.</p>
                </div>

                <div class="flex flex-col gap-3">
                    <div class="counter-industrial text-zinc-300 dark:text-zinc-600 agent:hidden" aria-hidden="true">02</div>
                    <div class="spec-label text-zinc-400 dark:text-zinc-500 -mt-1 mb-0.5 agent:hidden">Convert</div>
                    <h3 class="text-sm font-semibold text-zinc-950 dark:text-white">Strip decorative HTML</h3>
                    <p class="text-sm/7 text-zinc-700 dark:text-zinc-400">Remove navigation bars, sidebars, footers, cookie banners, and ads from the body. Keep the article content. Replace <code class="text-xs bg-zinc-100 dark:bg-zinc-800 px-1.5 py-0.5 rounded">&lt;img&gt;</code> tags with <code class="text-xs bg-zinc-100 dark:bg-zinc-800 px-1.5 py-0.5 rounded">&lt;figure&gt;</code> blocks containing the alt text.</p>
                </div>

                <div class="flex flex-col gap-3">
                    <div class="counter-industrial text-zinc-300 dark:text-zinc-600 agent:hidden" aria-hidden="true">03</div>
                    <div class="spec-label text-zinc-400 dark:text-zinc-500 -mt-1 mb-0.5 agent:hidden">Serve</div>
                    <h3 class="text-sm font-semibold text-zinc-950 dark:text-white">Use a plugin or generate on publish</h3>
                    <p class="text-sm/7 text-zinc-700 dark:text-zinc-400">The WordPress and Caddy plugins handle conversion automatically. For custom stacks, generate the content-md file at publish time and serve it via content negotiation — no live conversion needed.</p>
                </div>

            </div>
        </x-container>
    </div>

    <div class="h-16 agent:hidden"></div>

@endsection

@push('markdown')

# content-md for Content Producers

Best practices and guidelines for writing content-md documents — how to structure frontmatter, markdown body, and custom blocks for optimal AI agent consumption.

## Frontmatter

The frontmatter block is the first thing an AI agent reads. It decides whether to fetch the full document based on this alone.

### Write a precise description

The `description` field is the most important signal for relevance. Aim for ~200 characters. Be specific — avoid generic phrases like "a blog post about X". State what the reader will learn or get from the content.

### Always include a date

AI agents use `date` to assess freshness. Use ISO 8601: `2024-03-15`.

### Declare a license

Use an SPDX identifier like `CC-BY-4.0` or `CC-BY-NC-4.0`. Omitting it leaves agents without guidance on permissible use.

### Keep frontmatter within ~540 characters

The frontmatter is designed to fit in ~100 tokens. Beyond that, it stops being a lightweight index and starts eating into the agent's context budget.

## Markdown Body

### Open with a level-one heading

The first line of the body must be an `# H1` heading matching the document title.

### Use headings to create a scannable outline

Agents scan heading structure to understand document scope. Use `##` for main sections and `###` for subsections. Don't skip heading levels.

### Keep the document within a reasonable token budget

Aim for under 4,000 tokens (~16,000 characters) for most articles. If your content is long, use a `<nav>` block to link to related sub-pages.

### Replace images with text descriptions

For informational images, use a `<figure>` block with a description and a link to the original image URL.

### Use a `<nav>` block for document context

Place a `<nav>` block near the top or bottom linking to previous/next pages, a table of contents, or related resources.

### Use code blocks for all code

Always fence code samples with triple backticks and a language identifier.

### Avoid HTML except for content-md blocks

Reserve HTML tags for `<nav>`, `<figure>`, `<abstract>`. Use standard Markdown for everything else.

## Converting Existing Content

### 01 — Start with your title and excerpt

Most CMS platforms already store a title, excerpt, publication date, and author. These map directly to frontmatter fields.

### 02 — Strip decorative HTML

Remove navigation, sidebars, footers, and ads. Replace `<img>` tags with `<figure>` blocks containing the alt text.

### 03 — Use a plugin or generate on publish

The WordPress and Caddy plugins handle conversion automatically. For custom stacks, generate the content-md file at publish time.

@endpush
