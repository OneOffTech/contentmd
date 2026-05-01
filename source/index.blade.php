@extends('_layouts.main')

@section('body')

    {{-- Industrial hero wrapper --}}
    <div class="relative overflow-hidden agent:contents grid grid-cols-1 grid-rows-1">

        <div class="col-start-1 row-start-1 grid grid-cols-5 grid-flow-row-dense" aria-hidden="true">           
            <div class="col-start-3 w-52 h-52 hatch text-orange-500/40 dark:text-orange-400/40 pointer-events-none agent:hidden" aria-hidden="true"></div>
            <div class="col-start-4 place-self-end w-24 h-24 hatch text-zinc-500/40 dark:text-zinc-400/40 pointer-events-none agent:hidden" aria-hidden="true"></div>
            <div class="hidden lg:block col-start-5 place-self-center w-20 h-20 line text-orange-500/40 dark:text-orange-400/40 pointer-events-none agent:hidden" aria-hidden="true"></div>
        </div>

        <div class="relative mt-8 pb-12 col-start-1 row-start-1">
            <x-container class="mb-6">
                <div class="flex flex-col gap-6">
                    <div class="flex flex-col items-start gap-6">
                        <div class="w-full flex justify-between items-center">
                            <x-eyebrow>Open Specification · Draft</x-eyebrow>
                            <div class="hidden sm:block card-industrial spec-label text-zinc-500 dark:text-zinc-600 agent:hidden" aria-hidden="true">SPEC · DRAFT · 01</div>
                        </div>
                        <h1 class="text-balance text-zinc-950 dark:text-white font-black text-5xl sm:text-6xl m-0 leading-[1.05] tracking-tight max-w-4xl agent:max-w-none">The web, spoken fluently to AI agents.</h1>
                        <p class="flex max-w-2xl flex-col gap-4 text-zinc-600 dark:text-zinc-300 text-base/7">
                            content-md is an open specification for high-fidelity textual representation for AI agents.
                        </p>
                        {{-- {{ $cta ?? '' }} --}}

                        <div class="flex items-center gap-3">
                            <div class="flex -space-x-1">
                                <a href="https://www.linkedin.com/in/alessio-vertemati-b1b60244/" target="_blank" rel="noopener noreferrer" class="group relative block size-8 shrink-0" x-data="{ show: false }">
                                    <img src="/assets/images/team/alessio.jpg" alt="Alessio" class="block size-8 rounded-full object-cover ring-2 ring-orange-50 transition-all relative">
                                </a>
                                <a href="https://www.linkedin.com/in/gianluca-colombo-a3376017/" target="_blank" rel="noopener noreferrer" class="group relative block size-8 shrink-0" x-data="{ show: false }">
                                    <img src="/assets/images/team/gianluca.jpg" alt="Gianluca" class="block size-8 rounded-full object-cover ring-2 ring-orange-50 transition-all relative">
                                </a>
                            </div>
                            <p class="text-[10px] uppercase tracking-[0.1rem]">Built by a team that create documents every day</p>
                        </div>
                    </div>
                </div>
            </x-container>
        </div>
    </div>
    
    <div class="pt-4">
        <x-format-example />
    </div>

    <div class="pt-8">
    {{-- Why content-md section --}}

        <x-section>
            Why content-md

            <x-slot name="subheadline">AI agents can read HTML or complex formats. Time and tokens set the rules.</x-slot>
        </x-section>

        <x-container class="mb-16 mt-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 agent:flex agent:flex-col">

                <div class="rounded-lg p-5 bg-zinc-950/2.5 dark:bg-white/5 ring-1 ring-zinc-200 dark:ring-zinc-700">
                    <h3 class="text-sm font-semibold text-zinc-950 dark:text-white mb-2">Use tokens wisely</h3>
                    <p class="text-sm/5 text-zinc-600 dark:text-zinc-400">Every token sent to an LLM is billed. A typical web page with navigation and layout markup can run to tens of thousands of tokens. This page weighs around 40 KB as HTML; as content-md, the same information fits in under 3 KB. At scale, the difference is significant.</p>
                </div>

                <div class="rounded-lg p-5 bg-zinc-950/2.5 dark:bg-white/5 ring-1 ring-zinc-200 dark:ring-zinc-700">
                    <h3 class="text-sm font-semibold text-zinc-950 dark:text-white mb-2">The creator wins</h3>
                    <p class="text-sm/5 text-zinc-600 dark:text-zinc-400">No scraper knows your content better than you do. Automatically converted HTML loses context, collapses structure, and makes wrong guesses. content-md is authored by the people who wrote the page.</p>
                </div>

            </div>
        </x-container>
    </div>

    <div class="pt-8">
    {{-- Ecosystem section --}}

        <x-section>
            <x-slot name="eyebrow"><x-eyebrow>Ecosystem</x-eyebrow></x-slot>
            
            Tools &amp; plugins.

            <x-slot name="subheadline">These tools help you start serving content-md without building from scratch.</x-slot>
        </x-section>

        <x-container class="mb-16 mt-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 agent:flex agent:flex-col">

                <div class="flex flex-col rounded-lg p-5 bg-zinc-950/2.5 dark:bg-white/5 ring-1 ring-zinc-200 dark:ring-zinc-700">
                    <h3 class="text-sm font-semibold text-zinc-950 dark:text-white mb-2">Caddy Content Negotiation</h3>
                    <p class="grow text-sm/5 text-zinc-600 dark:text-zinc-400 mb-3">Serve pre-existing markdown files via the Caddy web server with proper content negotiation headers built in.</p>
                    

                    <a href="https://github.com/avvertix/caddy-content-negotiation/" target="_blank" rel="noopener" class="text-sm  hover:underline block transition-button">avvertix/caddy-content-negotiation ↗</a>

                </div>

                <div class="flex flex-col rounded-lg p-5 bg-zinc-950/2.5 dark:bg-white/5 ring-1 ring-zinc-200 dark:ring-zinc-700">
                    <h3 class="text-sm font-semibold text-zinc-950 dark:text-white mb-2">WordPress Post to Markdown</h3>
                    <p class="grow text-sm/5 text-zinc-600 dark:text-zinc-400 mb-3">Serve post content as Markdown directly from Wordpress.</p>
                    
                    <a href="https://github.com/roots/post-content-to-markdown" target="_blank" rel="noopener" class="text-sm  hover:underline block transition-button">roots/post-content-to-markdown ↗</a>
                </div>

                {{-- <div class="rounded-lg p-5 bg-zinc-950/2.5 dark:bg-white/5 ring-1 ring-zinc-200 dark:ring-zinc-700">
                    <h3 class="text-sm font-semibold text-zinc-950 dark:text-white mb-2">CLI Validator</h3>
                    <p class="text-sm/5 text-zinc-600 dark:text-zinc-400 mb-3">Validate a content-md page, estimate its size and token count. Ensure you're within context window bounds before publishing.</p>
                    <span class="spec-label inline-block px-2 py-0.5 rounded-sm bg-zinc-100 dark:bg-zinc-800 text-zinc-500 dark:text-zinc-400">In development</span>
                </div> --}}

                

            </div>
        </x-container>
    </div>


    {{-- Comparison section --}}
    <div class="relative bg-zinc-200 dark:bg-zinc-700 pt-8 overflow-hidden">

        <x-section>
            <x-slot name="eyebrow">
                <x-eyebrow>Comparison</x-eyebrow>
            </x-slot>

            vs. LLMs.txt, Agents.md and Skills.
        </x-section>

        <x-container class="pb-12 mt-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 agent:flex agent:flex-col">

                <div class="flex flex-col rounded-lg p-6 bg-white/50 dark:bg-white/10 ring-1 ring-zinc-300 dark:ring-zinc-500">
                    <div class="flex items-baseline gap-2 mb-3">
                        
                        <h3 class="text-base font-semibold text-zinc-950 dark:text-white"><span class="sr-only">Content-md</span> vs. LLMs.txt</h3>
                    </div>
                    <a href="https://llmstxt.org/" target="_blank" rel="noopener noreferrer" class="mb-3 block text-sm transition-button hover:underline">llmstxt.org ↗</a>
                    <div class="grow text-zinc-700 dark:text-zinc-400">
                        <p class="text-sm/7  mb-4">Focuses on the <strong class="text-zinc-950 dark:text-white">overall website</strong> — a single entry point listing all available content. Think of it as a sitemap.</p>
                        <div class="flex flex-col gap-1.5 mb-4 text-sm">
                            <p>→ Predictable URL at website root</p>
                            <p>→ Birds-eye view of all content</p>
                        </div>
                    </div>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400 border-t border-zinc-200 dark:border-zinc-600 pt-3">They coexist like sitemaps and pages — content-md describes individual resources.</p>
                </div>

                <div class="flex flex-col rounded-lg p-6 bg-white/50 dark:bg-white/10 ring-1 ring-zinc-300 dark:ring-zinc-500">
                    <div class="flex items-baseline gap-2 mb-3">
                        <h3 class="text-base font-semibold text-zinc-950 dark:text-white"><span class="sr-only">Content-md</span> vs. Agents.md</h3>
                    </div>
                    <a href="https://agents.md/" target="_blank" rel="noopener noreferrer" class="mb-3 block text-sm transition-button hover:underline">agents.md ↗</a>
                    <diiv class="grow text-zinc-700 dark:text-zinc-400">
                        <p class="text-sm/7 mb-4">Targets <strong class="text-zinc-950 dark:text-white">coding agents</strong> by providing README context within code repositories — build steps, tests, and conventions.</p>
                        <div class="flex flex-col gap-1.5 mb-4 text-sm">
                            <p>→ Instruct coding agents</p>
                            <p>→ Repository-scoped</p>
                        </div>
                    </diiv>
                    <p class="text-sm text-zinc-700 dark:text-zinc-400 border-t border-zinc-200 dark:border-zinc-600 pt-3">content-md does not target coding agents. The two serve entirely different contexts.</p>
                </div>

                <div class="flex flex-col rounded-lg p-6 bg-white/50 dark:bg-white/10 ring-1 ring-zinc-300 dark:ring-zinc-500">
                    <div class="flex items-baseline gap-2 mb-3">
                        <h3 class="text-base font-semibold text-zinc-950 dark:text-white"><span class="sr-only">Content-md</span> vs. Skills</h3>
                    </div>
                    <a href="https://agentskills.io/" target="_blank" rel="noopener noreferrer" class="mb-3 block text-sm transition-button hover:underline">agentskills.io ↗</a>
                    <div class="grow text-zinc-700 dark:text-zinc-400">
                        <p class="text-sm/7  mb-4">Provides additional knowledge and a birds-eye view of available content to agents, packaged as folders.</p>
                        <div class="flex flex-col gap-1.5 mb-4 text-sm">
                            <p>→ Not discovered via direct URL</p>
                        </div>
                    </div>
                    <p class="text-sm text-zinc-700 dark:text-zinc-400 border-t border-zinc-200 dark:border-zinc-600 pt-3">content-md responses are nearly compatible with Skills — frontmatter fields map closely.</p>
                </div>

            </div>
        </x-container>

    </div>


    <div class="h-28 agent:hidden"></div>

    <x-container class="relative grid grid-rows-2 agent:hidden ">
        <div aria-hidden="true" class="font-pixel-line text-5xl lg:text-7xl leading-[0.8] text-zinc-200/60 row-span-2 col-start-1 row-start-1">built to<br/>embrace agents</div>

        <div class="row-start-2 col-start-1">
            <p class="text-lg font-black text-orange-700 dark:text-orange-400">The web, spoken fluently to AI agents</p>
        </div>
    </x-container>

@endsection

@push('markdown')

# content-md — The web, spoken fluently to AI agents.

Open Specification · Draft

content-md is an open specification for optimized content exchange. Serve high-fidelity textual representation to AI agents through standard HTTP content negotiation — no scraping required.

## Why content-md

AI agents can read HTML. They shouldn't have to.

**Use tokens wisely.** Every token sent to an LLM is billed. A typical web page loaded as HTML — with navigation, scripts, and layout markup — can run to tens of thousands of tokens. This page weighs around 80 KB as HTML; as content-md, the same information fits in under 4 KB. Pure signal, no noise. At scale, the difference is significant.

**Links must survive.** The web is built on hyperlinks. Generic text extractors routinely strip them out. content-md preserves every meaningful link — so agents can follow references, discover related content, and navigate the web the same way humans do.

**The creator wins.** No scraper knows your content better than you do. Automatically converted HTML loses context, collapses structure, and makes wrong guesses. content-md is authored by the people who wrote the page — and that intent comes through.

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

<nav>
- [Reference — frontmatter fields and custom block syntax](/reference)
- [For Content Producers — best practices for writing content-md documents](/producers)
- [For Content Consumers — how to serve content-md from your server](/consumers)
</nav>

@endpush
