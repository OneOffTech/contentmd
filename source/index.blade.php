@extends('_layouts.main')

@section('body')

<x-page-hero>
    The web, spoken fluently to AI agents.

    <x-slot name="label">
        <div class="w-full flex justify-between items-center">
            <x-eyebrow>Open Specification · Draft</x-eyebrow>
            <div class="hidden sm:block card-industrial spec-label text-zinc-500 dark:text-zinc-600 agent:hidden" aria-hidden="true">SPEC · DRAFT · 01</div>
        </div>
    </x-slot>

    <x-slot name="description">
        content-md is an open specification for high-fidelity textual representation for AI agents.
    </x-slot>

    <x-slot name="decoration">
        <div class="hidden lg:block col-start-5 place-self-center w-20 h-20 line text-orange-500/40 dark:text-orange-400/40 pointer-events-none agent:hidden" aria-hidden="true"></div>
    </x-slot>

    <x-slot name="extra">
        <div class="flex items-center gap-3 agent:hidden">
            <div class="flex -space-x-1">
                <a href="https://www.linkedin.com/in/alessio-vertemati-b1b60244/" target="_blank" rel="noopener noreferrer" class="group relative block size-8 shrink-0">
                    <img src="/assets/images/team/alessio.jpg" alt="Alessio" class="block size-8 rounded-full object-cover ring-2 ring-orange-50 transition-all relative">
                </a>
                <a href="https://www.linkedin.com/in/gianluca-colombo-a3376017/" target="_blank" rel="noopener noreferrer" class="group relative block size-8 shrink-0">
                    <img src="/assets/images/team/gianluca.jpg" alt="Gianluca" class="block size-8 rounded-full object-cover ring-2 ring-orange-50 transition-all relative">
                </a>
            </div>
            <p class="text-[10px] uppercase tracking-[0.1rem]">Built by a team that create documents every day</p>
        </div>
    </x-slot>
</x-page-hero>

<div class="pt-4">
    <x-format-example />
</div>

<div class="pt-8">
    <x-section>
        Why content-md
        <x-slot name="subheadline">AI agents can read HTML or complex formats. Time and tokens set the rules.</x-slot>
    </x-section>

    <x-container class="mb-16 mt-4">
        <x-card-grid>
            <x-card title="Use tokens wisely">Every token sent to an LLM is billed. A typical web page with navigation and layout markup can run to tens of thousands of tokens. This page weighs around 40 KB as HTML; as content-md, the same content fits in under 3 KB.</x-card>
            <x-card title="The creator wins">No scraper knows your content better than you do. Automatically converted HTML loses context, collapses structure, and makes wrong guesses. content-md is authored by the people who wrote the page.</x-card>
        </x-card-grid>
    </x-container>
</div>

<div class="pt-8">
    <x-section>
        <x-slot name="eyebrow"><x-eyebrow>Ecosystem</x-eyebrow></x-slot>

        Tools &amp; plugins.

        <x-slot name="subheadline">These tools help you start serving content-md without building from scratch.</x-slot>
    </x-section>

    <x-container class="mb-16 mt-4">
        <x-card-grid cols="3-sm">
            <x-card title="contentmd CLI">
                Browse and validate content-md sites from the command line — fetch pages as an AI agent, run compliance checks, and convert content to agent skills.
                <x-slot name="action">
                    <a href="/cli" class="text-sm hover:underline block transition-button">CLI reference →</a>
                </x-slot>
            </x-card>
            <x-card title="Caddy Content Negotiation">
                Serve pre-existing markdown files via the Caddy web server with proper content negotiation headers built in.
                <x-slot name="action">
                    <a href="https://github.com/avvertix/caddy-content-negotiation/" target="_blank" rel="noopener" class="text-sm hover:underline block transition-button">avvertix/caddy-content-negotiation ↗</a>
                </x-slot>
            </x-card>
            <x-card title="WordPress Post to Markdown">
                Serve post content as Markdown directly from Wordpress.
                <x-slot name="action">
                    <a href="https://github.com/roots/post-content-to-markdown" target="_blank" rel="noopener" class="text-sm hover:underline block transition-button">roots/post-content-to-markdown ↗</a>
                </x-slot>
            </x-card>
        </x-card-grid>
    </x-container>
