@extends('_layouts.main')

@section('body')

    @component('_components.cover')
        The web, spoken fluently to AI agents.

        @slot('eyebrow')
            <x-eyebrow>Open Specification · Draft</x-eyebrow>
        @endslot

        @slot('subheadline')
            content-md is an open specification for optimized content exchange. Serve high-fidelity textual representation to AI agents through standard HTTP content negotiation — no scraping required.
        @endslot
    @endcomponent

    <x-feature-grid class="mt-16 mb-8">

        <x-feature-item title="Builds on your existing CMS content" level="h2">
            content-md works with the content you already have. No migration required — generate the optimized representation from your existing articles, pages, and documents.
        </x-feature-item>

        <x-feature-item title="Standard HTTP content negotiation" level="h2">
            Uses the same <code class="text-xs bg-mauve-100 dark:bg-mauve-800 px-1.5 py-0.5 rounded">Accept</code> header mechanism browsers and servers have used for decades. AI agents that know the protocol get the optimized version. Others get HTML as usual.
        </x-feature-item>

        <x-feature-item title="YAML frontmatter + Markdown body" level="h2">
            A familiar, concise format designed to stay within AI context window limits. ~100 tokens of structured frontmatter, followed by a clean Markdown document that starts with a first-level heading.
        </x-feature-item>

    </x-feature-grid>


    {{-- Philosophy strip --}}
    <div class="bg-mauve-200 dark:bg-mauve-700 py-8">
        <x-container>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-6 sm:gap-12 text-sm font-medium text-mauve-700 dark:text-mauve-300 agent:flex-col agent:items-start">
                <div>
                    <strong class="text-mauve-950 dark:text-white">Web pages</strong> with interactive content
                    <span class="mx-2 text-mauve-400 dark:text-mauve-500">→</span>
                    <em>for humans</em>
                </div>
                <div class="hidden sm:block w-px h-6 bg-mauve-300 dark:bg-mauve-500 agent:hidden"></div>
                <div>
                    <strong class="text-mauve-950 dark:text-white">content-md</strong> with high-fidelity text
                    <span class="mx-2 text-mauve-400 dark:text-mauve-500">→</span>
                    <em>for AI agents</em>
                </div>
            </div>
        </x-container>
    </div>


    {{-- Format section --}}
    <div class="pt-8">

        @component('_components.section')
            YAML frontmatter, Markdown body.

            @slot('eyebrow')
                <x-eyebrow>The Format</x-eyebrow>
            @endslot

            @slot('subheadline')
                content-md starts with a YAML frontmatter block providing context (~100 tokens), followed by a Markdown document. Familiar, concise, and designed to stay within AI context window limits.
            @endslot
        @endcomponent

        <x-container class="mt-4 mb-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start agent:flex agent:flex-col">

                <div class="rounded-lg overflow-hidden agent:hidden">
                    <div class="bg-mauve-800 dark:bg-mauve-900 px-4 py-2 flex items-center gap-2">
                        <span class="text-xs text-mauve-400">article.md</span>
                    </div>
                    <div class="bg-mauve-950 p-5 font-mono text-xs leading-relaxed text-mauve-100 overflow-x-auto">
<span class="text-mauve-500">---</span><br>
<span class="text-purple-400">description:</span> <span class="text-yellow-300">AI agents must be considered as first-class visitors,</span><br>
<span class="text-yellow-300">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; let's give them a tailored experience.</span><br>
<span class="text-purple-400">title:</span>       <span class="text-yellow-300">Introducing Content-md</span><br>
<span class="text-purple-400">date:</span>        <span class="text-yellow-300">2024-01-15</span><br>
<span class="text-purple-400">author:</span>      <span class="text-yellow-300">Jane Smith</span><br>
<span class="text-purple-400">license:</span>     <span class="text-yellow-300">CC-BY-4.0</span><br>
<span class="text-mauve-500">---</span><br>
<br>
<span class="text-emerald-400"># Introducing Content-md</span><br>
<br>
<span class="text-mauve-300">AI Agents are increasingly browsing the web on behalf</span><br>
<span class="text-mauve-300">of humans. The web was built with humans in mind that</span><br>
<span class="text-mauve-300">demand quality and pleasant interaction. Agents go</span><br>
<span class="text-mauve-300">straight to the point and prefer a more structured approach.</span><br>
<br>
<span class="text-blue-400">## The Problem</span><br>
<br>
<span class="text-mauve-300">Converting complex HTML pages with navigation, ads,</span><br>
<span class="text-mauve-300">and JavaScript into LLM-friendly plain text is both</span><br>
<span class="text-mauve-300">difficult and imprecise.</span>
                    </div>
                </div>

                <div class="hidden agent:block prose prose-mauve dark:prose-invert max-w-none">
                    <pre><code>---
