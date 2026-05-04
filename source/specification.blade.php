---
title: content-md Specification
description: Content-md specification with frontmatter fields and custom block syntax.
card:
  template: '_og.page'
  path: /assets/og/reference.png
---

@extends('_layouts.main')

@section('body')

<x-page-hero>
    The format, in full.

    <x-slot name="label"><x-eyebrow>Reference</x-eyebrow></x-slot>

    <x-slot name="description">
        Complete reference for the content-md document format — frontmatter fields, markdown body rules, and custom block syntax.
    </x-slot>
</x-page-hero>


{{-- Format section --}}
<div class="pt-8">
    <x-section>
        <x-slot name="eyebrow"><x-eyebrow>The Format</x-eyebrow></x-slot>

        YAML frontmatter, Markdown body.

        <x-slot name="subheadline">content-md starts with a YAML frontmatter block providing context (~100 tokens), followed by a Markdown document. Familiar, concise, and designed to stay within AI context window limits.</x-slot>
    </x-section>

    <x-format-example />
</div>


{{-- Frontmatter reference table --}}
<x-section-panel>
    <x-section>
        <x-slot name="eyebrow"><x-eyebrow>Frontmatter Fields</x-eyebrow></x-slot>
        The fields.
    </x-section>

    <x-container class="pb-12 mt-4">
        <div class="overflow-x-auto agent:hidden">
            <table class="w-full text-sm border-collapse">
                <thead>
                    <tr class="border-b border-zinc-300 dark:border-zinc-600">
                        <th class="text-left py-3 pr-6 font-semibold text-zinc-950 dark:text-white">Field</th>
                        <th class="text-left py-3 pr-6 font-semibold text-zinc-950 dark:text-white">Required</th>
                        <th class="text-left py-3 font-semibold text-zinc-950 dark:text-white">Description</th>
                    </tr>
                </thead>
                <tbody class="text-zinc-700 dark:text-zinc-300">
                    <tr class="border-b border-zinc-300/60 dark:border-zinc-600/60">
                        <td class="py-3 pr-6"><code class="text-xs bg-zinc-100 dark:bg-zinc-800 px-1.5 py-0.5 rounded">title</code></td>
                        <td class="py-3 pr-6"><span class="inline-block text-xs font-medium px-2 py-0.5 rounded-full bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200">Required</span></td>
                        <td class="py-3">Non-empty. The title of the resource — article, page, document.</td>
                    </tr>
                    <tr class="border-b border-zinc-300/60 dark:border-zinc-600/60">
                        <td class="py-3 pr-6"><code class="text-xs bg-zinc-100 dark:bg-zinc-800 px-1.5 py-0.5 rounded">description</code></td>
                        <td class="py-3 pr-6"><span class="inline-block text-xs font-medium px-2 py-0.5 rounded-full bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200">Required</span></td>
                        <td class="py-3">Non-empty. Best ~200 characters. A short and accurate summary of the content.</td>
                    </tr>
                    <tr class="border-b border-zinc-300/60 dark:border-zinc-600/60">
                        <td class="py-3 pr-6"><code class="text-xs bg-zinc-100 dark:bg-zinc-800 px-1.5 py-0.5 rounded">date</code></td>
                        <td class="py-3 pr-6"><span class="inline-block text-xs font-medium px-2 py-0.5 rounded-full bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200">Encouraged</span></td>
                        <td class="py-3">Date of creation or publication, whichever is more recent. ISO 8601 format.</td>
                    </tr>
                    <tr class="border-b border-zinc-300/60 dark:border-zinc-600/60">
                        <td class="py-3 pr-6"><code class="text-xs bg-zinc-100 dark:bg-zinc-800 px-1.5 py-0.5 rounded">license</code></td>
                        <td class="py-3 pr-6"><span class="inline-block text-xs font-medium px-2 py-0.5 rounded-full bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200">Encouraged</span></td>
                        <td class="py-3">License name or SPDX Identifier of the content.</td>
                    </tr>
                    <tr>
                        <td class="py-3 pr-6"><code class="text-xs bg-zinc-100 dark:bg-zinc-800 px-1.5 py-0.5 rounded">author</code></td>
                        <td class="py-3 pr-6"><span class="inline-block text-xs font-medium px-2 py-0.5 rounded-full bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200">Encouraged</span></td>
                        <td class="py-3">Author of the content. Host owner is assumed as author if not provided.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="hidden agent:block prose prose-zinc dark:prose-invert max-w-none">
            <table>
                <thead>
                    <tr>
                        <th>Field</th>
                        <th>Required</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><code>title</code></td>
                        <td>Required</td>
                        <td>Non-empty. The title of the resource — article, page, document.</td>
                    </tr>
                    <tr>
                        <td><code>description</code></td>
                        <td>Required</td>
                        <td>Non-empty. Best ~200 characters. A short and accurate summary of the content.</td>
                    </tr>
                    <tr>
                        <td><code>date</code></td>
                        <td>Encouraged</td>
                        <td>Date of creation or publication, whichever is more recent. ISO 8601 format.</td>
                    </tr>
                    <tr>
                        <td><code>license</code></td>
                        <td>Encouraged</td>
                        <td>License name or SPDX Identifier of the content.</td>
                    </tr>
                    <tr>
                        <td><code>author</code></td>
                        <td>Encouraged</td>
                        <td>Author of the content. Host owner is assumed as author if not provided.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <p class="mt-4 text-xs text-zinc-600 dark:text-zinc-400">
            Fields map to Dublin Core, schema.org CreativeWork, and standard HTML meta equivalents.
        </p>
    </x-container>
