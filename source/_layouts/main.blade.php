<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="{{ $page->description ?? $page->siteDescription }}">
        <title>{{ $page->title ?? $page->siteTitle }}</title>

        @php
            $path_as_string = (string) $page->_meta->path;

            $chat_url = empty($path_as_string) ? 'index.md' : $path_as_string . '.md';
        @endphp

        <link rel="alternate" type="text/markdown" href="{{ $page->url($chat_url) }}">


        @viteRefresh()
        <link rel="stylesheet" href="{{ vite('source/_assets/css/main.css') }}">
        <script defer type="module" src="{{ vite('source/_assets/js/main.js') }}"></script>

        <link rel="icon" type="image/png" href="/assets/favicon-96x96.png" sizes="96x96" />
        <link rel="icon" type="image/svg+xml" href="/assets/favicon.svg" />
        <link rel="shortcut icon" href="/assets/favicon.ico" />
        <link rel="apple-touch-icon" sizes="180x180" href="/assets/apple-touch-icon.png" />
        <meta name="apple-mobile-web-app-title" content="Content-MD" />
        <link rel="manifest" href="/assets/site.webmanifest" />

        <meta property="og:title" content="{{ $page->title ?? $page->siteTitle }}">

        <meta property="og:locale" content="en_US">
        <meta name="description" content="{{ $page->description ?? $page->siteDescription }}">
        <meta property="og:description" content="{{ $page->description ?? $page->siteDescription }}">
        <meta property="og:url" content="{{ $page->url($page->getUrl()) }}">
        <meta property="og:site_name" content="OneOffTech">
        <meta property="og:image" content="{{ $page->url($page->card?->path ?? '/assets/og/main.png') }}">
        <meta property="og:type" content="website">
        <meta name="twitter:card" content="summary_large_image">
        <meta property="twitter:image" content="{{ $page->url($page->card?->path ?? '/assets/og/main.png') }}">
        <meta property="twitter:title" content="{{ $page->title ?? $page->siteTitle }}">

        <script type="application/json+ld">
        @php
            $ld = [
                "@context" => "https://schema.org",
                "@type" => "WebPage",
                "description" => $page->description ?? $page->siteDescription,
                "headline" => $page->title ?? $page->siteTitle,
                "url" => $page->getUrl(),
                "image" => [
                    "card" => "summary_large_image",
                    "url" => $page->url($image ?? '/assets/og/main.png', true),
                    "@type" => "imageObject",
                ]
            ];
        @endphp

        {!! \Illuminate\Support\Js::encode($ld) !!}
        </script>
    </head>
    <body class="font-inter antialiased bg-white dark:bg-zinc-950 tap-highlight-accent dark:text-white ">

        {{-- Mode toggle bar --}}
        <div
            x-data="{
                agent: sessionStorage.getItem('contentmd-mode') === 'agent',
                init() { this.applyAll(); },
                applyAll() {
                    const html = document.documentElement;
                    delete html.dataset.agent;
                    if (this.agent) {
                        html.dataset.agent = '';
                        sessionStorage.setItem('contentmd-mode', 'agent');
                    } else {
                        sessionStorage.setItem('contentmd-mode', '');
                    }
                },
                setAgent(val) { this.agent = val; this.applyAll() },
            }"
            class="shadow-xl ignore-agent z-50 fixed bottom-3.5 right-3.5 border border-zinc-600/20 bg-zinc-100 dark:border-white/10 dark:bg-zinc-900">
            <div class="px-4 flex items-center justify-end gap-3 py-2 text-xs agent:text-sm">
                <span class="sr-only">Switch viewing mode</span>
                <div class="flex items-center gap-1">
                    <button @click="setAgent(false)" class=" ignore-agent hit-area-y-2 flex items-center gap-1.5 rounded px-2 py-1 text-zinc-600 hover:text-accent-600 hover:bg-zinc-100 dark:text-zinc-300 dark:hover:bg-white/10 transition" :class="{ 'text-accent-600 font-medium': !agent }">
                        <span class="inline-flex w-3.5 h-3.5 rounded-sm border! border-current items-center justify-center">
                            <svg x-show="!agent" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 12 12" fill="currentColor" class="w-2.5 h-2.5"><path d="M10 3L5 8.5 2 5.5l-.8.8L5 10.1 10.8 3.8z"/></svg>
                        </span>
                        Human
                    </button>
                    <button @click="setAgent(true)" class=" ignore-agent hit-area-y-2 flex items-center gap-1.5 rounded px-2 py-1 text-zinc-600 hover:text-accent-600 hover:bg-zinc-100 dark:text-zinc-300 dark:hover:bg-white/10 transition" :class="{ 'text-accent-600 font-medium': agent }">
                        <span class="inline-flex w-3.5 h-3.5 rounded-sm border! border-current items-center justify-center">
                            <svg x-show="agent" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 12 12" fill="currentColor" class="w-2.5 h-2.5"><path d="M10 3L5 8.5 2 5.5l-.8.8L5 10.1 10.8 3.8z"/></svg>
                        </span>
                        Agent
                    </button>
                </div>
                <div class=" ignore-agent">
                    <x-copy-as-markdown-button />
                </div>
            </div>
        </div>

        {{-- Header --}}
        <header class="border-b border-zinc-100 dark:border-zinc-800 agent:hidden">
            <x-container class="flex items-center justify-between py-4">
                <a href="/" class="inline-flex gap-1 items-center text-xl sm:text-3xl font-mono font-medium text-zinc-950 dark:text-white hover:text-orange-600 dark:hover:text-orange-300 transition-button">
                    <x-logo class="text-orange-600 dark:text-orange-400 size-6 sm:size-8" />
                    content-md
                </a>
                <nav class="relative flex items-center gap-6 text-sm">
                    <div class="hidden sm:flex items-center gap-6 ">
                        <a href="/writers" class="text-zinc-600 dark:text-zinc-300 hover:text-zinc-950 dark:hover:text-white transition-button">Write content-md</a>
                        <a href="/consumers" class="text-zinc-600 dark:text-zinc-300 hover:text-zinc-950 dark:hover:text-white transition-button">Read content-md</a>
                        <a href="/reference" class="text-zinc-600 dark:text-zinc-300 hover:text-zinc-950 dark:hover:text-white transition-button">Reference</a>
                        <a href="{{ $page->github }}" target="_blank" rel="noopener noreferrer" class="text-zinc-600 dark:text-zinc-300 hover:text-zinc-950 dark:hover:text-white transition-button">
                            GitHub ↗
                        </a>
                    </div>
                    <details class="sm:hidden agent:hidden">
                        <summary class="transition-button cursor-pointer">Menu</summary>

                        <div class="absolute w-60 bg-white dark:bg-zinc-950 shadow z-10 right-0 flex flex-col items-end gap-2 p-4">
                            <a href="/writers" class="p-4 text-zinc-600 dark:text-zinc-300 hover:text-zinc-950 dark:hover:text-white transition-button">Write content-md</a>
                            <a href="/consumers" class="p-4 text-zinc-600 dark:text-zinc-300 hover:text-zinc-950 dark:hover:text-white transition-button">Read content-md</a>
                            <a href="/reference" class="p-4 text-zinc-600 dark:text-zinc-300 hover:text-zinc-950 dark:hover:text-white transition-button">Reference</a>
                            <a href="{{ $page->github }}" target="_blank" rel="noopener noreferrer" class="p-4 text-zinc-600 dark:text-zinc-300 hover:text-zinc-950 dark:hover:text-white transition-button">
                                GitHub ↗
                            </a>
                        </div>
                    </details>
                </nav>
            </x-container>
        </header>

        <main role="main">
            @yield('body')
        </main>

        {{-- Footer --}}
        <footer class="border-t border-zinc-100 dark:border-zinc-800 py-8 agent:hidden">
            <x-container class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 text-xs text-zinc-500 dark:text-zinc-400">
                <p>Brought you by Alessio and Gianluca, <a href="https://oneofftech.de" target="_blank" rel="noopener noreferrer" class="underline hover:text-zinc-950 dark:hover:text-white transition-button">OneOff-Tech</a> and contributors.</p>

                <x-oot class="text-accent-600 dark:text-zinc-200" />
            </x-container>
        </footer>

        <div class="h-28 md:h-10 flex flex-col md:flex-row md:justify-end items-end md:items-center mr-3.5 md:mr-0 md:mb-3.5">
            <p class="agent:hidden font-hand whitespace-nowrap">See like an AI Agent <span class="hidden md:inline">→</span><span class="md:hidden">↓</span></p>
            <p class="agent:hidden w-88"></p>
        </div>

        {{-- Markdown template for agent/copy-as-markdown --}}
        <template id="markdown-template">
---
title: '{{ $page->title }}'
description: '{{ $page->description }}'
date: {{ date('Y-m-d') }}
license: 'CC-BY-4.0'
---

@stack('markdown')

<nav>
- [Write content-md]({{ $page->url('/writers') }})
- [Read content-md]({{ $page->url('/consumers') }})
- [Reference]({{ $page->url('/reference') }})
- [GitHub ↗]({{ $page->github }})
</nav>

</template>

    </body>
</html>