</div>

<x-section-panel class="relative overflow-hidden">
    <x-section>
        <x-slot name="eyebrow"><x-eyebrow>Comparison</x-eyebrow></x-slot>
        vs. LLMs.txt, Agents.md and Skills.
    </x-section>

    <x-container class="pb-12 mt-8">
        <x-card-grid cols="3">
            <x-comparison-card versus="LLMs.txt" href="https://llmstxt.org/" link="llmstxt.org">
                <p class="text-sm/7 mb-4">Covers the <strong class="text-zinc-950 dark:text-white">whole website</strong>: one URL listing everything available. Think of it as a sitemap.</p>
                <div class="flex flex-col gap-1.5 mb-4 text-sm">
                    <p>→ Predictable URL at website root</p>
                    <p>→ Birds-eye view of all content</p>
                </div>
                <x-slot name="footer">They coexist like sitemaps and pages. content-md describes individual resources.</x-slot>
            </x-comparison-card>

            <x-comparison-card versus="Agents.md" href="https://agents.md/" link="agents.md">
                <p class="text-sm/7 mb-4">Targets <strong class="text-zinc-950 dark:text-white">coding agents</strong> with README context for code repositories: build steps, tests, conventions.</p>
                <div class="flex flex-col gap-1.5 mb-4 text-sm">
                    <p>→ Instruct coding agents</p>
                    <p>→ Repository-scoped</p>
                </div>
                <x-slot name="footer">content-md does not target coding agents. The two serve entirely different contexts.</x-slot>
            </x-comparison-card>

            <x-comparison-card versus="Skills" href="https://agentskills.io/" link="agentskills.io">
                <p class="text-sm/7 mb-4">Provides additional knowledge and a birds-eye view of available content to agents, packaged as folders.</p>
                <div class="flex flex-col gap-1.5 mb-4 text-sm">
                    <p>→ Not discovered via direct URL</p>
                </div>
                <x-slot name="footer">content-md responses are nearly compatible with Skills; the frontmatter fields map closely.</x-slot>
            </x-comparison-card>
        </x-card-grid>
    </x-container>
</x-section-panel>