description: AI agents must be considered as first-class visitors,
             let's give them a tailored experience.
title:       Introducing Content-md
date:        2024-01-15
author:      Jane Smith
license:     CC-BY-4.0
---

# Introducing Content-md

AI Agents are increasingly browsing the web on behalf of humans...</code></pre>
                </div>

                <div class="flex flex-col gap-8 agent:hidden">
                    <div>
                        <span class="inline-block text-xs font-medium px-2 py-0.5 rounded-full bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200 mb-3">YAML</span>
                        <h3 class="text-sm font-semibold text-mauve-950 dark:text-white mb-2">Frontmatter</h3>
                        <p class="text-sm/7 text-mauve-700 dark:text-mauve-400">Serves as an introductory summary — ~100 tokens, ~540 characters. AI agents read this first to decide if the full document is relevant before fetching it. Functions as a lightweight preflighted index.</p>
                    </div>
                    <div class="border-t border-mauve-200 dark:border-mauve-700 pt-8">
                        <span class="inline-block text-xs font-medium px-2 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-900 text-emerald-800 dark:text-emerald-200 mb-3">MD</span>
                        <h3 class="text-sm font-semibold text-mauve-950 dark:text-white mb-2">Markdown body</h3>
                        <p class="text-sm/7 text-mauve-700 dark:text-mauve-400">CommonMark or GitHub-flavored Markdown. Must open with a first-level heading. Prefer text over images — link images and include alternate text. Preserve document hierarchy starting from level two.</p>
                    </div>
                </div>

            </div>
        </x-container>

    </div>


    {{-- Frontmatter reference table --}}
    <div class="bg-mauve-200 dark:bg-mauve-700 pt-8">

        @component('_components.section')
            The fields.

            @slot('eyebrow')
                <x-eyebrow>Frontmatter Reference</x-eyebrow>
            @endslot
        @endcomponent

        <x-container class="pb-12 mt-4">
            <div class="overflow-x-auto agent:hidden">
                <table class="w-full text-sm border-collapse">
                    <thead>
                        <tr class="border-b border-mauve-300 dark:border-mauve-600">
                            <th class="text-left py-3 pr-6 font-semibold text-mauve-950 dark:text-white">Field</th>
                            <th class="text-left py-3 pr-6 font-semibold text-mauve-950 dark:text-white">Required</th>
                            <th class="text-left py-3 font-semibold text-mauve-950 dark:text-white">Description</th>
                        </tr>
                    </thead>
                    <tbody class="text-mauve-700 dark:text-mauve-300">
                        <tr class="border-b border-mauve-300/60 dark:border-mauve-600/60">
                            <td class="py-3 pr-6"><code class="text-xs bg-mauve-100 dark:bg-mauve-800 px-1.5 py-0.5 rounded">title</code></td>
                            <td class="py-3 pr-6"><span class="inline-block text-xs font-medium px-2 py-0.5 rounded-full bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200">Required</span></td>
                            <td class="py-3">Non-empty. The title of the resource — article, page, document.</td>
                        </tr>
                        <tr class="border-b border-mauve-300/60 dark:border-mauve-600/60">
                            <td class="py-3 pr-6"><code class="text-xs bg-mauve-100 dark:bg-mauve-800 px-1.5 py-0.5 rounded">description</code></td>
                            <td class="py-3 pr-6"><span class="inline-block text-xs font-medium px-2 py-0.5 rounded-full bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200">Required</span></td>
                            <td class="py-3">Non-empty. Best ~200 characters. A short and accurate summary of the content.</td>
                        </tr>
                        <tr class="border-b border-mauve-300/60 dark:border-mauve-600/60">
                            <td class="py-3 pr-6"><code class="text-xs bg-mauve-100 dark:bg-mauve-800 px-1.5 py-0.5 rounded">date</code></td>
                            <td class="py-3 pr-6"><span class="inline-block text-xs font-medium px-2 py-0.5 rounded-full bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200">Encouraged</span></td>
                            <td class="py-3">Date of creation or publication, whichever is more recent. ISO 8601 format.</td>
                        </tr>
                        <tr class="border-b border-mauve-300/60 dark:border-mauve-600/60">
                            <td class="py-3 pr-6"><code class="text-xs bg-mauve-100 dark:bg-mauve-800 px-1.5 py-0.5 rounded">license</code></td>
                            <td class="py-3 pr-6"><span class="inline-block text-xs font-medium px-2 py-0.5 rounded-full bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200">Encouraged</span></td>
                            <td class="py-3">License name or SPDX Identifier of the content.</td>
                        </tr>
                        <tr>
                            <td class="py-3 pr-6"><code class="text-xs bg-mauve-100 dark:bg-mauve-800 px-1.5 py-0.5 rounded">author</code></td>
                            <td class="py-3 pr-6"><span class="inline-block text-xs font-medium px-2 py-0.5 rounded-full bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200">Encouraged</span></td>
                            <td class="py-3">Author of the content. Host owner is assumed as author if not provided.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="hidden agent:block prose prose-mauve dark:prose-invert max-w-none">
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

            <p class="mt-4 text-xs text-mauve-600 dark:text-mauve-400">
                Fields map to Dublin Core, schema.org CreativeWork, and standard HTML meta equivalents.
            </p>
        </x-container>

    </div>


    {{-- Custom blocks section --}}
    @component('_components.section')
        Navigation, figures, and more.

        @slot('eyebrow')
            <x-eyebrow>Custom Blocks</x-eyebrow>
        @endslot

        @slot('subheadline')
            content-md proposes custom blocks to include navigation affordances, image descriptions, formal abstracts, and advertising. AI agents may choose to skip advertisement blocks.
        @endslot
    @endcomponent

    <x-container class="mb-16 mt-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 agent:flex agent:flex-col">

            <div class="rounded-lg p-5 bg-mauve-950/2.5 dark:bg-white/5 ring-1 ring-mauve-200 dark:ring-mauve-700">
                <div class="flex items-center gap-2 mb-2">
                    <code class="text-xs font-medium px-2 py-0.5 rounded bg-mauve-200 dark:bg-mauve-700 text-mauve-700 dark:text-mauve-300">&lt;nav&gt;</code>
                    <span class="text-sm font-semibold text-mauve-950 dark:text-white">Navigation</span>
                </div>
                <p class="text-xs/5 text-mauve-600 dark:text-mauve-400 mb-3">Communicate website navigation or linked resources relevant to the content.</p>
                <div class="rounded bg-mauve-950 p-3 font-mono text-xs leading-relaxed text-mauve-100 overflow-x-auto agent:hidden">
