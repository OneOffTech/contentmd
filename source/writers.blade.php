---
title: content-md for writers
description: Best practices and guidelines for writing content-md documents — how to structure frontmatter, markdown body, and custom blocks for optimal AI agent consumption.
card:
  template: '_og.page'
  path: /assets/og/writers.png
---

@extends('_layouts.main')

@section('body')

<x-page-hero>
    Write content AI agents can actually use.

    <x-slot name="label"><x-eyebrow>For Content Producers</x-eyebrow></x-slot>

    <x-slot name="description">
        Best practices for writing content-md documents — from describing your content clearly in frontmatter to structuring a Markdown body that works well within AI context windows.
    </x-slot>
</x-page-hero>


{{-- Frontmatter best practices --}}
<div class="pt-8">
    <x-section>
        <x-slot name="eyebrow"><x-eyebrow>Frontmatter</x-eyebrow></x-slot>

        The first ~100 tokens.

        <x-slot name="subheadline">The frontmatter block is the first thing an AI agent reads. It decides whether to fetch the full document based on this alone.</x-slot>
    </x-section>

    <x-container class="mt-4 mb-12">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 agent:flex agent:flex-col">

            <div class="flex flex-col gap-6">
                <x-card badge="required" title="Write a precise description" size="xs">
                    Get the <code class="bg-zinc-200 dark:bg-zinc-700 px-1 rounded">description</code> right. Aim for ~200 characters. Be specific: avoid phrases like "a blog post about X" and state what the reader will actually get from the content.
                </x-card>

                <x-card badge="encouraged" title="Always include a date" size="xs">
                    AI agents use <code class="bg-zinc-200 dark:bg-zinc-700 px-1 rounded">date</code> to assess freshness. A guide from 2019 may have outdated advice; an agent that knows this can deprioritise it accordingly. Use ISO 8601: <code class="bg-zinc-200 dark:bg-zinc-700 px-1 rounded">2024-03-15</code>.
                </x-card>

                <x-card badge="encouraged" title="Declare a license" size="xs">
                    The <code class="bg-zinc-200 dark:bg-zinc-700 px-1 rounded">license</code> field tells agents how your content may be used. Use an <a href="https://spdx.org/licenses/" target="_blank" rel="noopener noreferrer" class="underline hover:text-zinc-950 dark:hover:text-white">SPDX identifier</a> like <code class="bg-zinc-200 dark:bg-zinc-700 px-1 rounded">CC-BY-4.0</code> or <code class="bg-zinc-200 dark:bg-zinc-700 px-1 rounded">CC-BY-NC-4.0</code>. Omitting it leaves agents without guidance on permissible use.
                </x-card>

                <x-card badge="tip" title="Keep frontmatter within ~540 characters" size="xs">
                    The frontmatter fits in ~100 tokens. Beyond that it stops being a lightweight index and starts eating into the agent's context budget. Keep field values concise.
                </x-card>
            </div>

            <div class="rounded-lg overflow-hidden self-start agent:hidden">
                <div class="bg-zinc-800 dark:bg-zinc-900 px-4 py-2 flex items-center justify-between">
                    <span class="text-xs text-zinc-400">Good frontmatter</span>
                    <span class="text-xs text-emerald-400">✓</span>
                </div>
                <div class="bg-zinc-950 dark:bg-zinc-900 p-5 font-mono text-xs leading-relaxed text-zinc-100 overflow-x-auto">