</x-section-panel>


{{-- Custom blocks section --}}
<div class="pt-8">
    <x-section>
        <x-slot name="eyebrow"><x-eyebrow>Custom Blocks</x-eyebrow></x-slot>

        Navigation, figures, and more.

        <x-slot name="subheadline">content-md proposes custom blocks to include navigation affordances, image descriptions, formal abstracts, and advertising. AI agents may choose to skip advertisement blocks.</x-slot>
    </x-section>

    <x-container class="mb-16 mt-4">
        <x-card-grid>
            <div class="rounded-lg p-5 bg-zinc-950/2.5 dark:bg-white/5 ring-1 ring-zinc-200 dark:ring-zinc-700">
                <div class="flex items-center gap-2 mb-2">
                    <code class="text-xs font-medium px-2 py-0.5 rounded bg-zinc-200 dark:bg-zinc-700 text-zinc-700 dark:text-zinc-300">&lt;nav&gt;</code>
                    <span class="text-sm font-semibold text-zinc-950 dark:text-white">Navigation</span>
                </div>
                <p class="text-xs/5 text-zinc-600 dark:text-zinc-400 mb-3">Communicate website navigation or linked resources relevant to the content.</p>
                <div class="rounded bg-zinc-950 p-3 font-mono text-xs leading-relaxed text-zinc-100 overflow-x-auto agent:hidden">