<span class="text-emerald-400">&lt;nav&gt;</span><br>
- Next: <span class="text-blue-400">[Next article](https://example.com/next)</span><br>
- Related: <span class="text-blue-400">[Topic guide](https://example.com/topic)</span><br>
<span class="text-emerald-400">&lt;/nav&gt;</span>
                </div>
                <div class="hidden agent:block text-xs text-mauve-600 dark:text-mauve-400">
                    <pre><code>&lt;nav&gt;
- Next: [Next article](https://example.com/next)
- Related: [Topic guide](https://example.com/topic)
&lt;/nav&gt;</code></pre>
                </div>
            </div>

            <div class="rounded-lg p-5 bg-mauve-950/2.5 dark:bg-white/5 ring-1 ring-mauve-200 dark:ring-mauve-700">
                <div class="flex items-center gap-2 mb-2">
                    <code class="text-xs font-medium px-2 py-0.5 rounded bg-mauve-200 dark:bg-mauve-700 text-mauve-700 dark:text-mauve-300">&lt;figure&gt;</code>
                    <span class="text-sm font-semibold text-mauve-950 dark:text-white">Image (alternate)</span>
                </div>
                <p class="text-xs/5 text-mauve-600 dark:text-mauve-400 mb-3">Signal an image is present using its alternate text or caption instead of a binary embed.</p>
                <div class="rounded bg-mauve-950 p-3 font-mono text-xs leading-relaxed text-mauve-100 overflow-x-auto agent:hidden">
<span class="text-emerald-400">&lt;figure&gt;</span><br>
Alternate text describing the image<br>
and/or its caption for AI context.<br>
<span class="text-emerald-400">&lt;/figure&gt;</span>
                </div>
                <div class="hidden agent:block text-xs text-mauve-600 dark:text-mauve-400">
                    <pre><code>&lt;figure&gt;
Alternate text describing the image and/or caption
&lt;/figure&gt;</code></pre>
                </div>
            </div>

            <div class="rounded-lg p-5 bg-mauve-950/2.5 dark:bg-white/5 ring-1 ring-mauve-200 dark:ring-mauve-700">
                <div class="flex items-center gap-2 mb-2">
                    <code class="text-xs font-medium px-2 py-0.5 rounded bg-mauve-200 dark:bg-mauve-700 text-mauve-700 dark:text-mauve-300">&lt;abstract&gt;</code>
                    <span class="text-sm font-semibold text-mauve-950 dark:text-white">Abstract</span>
                </div>
                <p class="text-xs/5 text-mauve-600 dark:text-mauve-400 mb-3">For scientific articles with formal abstracts. The <code class="bg-mauve-200 dark:bg-mauve-700 px-1 rounded">lang</code> attribute is optional.</p>
                <div class="rounded bg-mauve-950 p-3 font-mono text-xs leading-relaxed text-mauve-100 overflow-x-auto agent:hidden">
<span class="text-emerald-400">&lt;abstract lang="en"&gt;</span><br>
We present a novel approach to<br>
serving web content to AI agents...<br>
<span class="text-emerald-400">&lt;/abstract&gt;</span>
                </div>
                <div class="hidden agent:block text-xs text-mauve-600 dark:text-mauve-400">
                    <pre><code>&lt;abstract lang="en"&gt;
We present a novel approach to serving web content to AI agents...
&lt;/abstract&gt;</code></pre>
                </div>
            </div>

            <div class="rounded-lg p-5 bg-mauve-950/2.5 dark:bg-white/5 ring-1 ring-mauve-200 dark:ring-mauve-700">
                <div class="flex items-center gap-2 mb-2">
                    <code class="text-xs font-medium px-2 py-0.5 rounded bg-mauve-200 dark:bg-mauve-700 text-mauve-700 dark:text-mauve-300">[!AD]</code>
                    <span class="text-sm font-semibold text-mauve-950 dark:text-white">Advertisement</span>
                </div>
                <p class="text-xs/5 text-mauve-600 dark:text-mauve-400 mb-3">Include paid advertisements alongside content — AI agents may choose to ignore them.</p>
                <div class="rounded bg-mauve-950 p-3 font-mono text-xs leading-relaxed text-mauve-100 overflow-x-auto agent:hidden">
<span class="text-yellow-300">&gt; [!AD]</span><br>
<span class="text-yellow-300">&gt; Buy one, get two — promo active</span><br>
<span class="text-yellow-300">&gt; for the next 30 days.</span>
                </div>
                <div class="hidden agent:block text-xs text-mauve-600 dark:text-mauve-400">
                    <pre><code>&gt; [!AD]
&gt; Buy one, get two — promo active
&gt; for the next 30 days.</code></pre>
                </div>
            </div>

        </div>
    </x-container>


    {{-- Serving / Content negotiation --}}
    <div class="bg-mauve-200 dark:bg-mauve-700 pt-8">

        @component('_components.section')
            Content negotiation.

            @slot('eyebrow')
                <x-eyebrow>How It Works</x-eyebrow>
            @endslot

            @slot('subheadline')
                content-md uses the standard HTTP <code class="text-xs bg-mauve-100 dark:bg-mauve-800 px-1.5 py-0.5 rounded">Accept</code> header — a mechanism browsers and servers have used for decades. AI agents that know the protocol get the optimized version. Others get HTML as usual.
            @endslot
        @endcomponent

        <x-container class="mt-8 pb-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 agent:flex agent:flex-col">

                <div class="flex flex-col gap-3">
                    <div class="text-2xl font-extrabold font-serif text-mauve-400 dark:text-mauve-500">01</div>
                    <h3 class="text-sm font-semibold text-mauve-950 dark:text-white">AI Agent requests</h3>
                    <p class="text-sm/7 text-mauve-700 dark:text-mauve-400">The AI agent sends an HTTP request with <code class="text-xs bg-mauve-100 dark:bg-mauve-800 px-1.5 py-0.5 rounded">text/markdown</code> as its highest preference in the <code class="text-xs bg-mauve-100 dark:bg-mauve-800 px-1.5 py-0.5 rounded">Accept</code> header.</p>
                    <div class="rounded bg-mauve-950 dark:bg-mauve-900 p-3 font-mono text-xs text-mauve-100 overflow-x-auto agent:hidden">
                        Accept: text/markdown, text/html;q=0.8
                    </div>
                </div>

                <div class="flex flex-col gap-3">
                    <div class="text-2xl font-extrabold font-serif text-mauve-400 dark:text-mauve-500">02</div>
                    <h3 class="text-sm font-semibold text-mauve-950 dark:text-white">Server negotiates</h3>
                    <p class="text-sm/7 text-mauve-700 dark:text-mauve-400">The server detects the preference and checks if content-md is available for the requested resource.</p>
                    <div class="rounded bg-mauve-950 dark:bg-mauve-900 p-3 font-mono text-xs text-mauve-100 overflow-x-auto agent:hidden">
                        Content-Type: text/markdown
                    </div>
                </div>

                <div class="flex flex-col gap-3">
                    <div class="text-2xl font-extrabold font-serif text-mauve-400 dark:text-mauve-500">03</div>
                    <h3 class="text-sm font-semibold text-mauve-950 dark:text-white">Delivers content-md</h3>
                    <p class="text-sm/7 text-mauve-700 dark:text-mauve-400">YAML frontmatter + Markdown body is returned with standard caching headers. Start with a 7-day expiry.</p>
                    <div class="rounded bg-mauve-950 dark:bg-mauve-900 p-3 font-mono text-xs text-mauve-100 overflow-x-auto agent:hidden">
                        Cache-Control: max-age=604800
                    </div>
                </div>

            </div>

            {{-- Range requests --}}
            <div class="mt-8 rounded-lg p-6 bg-white/50 dark:bg-white/10 ring-1 ring-mauve-300 dark:ring-mauve-500">
                <div class="flex items-center gap-3 mb-3">
                    <span class="inline-block text-xs font-medium px-2 py-0.5 rounded-full bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200">Experimental</span>
                    <h3 class="text-sm font-semibold text-mauve-950 dark:text-white">Range requests — frontmatter only</h3>
                </div>
                <p class="text-sm/7 text-mauve-700 dark:text-mauve-400 max-w-2xl">
                    AI agents can request <em>only the frontmatter</em> using HTTP range requests with a non-standard unit. This allows lightweight topic verification before fetching the full document — efficient exploration at scale.
                </p>
                <div class="mt-4 rounded bg-mauve-950 dark:bg-mauve-900 p-3 font-mono text-xs text-mauve-100 inline-block agent:hidden">
                    Range: x-frontmatter
                </div>
                <p class="mt-3 text-xs text-mauve-600 dark:text-mauve-400">
                    Server signals support via <code class="bg-mauve-100 dark:bg-mauve-800 px-1 rounded">Accept-Range: x-frontmatter</code> response header. The Caddy implementation supports both <code class="bg-mauve-100 dark:bg-mauve-800 px-1 rounded">x-frontmatter</code> and <code class="bg-mauve-100 dark:bg-mauve-800 px-1 rounded">bytes</code> range units.
                </p>
            </div>

        </x-container>

    </div>


    {{-- Ecosystem section --}}
    @component('_components.section')
        Tools &amp; plugins.

        @slot('eyebrow')
            <x-eyebrow>Ecosystem</x-eyebrow>
        @endslot

        @slot('subheadline')
            The content-md ecosystem is growing. From CMS plugins to server middleware, these tools help you start serving content-md without building from scratch.
        @endslot
    @endcomponent

    <x-container class="mb-16 mt-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 agent:flex agent:flex-col">

            <div class="rounded-lg p-5 bg-mauve-950/2.5 dark:bg-white/5 ring-1 ring-mauve-200 dark:ring-mauve-700">
                <h3 class="text-sm font-semibold text-mauve-950 dark:text-white mb-2">WordPress Plugin</h3>
                <p class="text-xs/5 text-mauve-600 dark:text-mauve-400 mb-3">Return content-md versions of articles and pages with zero configuration. Drop-in plugin for the world's most popular CMS.</p>
                <span class="inline-block text-xs font-medium px-2 py-0.5 rounded-full bg-mauve-100 dark:bg-mauve-800 text-mauve-600 dark:text-mauve-400">In development</span>
            </div>

            <div class="rounded-lg p-5 bg-mauve-950/2.5 dark:bg-white/5 ring-1 ring-mauve-200 dark:ring-mauve-700">
                <h3 class="text-sm font-semibold text-mauve-950 dark:text-white mb-2">Caddy Plugin</h3>
                <p class="text-xs/5 text-mauve-600 dark:text-mauve-400 mb-3">Serve pre-existing markdown files via the Caddy web server with proper content negotiation headers built in.</p>
                <span class="inline-block text-xs font-medium px-2 py-0.5 rounded-full bg-mauve-100 dark:bg-mauve-800 text-mauve-600 dark:text-mauve-400">In development</span>
            </div>

            <div class="rounded-lg p-5 bg-mauve-950/2.5 dark:bg-white/5 ring-1 ring-mauve-200 dark:ring-mauve-700">
                <h3 class="text-sm font-semibold text-mauve-950 dark:text-white mb-2">Markdown Parsers</h3>
                <p class="text-xs/5 text-mauve-600 dark:text-mauve-400 mb-3">Parser plugins for popular libraries to handle content-md custom blocks: <code class="bg-mauve-200 dark:bg-mauve-700 px-1 rounded">&lt;nav&gt;</code>, <code class="bg-mauve-200 dark:bg-mauve-700 px-1 rounded">&lt;figure&gt;</code>, <code class="bg-mauve-200 dark:bg-mauve-700 px-1 rounded">&lt;abstract&gt;</code>.</p>
                <span class="inline-block text-xs font-medium px-2 py-0.5 rounded-full bg-mauve-100 dark:bg-mauve-800 text-mauve-600 dark:text-mauve-400">In development</span>
            </div>

            <div class="rounded-lg p-5 bg-mauve-950/2.5 dark:bg-white/5 ring-1 ring-mauve-200 dark:ring-mauve-700">
                <h3 class="text-sm font-semibold text-mauve-950 dark:text-white mb-2">CLI Validator</h3>
                <p class="text-xs/5 text-mauve-600 dark:text-mauve-400 mb-3">Validate a content-md page, estimate its size and token count. Ensure you're within context window bounds before publishing.</p>
                <span class="inline-block text-xs font-medium px-2 py-0.5 rounded-full bg-mauve-100 dark:bg-mauve-800 text-mauve-600 dark:text-mauve-400">In development</span>
            </div>

        </div>
    </x-container>


    {{-- Comparison section --}}
    <div class="bg-mauve-200 dark:bg-mauve-700 pt-8">

        @component('_components.section')
            vs. LLMs.txt, Agents.md and Skills.

            @slot('eyebrow')
                <x-eyebrow>Comparison</x-eyebrow>
            @endslot
        @endcomponent

        <x-container class="pb-12 mt-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 agent:flex agent:flex-col">

                <div class="rounded-lg p-6 bg-white/50 dark:bg-white/10 ring-1 ring-mauve-300 dark:ring-mauve-500">
                    <div class="flex items-baseline gap-2 mb-3">
                        <span class="text-xs font-semibold text-mauve-400 dark:text-mauve-500">vs.</span>
                        <h3 class="text-base font-semibold text-mauve-950 dark:text-white">LLMs.txt</h3>
                        <a href="https://llmstxt.org/" target="_blank" rel="noopener noreferrer" class="ml-auto text-xs text-accent-600 dark:text-accent-400 hover:underline">llmstxt.org ↗</a>
                    </div>
                    <p class="text-sm/7 text-mauve-700 dark:text-mauve-400 mb-4">Focuses on the <strong class="text-mauve-950 dark:text-white">overall website</strong> — a single entry point listing all available content. Think of it as a sitemap for AI agents.</p>
                    <div class="flex flex-col gap-1.5 mb-4 text-xs">
                        <div class="text-emerald-700 dark:text-emerald-400">✓ Predictable URL at website root</div>
                        <div class="text-emerald-700 dark:text-emerald-400">✓ Birds-eye view of all content</div>
                        <div class="text-mauve-500 dark:text-mauve-400">✗ Not specific to a resource</div>
                        <div class="text-mauve-500 dark:text-mauve-400">✗ Not discovered via direct URL</div>
                    </div>
                    <p class="text-xs text-mauve-600 dark:text-mauve-400 border-t border-mauve-200 dark:border-mauve-600 pt-3">They coexist like sitemaps and pages — content-md describes individual resources.</p>
                </div>

                <div class="rounded-lg p-6 bg-white/50 dark:bg-white/10 ring-1 ring-mauve-300 dark:ring-mauve-500">
                    <div class="flex items-baseline gap-2 mb-3">
                        <span class="text-xs font-semibold text-mauve-400 dark:text-mauve-500">vs.</span>
                        <h3 class="text-base font-semibold text-mauve-950 dark:text-white">Agents.md</h3>
                        <a href="https://agents.md/" target="_blank" rel="noopener noreferrer" class="ml-auto text-xs text-accent-600 dark:text-accent-400 hover:underline">agents.md ↗</a>
                    </div>
                    <p class="text-sm/7 text-mauve-700 dark:text-mauve-400 mb-4">Targets <strong class="text-mauve-950 dark:text-white">coding agents</strong> by providing README context within code repositories — build steps, tests, and conventions.</p>
                    <div class="flex flex-col gap-1.5 mb-4 text-xs">
                        <div class="text-mauve-600 dark:text-mauve-400">→ Different audience entirely</div>
                        <div class="text-mauve-600 dark:text-mauve-400">→ Repository-scoped, not web-scoped</div>
                    </div>
                    <p class="text-xs text-mauve-600 dark:text-mauve-400 border-t border-mauve-200 dark:border-mauve-600 pt-3">content-md does not target coding agents. The two serve entirely different contexts.</p>
                </div>

                <div class="rounded-lg p-6 bg-white/50 dark:bg-white/10 ring-1 ring-mauve-300 dark:ring-mauve-500">
                    <div class="flex items-baseline gap-2 mb-3">
                        <span class="text-xs font-semibold text-mauve-400 dark:text-mauve-500">vs.</span>
                        <h3 class="text-base font-semibold text-mauve-950 dark:text-white">Skills</h3>
                        <a href="https://agentskills.io/" target="_blank" rel="noopener noreferrer" class="ml-auto text-xs text-accent-600 dark:text-accent-400 hover:underline">agentskills.io ↗</a>
                    </div>
                    <p class="text-sm/7 text-mauve-700 dark:text-mauve-400 mb-4">Provides additional knowledge and a birds-eye view of available content to agents, currently within dedicated folders.</p>
                    <div class="flex flex-col gap-1.5 mb-4 text-xs">
                        <div class="text-emerald-700 dark:text-emerald-400">✓ Provides knowledge to agents</div>
                        <div class="text-emerald-700 dark:text-emerald-400">✓ Structured content</div>
                        <div class="text-mauve-500 dark:text-mauve-400">✗ Not discovered via direct URL</div>
                    </div>
                    <p class="text-xs text-mauve-600 dark:text-mauve-400 border-t border-mauve-200 dark:border-mauve-600 pt-3">content-md responses are nearly compatible with Skills — frontmatter fields map closely.</p>
                </div>

            </div>
        </x-container>

    </div>


    {{-- CTA --}}
    <div class="h-8 agent:hidden"></div>

    @component('_components.banner-section')
        Ready to serve AI agents the right way?

        @slot('subheadline')
            content-md is an open specification — free to adopt and implement. Read the full spec, explore the ecosystem, or start serving optimized content to AI agents today.
        @endslot

        @slot('cta')
        <div class="flex flex-wrap gap-3">
            <a href="https://github.com/OneOffTech/content-md" target="_blank" rel="noopener noreferrer" class="select-none inline-flex items-center gap-2 leading-4 ring-1 ring-accent-900/10 font-medium px-6 py-4 shadow-lg rounded-md text-white agent:text-white! bg-gradient-radial-blue hover:bg-mauve-50 dark:bg-mauve-600 agent:dark:bg-mauve-100 dark:text-white agent:dark:text-mauve-950! dark:hover:bg-mauve-500 agent:dark:hover:bg-mauve-200 focus:outline-none focus:ring-2 focus:ring-accent-500 whitespace-nowrap transition-button">
                Read the spec on GitHub
            </a>
        </div>
        @endslot
    @endcomponent

    <div class="h-14 agent:hidden"></div>

    {{-- <x-agent-suggestions hint="Want to know more about content-md or how to implement it?">
        <ul>
            <li>Explain how content-md content negotiation works and how to implement it in my server</li>
            <li>Show me how to structure a content-md document for an article on my website</li>
            <li>How does content-md compare to LLMs.txt and when should I use each?</li>
            <li>What are the required and encouraged frontmatter fields in content-md?</li>
            <li>How do I convert my existing Markdown files to be content-md compliant?</li>
        </ul>
    </x-agent-suggestions> --}}

    <div class="h-14 agent:hidden"></div>

@endsection

@push('markdown')

# content-md — The web, spoken fluently to AI agents.

Open Specification · Draft

content-md is an open specification for optimized content exchange. Serve high-fidelity textual representation to AI agents through standard HTTP content negotiation — no scraping required.

## Why content-md

AI agents are increasingly browsing the web on behalf of humans. The web was built with humans in mind that demand quality and pleasant interaction. Agents go straight to the point and prefer a more structured approach.

Converting complex HTML pages with navigation, ads, and JavaScript into LLM-friendly plain text is both difficult and imprecise. content-md solves this at the protocol level.

- Builds on your existing CMS content — no migration required
- Standard HTTP content negotiation — the same mechanism browsers and servers have used for decades
- YAML frontmatter + Markdown body — familiar, concise, designed to stay within AI context window limits

## The Format

content-md starts with a YAML frontmatter block providing context (~100 tokens), followed by a Markdown document.

### Frontmatter

Serves as an introductory summary — ~100 tokens, ~540 characters. AI agents read this first to decide if the full document is relevant before fetching it.

| Field | Required | Description |
| --- | --- | --- |
| title | Required | Non-empty. The title of the resource — article, page, document. |
| description | Required | Non-empty. Best ~200 characters. A short and accurate summary of the content. |
| date | Encouraged | Date of creation or publication, whichever is more recent. ISO 8601 format. |
| license | Encouraged | License name or SPDX Identifier of the content. |
| author | Encouraged | Author of the content. Host owner is assumed as author if not provided. |

Fields map to Dublin Core, schema.org CreativeWork, and standard HTML meta equivalents.

### Markdown body

CommonMark or GitHub-flavored Markdown. Must open with a first-level heading. Prefer text over images — link images and include alternate text. Preserve document hierarchy starting from level two.

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

## How It Works — Content negotiation

content-md uses the standard HTTP `Accept` header. AI agents that know the protocol get the optimized version. Others get HTML as usual.

**01 — AI Agent requests**: The AI agent sends an HTTP request with `text/markdown` as its highest preference.

```
Accept: text/markdown, text/html;q=0.8
```

**02 — Server negotiates**: The server detects the preference and checks if content-md is available.

```
Content-Type: text/markdown
```

**03 — Delivers content-md**: YAML frontmatter + Markdown body is returned with standard caching headers.

```
Cache-Control: max-age=604800
```

### Range requests (Experimental)

AI agents can request only the frontmatter using HTTP range requests. This allows lightweight topic verification before fetching the full document.

```
Range: x-frontmatter
```

Server signals support via `Accept-Range: x-frontmatter`. The Caddy implementation supports both `x-frontmatter` and `bytes` range units.

## Ecosystem

The content-md ecosystem is growing. All tools are currently in development.

- **WordPress Plugin**: Return content-md versions of articles and pages with zero configuration.
- **Caddy Plugin**: Serve pre-existing markdown files via the Caddy web server with proper content negotiation headers built in.
- **Markdown Parsers**: Parser plugins for popular libraries to handle content-md custom blocks: `<nav>`, `<figure>`, `<abstract>`.
- **CLI Validator**: Validate a content-md page, estimate its size and token count.

## Comparison

### vs LLMs.txt

LLMs.txt focuses on the overall website — a single entry point listing all available content. They coexist like sitemaps and pages — content-md describes individual resources.

### vs Agents.md

Agents.md targets coding agents by providing README context within code repositories. content-md does not target coding agents. The two serve entirely different contexts.

### vs Skills

Skills provides additional knowledge to agents, currently within dedicated folders. content-md responses are nearly compatible with Skills — frontmatter fields map closely.

**Want to know more about content-md or how to implement it?**

- Explain how content-md content negotiation works and how to implement it in my server
- Show me how to structure a content-md document for an article on my website
- How does content-md compare to LLMs.txt and when should I use each?
- What are the required and encouraged frontmatter fields in content-md?
- How do I convert my existing Markdown files to be content-md compliant?

@endpush