<span class="text-zinc-500">---</span><br>
<span class="text-purple-400">title:</span>       <span class="text-yellow-300">How to Set Up PostgreSQL Connection Pooling with PgBouncer</span><br>
<span class="text-purple-400">description:</span> <span class="text-zinc-400">&gt;-</span><br>
<span class="text-yellow-300">&nbsp;&nbsp;Step-by-step guide to installing and configuring PgBouncer</span><br>
<span class="text-yellow-300">&nbsp;&nbsp;for transaction-mode pooling on Ubuntu. Covers pool_mode,</span><br>
<span class="text-yellow-300">&nbsp;&nbsp;max_client_conn, and auth_type settings.</span><br>
<span class="text-purple-400">date:</span>        <span class="text-yellow-300">2024-11-02</span><br>
<span class="text-purple-400">author:</span>      <span class="text-yellow-300">Maria López</span><br>
<span class="text-purple-400">license:</span>     <span class="text-yellow-300">CC-BY-4.0</span><br>
<span class="text-zinc-500">---</span>
                </div>
                <div class="bg-zinc-800 dark:bg-zinc-900 px-4 py-2 flex items-center justify-between mt-4">
                    <span class="text-xs text-zinc-400">Avoid</span>
                    <span class="text-xs text-red-400">✗</span>
                </div>
                <div class="bg-zinc-950 dark:bg-zinc-900 p-5 font-mono text-xs leading-relaxed text-zinc-100 overflow-x-auto">
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
<x-section-panel>
    <x-section>
        <x-slot name="eyebrow"><x-eyebrow>Markdown Body</x-eyebrow></x-slot>

        Structure over decoration.

        <x-slot name="subheadline">The markdown body is what agents read and reason over. Poor structure leads to worse answers.</x-slot>
    </x-section>

    <x-container class="pb-12 mt-4">
        <x-card-grid cols="2-lg">

            <div class="flex flex-col gap-4">
                <x-card variant="panel" title="Open with a level-one heading" size="xs">
                    The first line of the body must be an <code class="bg-zinc-200 dark:bg-zinc-700 px-1 rounded"># H1</code> heading matching the document title.
                </x-card>

                <x-card variant="panel" title="Use headings to create a scannable outline" size="xs">
                    Agents scan heading structure before reading in full. Use <code class="bg-zinc-200 dark:bg-zinc-700 px-1 rounded">##</code> for main sections and <code class="bg-zinc-200 dark:bg-zinc-700 px-1 rounded">###</code> for subsections. Don't skip levels: jumping from <code class="bg-zinc-200 dark:bg-zinc-700 px-1 rounded">##</code> to <code class="bg-zinc-200 dark:bg-zinc-700 px-1 rounded">####</code> creates a broken hierarchy.
                </x-card>

                <x-card variant="panel" title="Prefer prose over tables for heavy content" size="xs">
                    Tables work well for reference data (fields, parameters, options). For explanatory content, plain paragraphs are easier to parse. Don't convert prose into tables just to look structured.
                </x-card>

                <x-card variant="panel" title="Keep the document within a reasonable token budget" size="xs">
                    Aim for under 4,000 tokens (~16,000 characters). Very long documents may exceed an agent's working context. If your content is long, link to sub-pages from a <code class="bg-zinc-200 dark:bg-zinc-700 px-1 rounded">&lt;nav&gt;</code> block rather than cramming everything into one file.
                </x-card>
            </div>

            <div class="flex flex-col gap-4">
                <x-card variant="panel" title="Replace images with text descriptions" size="xs">
                    Binary image embeds don't work in a text response. For decorative images, omit them. For informational images (charts, diagrams, screenshots) use a <code class="bg-zinc-200 dark:bg-zinc-700 px-1 rounded">&lt;figure&gt;</code> block with a description, plus a link to the original.
                </x-card>

                <x-card variant="panel" title="Use a nav block for document context" size="xs">
                    Place a <code class="bg-zinc-200 dark:bg-zinc-700 px-1 rounded">&lt;nav&gt;</code> block near the top or bottom of the document (not inside section headings) linking to previous/next pages, a table of contents, or related resources.
                </x-card>

                <x-card variant="panel" title="Use code blocks for all code" size="xs">
                    Fence code samples with triple backticks and a language identifier: <code class="bg-zinc-200 dark:bg-zinc-700 px-1 rounded">```python</code>, <code class="bg-zinc-200 dark:bg-zinc-700 px-1 rounded">```bash</code>, etc. Use inline code for identifiers, commands, and values.
                </x-card>

                <x-card variant="panel" title="Avoid HTML" size="xs">
                    Inline HTML adds noise. Stick to plain markdown and the defined content-md blocks: <code class="bg-zinc-200 dark:bg-zinc-700 px-1 rounded">&lt;nav&gt;</code>, <code class="bg-zinc-200 dark:bg-zinc-700 px-1 rounded">&lt;figure&gt;</code>, <code class="bg-zinc-200 dark:bg-zinc-700 px-1 rounded">&lt;abstract&gt;</code>.
                </x-card>
            </div>

        </x-card-grid>
    </x-container>
</x-section-panel>