<span class="text-emerald-400">&lt;nav&gt;</span><br>
- Next: <span class="text-blue-400">[Next article](https://example.com/next)</span><br>
- Related: <span class="text-blue-400">[Topic guide](https://example.com/topic)</span><br>
<span class="text-emerald-400">&lt;/nav&gt;</span>
                </div>
                <div class="hidden agent:block text-xs text-zinc-600 dark:text-zinc-400">
                    <pre><code>&lt;nav&gt;
- Next: [Next article](https://example.com/next)
- Related: [Topic guide](https://example.com/topic)
&lt;/nav&gt;</code></pre>
                </div>
            </div>

            <div class="rounded-lg p-5 bg-zinc-950/2.5 dark:bg-white/5 ring-1 ring-zinc-200 dark:ring-zinc-700">
                <div class="flex items-center gap-2 mb-2">
                    <code class="text-xs font-medium px-2 py-0.5 rounded bg-zinc-200 dark:bg-zinc-700 text-zinc-700 dark:text-zinc-300">&lt;figure&gt;</code>
                    <span class="text-sm font-semibold text-zinc-950 dark:text-white">Image (alternate)</span>
                </div>
                <p class="text-xs/5 text-zinc-600 dark:text-zinc-400 mb-3">Signal an image is present using its alternate text or caption instead of a binary embed.</p>
                <div class="rounded bg-zinc-950 p-3 font-mono text-xs leading-relaxed text-zinc-100 overflow-x-auto agent:hidden">
<span class="text-emerald-400">&lt;figure&gt;</span><br>
Alternate text describing the image<br>
and/or its caption for AI context.<br>
<span class="text-emerald-400">&lt;/figure&gt;</span>
                </div>
                <div class="hidden agent:block text-xs text-zinc-600 dark:text-zinc-400">
                    <pre><code>&lt;figure&gt;
Alternate text describing the image and/or caption
&lt;/figure&gt;</code></pre>
                </div>
            </div>

            <div class="rounded-lg p-5 bg-zinc-950/2.5 dark:bg-white/5 ring-1 ring-zinc-200 dark:ring-zinc-700">
                <div class="flex items-center gap-2 mb-2">
                    <code class="text-xs font-medium px-2 py-0.5 rounded bg-zinc-200 dark:bg-zinc-700 text-zinc-700 dark:text-zinc-300">&lt;abstract&gt;</code>
                    <span class="text-sm font-semibold text-zinc-950 dark:text-white">Abstract</span>
                </div>
                <p class="text-xs/5 text-zinc-600 dark:text-zinc-400 mb-3">For scientific articles with formal abstracts. The <code class="bg-zinc-200 dark:bg-zinc-700 px-1 rounded">lang</code> attribute is optional.</p>
                <div class="rounded bg-zinc-950 p-3 font-mono text-xs leading-relaxed text-zinc-100 overflow-x-auto agent:hidden">
<span class="text-emerald-400">&lt;abstract lang="en"&gt;</span><br>
We present a novel approach to<br>
serving web content to AI agents...<br>
<span class="text-emerald-400">&lt;/abstract&gt;</span>
                </div>
                <div class="hidden agent:block text-xs text-zinc-600 dark:text-zinc-400">
                    <pre><code>&lt;abstract lang="en"&gt;
We present a novel approach to serving web content to AI agents...
&lt;/abstract&gt;</code></pre>
                </div>
            </div>

            <div class="rounded-lg p-5 bg-zinc-950/2.5 dark:bg-white/5 ring-1 ring-zinc-200 dark:ring-zinc-700">
                <div class="flex items-center gap-2 mb-2">
                    <code class="text-xs font-medium px-2 py-0.5 rounded bg-zinc-200 dark:bg-zinc-700 text-zinc-700 dark:text-zinc-300">[!AD]</code>
                    <span class="text-sm font-semibold text-zinc-950 dark:text-white">Advertisement</span>
                </div>
                <p class="text-xs/5 text-zinc-600 dark:text-zinc-400 mb-3">Include paid advertisements alongside content — AI agents may choose to ignore them.</p>
                <div class="rounded bg-zinc-950 p-3 font-mono text-xs leading-relaxed text-zinc-100 overflow-x-auto agent:hidden">
<span class="text-yellow-300">&gt; [!AD]</span><br>
<span class="text-yellow-300">&gt; Buy one, get two — promo active</span><br>
<span class="text-yellow-300">&gt; for the next 30 days.</span>
                </div>
                <div class="hidden agent:block text-xs text-zinc-600 dark:text-zinc-400">
                    <pre><code>&gt; [!AD]
&gt; Buy one, get two — promo active
&gt; for the next 30 days.</code></pre>
                </div>
            </div>
        </x-card-grid>
    </x-container>
</div>

<div class="h-16 agent:hidden"></div>

@endsection

@push('markdown')

# content-md Specification

Complete reference for the content-md document format — frontmatter fields, markdown body rules, and custom block syntax.

## The Format

content-md starts with a YAML frontmatter block providing context (~100 tokens), followed by a Markdown document.

### Frontmatter

Serves as an introductory summary — ~100 tokens, ~540 characters. AI agents read this first to decide if the full document is relevant before fetching it. Functions as a lightweight preflighted index.

### Markdown body

CommonMark or GitHub-flavored Markdown. Must open with a first-level heading. Prefer text over images — link images and include alternate text. Preserve document hierarchy starting from level two.

## Frontmatter Fields

| Field | Required | Description |
| --- | --- | --- |
| title | Required | Non-empty. The title of the resource — article, page, document. |
| description | Required | Non-empty. Best ~200 characters. A short and accurate summary of the content. |
| date | Encouraged | Date of creation or publication, whichever is more recent. ISO 8601 format. |
| license | Encouraged | License name or SPDX Identifier of the content. |
| author | Encouraged | Author of the content. Host owner is assumed as author if not provided. |

Fields map to Dublin Core, schema.org CreativeWork, and standard HTML meta equivalents.

## Custom Blocks

content-md proposes custom blocks to include navigation affordances, image descriptions, formal abstracts, and advertising. AI agents may choose to skip advertisement blocks.

### Navigation

Communicate website navigation or linked resources relevant to the content.

```
<nav>
- Next: [Next article](https://example.com/next)
- Related: [Topic guide](https://example.com/topic)
</nav>
```

### Image (alternate)

Signal an image is present using its alternate text or caption instead of a binary embed.

```
<figure>
Alternate text describing the image and/or its caption for AI context.
</figure>
```

### Abstract

For scientific articles with formal abstracts. The `lang` attribute is optional.

```
<abstract lang="en">
We present a novel approach to serving web content to AI agents...
</abstract>
```

### Advertisement

Include paid advertisements alongside content — AI agents may choose to ignore them.

```
> [!AD]
> Buy one, get two — promo active
> for the next 30 days.
```

@endpush