{{-- Badges --}}
<div class="pt-8">
    <x-section>
        <x-slot name="eyebrow"><x-eyebrow>Badges</x-eyebrow></x-slot>

        We have badges too

        <x-slot name="subheadline">Add a badge to show that your project or site serves content-md.</x-slot>
    </x-section>

    <x-container class="mb-16 mt-4">
        <x-card-grid>
            <div class="flex flex-col gap-3">
                <div class="rounded-lg py-5 flex items-center  agent:hidden">
                    <img src="/badge.svg" alt="content-md badge" width="132" height="20">
                </div>
                <p class="text-xs text-zinc-500 dark:text-zinc-400 agent:hidden">Markdown</p>
                <x-code-snippet>[![Supports content-md format for AI Agents](https://contentmd.org/badge.svg)](https://contentmd.org)</x-code-snippet>
                <p class="text-xs text-zinc-500 dark:text-zinc-400 agent:hidden">HTML</p>
                <x-code-snippet>&lt;a href="https://contentmd.org"&gt;&lt;img src="https://contentmd.org/badge.svg" alt="Supports content-md format for AI Agents"&gt;&lt;/a&gt;</x-code-snippet>
            </div>

            <div class="flex flex-col gap-3">
                <div class="rounded-lg py-5 flex items-center  agent:hidden">
                    <img src="/badge-flat.svg" alt="content-md flat badge" width="132" height="20">
                </div>
                <p class="text-xs text-zinc-500 dark:text-zinc-400 agent:hidden">Markdown</p>
                <x-code-snippet>[![Supports content-md format for AI Agents](https://contentmd.org/badge-flat.svg)](https://contentmd.org)</x-code-snippet>
                <p class="text-xs text-zinc-500 dark:text-zinc-400 agent:hidden">HTML</p>
                <x-code-snippet>&lt;a href="https://contentmd.org"&gt;&lt;img src="https://contentmd.org/badge-flat.svg" alt="Supports content-md format for AI Agents"&gt;&lt;/a&gt;</x-code-snippet>
            </div>
        </x-card-grid>

        <div class="hidden agent:block prose prose-zinc dark:prose-invert max-w-none mt-4">
            <p><strong>Standard:</strong> <code>[![Supports content-md format for AI Agents](https://contentmd.org/badge.svg)](https://contentmd.org)</code></p>
            <p><strong>Flat:</strong> <code>[![Supports content-md format for AI Agents](https://contentmd.org/badge-flat.svg)](https://contentmd.org)</code></p>
        </div>
    </x-container>
</div>

<div class="h-28 agent:hidden"></div>

<x-container class="relative grid grid-rows-2 agent:hidden">
    <div aria-hidden="true" class="font-pixel-line text-5xl lg:text-7xl leading-[0.8] text-zinc-200/60 row-span-2 col-start-1 row-start-1">built to<br/>embrace agents</div>
    <div class="row-start-2 col-start-1">
        <p class="text-lg font-black text-orange-700 dark:text-orange-400">The web, spoken fluently to AI agents</p>
    </div>
</x-container>

@endsection

@push('markdown')

# content-md — The web, spoken fluently to AI agents.

Open Specification · Draft

content-md is an open specification for high-fidelity textual representation for AI agents.

## Why content-md

AI agents can read HTML or complex formats. Time and tokens set the rules.

**Use tokens wisely.** Every token sent to an LLM is billed. A typical web page with navigation and layout markup can run to tens of thousands of tokens. This page weighs around 40 KB as HTML; as content-md, the same content fits in under 3 KB.

**The creator wins.** No scraper knows your content better than you do. Automatically converted HTML loses context, collapses structure, and makes wrong guesses. content-md is authored by the people who wrote the page.

## Ecosystem

Tools & plugins. These tools help you start serving content-md without building from scratch.

- **Caddy Content Negotiation**: Serve pre-existing markdown files via the Caddy web server with proper content negotiation headers built in. [avvertix/caddy-content-negotiation](https://github.com/avvertix/caddy-content-negotiation/)
- **WordPress Post to Markdown**: Serve post content as Markdown directly from Wordpress. [roots/post-content-to-markdown](https://github.com/roots/post-content-to-markdown)
- **contentmd CLI**: Browse and validate content-md sites from the command line — fetch pages as an AI agent, run compliance checks, and convert content to agent skills. [CLI reference](/cli)

## Comparison

### vs LLMs.txt

LLMs.txt covers the whole website: one URL listing everything available. They coexist like sitemaps and pages. content-md describes individual resources.

### vs Agents.md

Agents.md targets coding agents with README context for code repositories: build steps, tests, conventions. content-md does not target coding agents — the two serve entirely different purposes.

### vs Skills

Skills provides additional knowledge to agents, currently within dedicated folders. content-md responses are nearly compatible with Skills; the frontmatter fields map closely.

## Badges

Promote the usage of content-md via the badge.

Standard badge:
```
[![content-md](https://contentmd.org/badge.svg)](https://contentmd.org)
```

Flat badge:
```
[![content-md](https://contentmd.org/badge-flat.svg)](https://contentmd.org)
```

<nav>
- [Reference — frontmatter fields and custom block syntax](/reference)
- [For Content Producers — best practices for writing content-md documents](/producers)
- [For Content Consumers — how to serve content-md from your server](/consumers)
</nav>

@endpush