{{-- Existing content migration --}}
<div class="pt-8">
    <x-section>
        <x-slot name="eyebrow"><x-eyebrow>Existing Content</x-eyebrow></x-slot>

        Converting what you already have.

        <x-slot name="subheadline">You don't need to rewrite your content. content-md layers on top of existing CMS content with minimal changes.</x-slot>
    </x-section>

    <x-container class="mb-16 mt-4">
        <x-card-grid cols="3">
            <x-numbered-step number="01" label="Source" title="Start with your title and excerpt">
                Most CMS platforms already store a title, excerpt or meta description, publication date, and author. These map directly to frontmatter fields with no extra writing.
            </x-numbered-step>

            <x-numbered-step number="02" label="Convert" title="Strip decorative HTML">
                Remove navigation bars, sidebars, footers, cookie banners, and ads. Keep the article content. Replace <code class="text-xs bg-zinc-100 dark:bg-zinc-800 px-1.5 py-0.5 rounded">&lt;img&gt;</code> tags with <code class="text-xs bg-zinc-100 dark:bg-zinc-800 px-1.5 py-0.5 rounded">&lt;figure&gt;</code> blocks containing the alternate text.
            </x-numbered-step>

            <x-numbered-step number="03" label="Serve" title="Use a plugin or generate on publish">
                Generate the content-md file at publish time and serve it via content negotiation (e.g. Caddy module) or integrate it into your CMS (e.g. Wordpress).
            </x-numbered-step>
        </x-card-grid>
    </x-container>
</div>

<div class="h-16 agent:hidden"></div>

@endsection

@push('markdown')

# content-md for Content Producers

How to write content-md documents: structuring frontmatter, markdown body, and custom blocks.

## Frontmatter

The frontmatter block is the first thing an AI agent reads. It decides whether to fetch the full document based on this alone.

### Write a precise description

Get the `description` right. Aim for ~200 characters. Be specific: avoid phrases like "a blog post about X" and state what the reader will actually get from the content.

### Always include a date

AI agents use `date` to assess freshness. A guide from 2019 may have outdated advice; an agent that knows this can deprioritise it accordingly. Use ISO 8601: `2024-03-15`.

### Declare a license

The `license` field tells agents how your content may be used. Use an SPDX identifier like `CC-BY-4.0` or `CC-BY-NC-4.0`. Omitting it leaves agents without guidance on permissible use.

### Keep frontmatter within ~540 characters

The frontmatter fits in ~100 tokens. Beyond that it stops being a lightweight index and starts eating into the agent's context budget. Keep field values concise.

## Markdown Body

The markdown body is what agents read and reason over. Poor structure leads to worse answers.

### Open with a level-one heading

The first line of the body must be an `# H1` heading matching the document title.

### Use headings to create a scannable outline

Agents scan heading structure before reading in full. Use `##` for main sections and `###` for subsections. Don't skip levels: jumping from `##` to `####` creates a broken hierarchy.

### Prefer prose over tables for heavy content

Tables work well for reference data (fields, parameters, options). For explanatory content, plain paragraphs are easier to parse. Don't convert prose into tables just to look structured.

### Keep the document within a reasonable token budget

Aim for under 4,000 tokens (~16,000 characters). Very long documents may exceed an agent's working context. If your content is long, link to sub-pages from a `<nav>` block rather than cramming everything into one file.

### Replace images with text descriptions

Binary image embeds don't work in a text response. For decorative images, omit them. For informational images (charts, diagrams, screenshots) use a `<figure>` block with a description, plus a link to the original.

### Use a `<nav>` block for document context

Place a `<nav>` block near the top or bottom of the document (not inside section headings) linking to previous/next pages, a table of contents, or related resources.

### Use code blocks for all code

Fence code samples with triple backticks and a language identifier: ` ```python `, ` ```bash `, etc. Use inline code for identifiers, commands, and values.

### Avoid HTML

Inline HTML adds noise. Stick to plain markdown and the defined content-md blocks: `<nav>`, `<figure>`, `<abstract>`.

## Converting Existing Content

You don't need to rewrite your content. content-md layers on top of existing CMS content with minimal changes.

### 01 — Start with your title and excerpt

Most CMS platforms already store a title, excerpt or meta description, publication date, and author. These map directly to frontmatter fields with no extra writing.

### 02 — Strip decorative HTML

Remove navigation bars, sidebars, footers, cookie banners, and ads. Keep the article content. Replace `<img>` tags with `<figure>` blocks containing the alternate text.

### 03 — Use a plugin or generate on publish

Generate the content-md file at publish time and serve it via content negotiation (e.g. Caddy module) or integrate it into your CMS (e.g. Wordpress).

@endpush
