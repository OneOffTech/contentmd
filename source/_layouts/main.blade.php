<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="{{ $page->description }}">
        <title>{{ $page->title }}</title>
        @viteRefresh()
        <link rel="stylesheet" href="{{ vite('source/_assets/css/main.css') }}">
        <script defer type="module" src="{{ vite('source/_assets/js/main.js') }}"></script>

        <link rel="icon" type="image/png" href="/assets/favicon-96x96.png" sizes="96x96" />
        <link rel="icon" type="image/svg+xml" href="/assets/favicon.svg" />
        <link rel="shortcut icon" href="/assets/favicon.ico" />
        <link rel="apple-touch-icon" sizes="180x180" href="/assets/apple-touch-icon.png" />
        <meta name="apple-mobile-web-app-title" content="Content-MD" />
        <link rel="manifest" href="/assets/site.webmanifest" />
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
            class="ignore-agent z-50 fixed bottom-3.5 right-3.5 border border-gray-300/10 bg-gray-100 dark:border-white/10 dark:bg-gray-900">
            <x-container class="flex items-center justify-end gap-3 py-2 text-xs agent:text-sm">
                <span class="sr-only">Switch viewing mode</span>
                <div class="flex items-center gap-1">
                    <button @click="setAgent(false)" class="hit-area-y-2 flex items-center gap-1.5 rounded px-2 py-1 text-gray-600 hover:text-accent-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-white/10 transition" :class="{ 'text-accent-600 font-medium': !agent }">
                        <span class="inline-flex w-3.5 h-3.5 rounded-sm border! border-current items-center justify-center">
                            <svg x-show="!agent" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 12 12" fill="currentColor" class="w-2.5 h-2.5"><path d="M10 3L5 8.5 2 5.5l-.8.8L5 10.1 10.8 3.8z"/></svg>
                        </span>
                        Human
                    </button>
                    <button @click="setAgent(true)" class="hit-area-y-2 flex items-center gap-1.5 rounded px-2 py-1 text-gray-600 hover:text-accent-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-white/10 transition" :class="{ 'text-accent-600 font-medium': agent }">
                        <span class="inline-flex w-3.5 h-3.5 rounded-sm border! border-current items-center justify-center">
                            <svg x-show="agent" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 12 12" fill="currentColor" class="w-2.5 h-2.5"><path d="M10 3L5 8.5 2 5.5l-.8.8L5 10.1 10.8 3.8z"/></svg>
                        </span>
                        Agent
                    </button>
                </div>
            </x-container>
        </div>

        {{-- Header --}}
        <header class="border-b border-zinc-100 dark:border-zinc-800 agent:hidden">
            <x-container class="flex items-center justify-between py-4">
                <a href="/" class="inline-flex gap-1 items-center text-3xl font-mono font-medium text-zinc-950 dark:text-white hover:text-orange-600 dark:hover:text-orange-300 transition-button">
                    <x-logo class="text-orange-600 dark:text-orange-400 size-8" />
                    content-md
                </a>
                <nav class="flex items-center gap-6 text-sm">
                    <a href="/reference" class="text-zinc-600 dark:text-zinc-300 hover:text-zinc-950 dark:hover:text-white transition-button">Reference</a>
                    <a href="/writers" class="text-zinc-600 dark:text-zinc-300 hover:text-zinc-950 dark:hover:text-white transition-button">Write content-md</a>
                    <a href="/consumers" class="text-zinc-600 dark:text-zinc-300 hover:text-zinc-950 dark:hover:text-white transition-button">Read content-md</a>
                    <a href="{{ $page->github }}" target="_blank" rel="noopener noreferrer" class="text-zinc-600 dark:text-zinc-300 hover:text-zinc-950 dark:hover:text-white transition-button">
                        GitHub ↗
                    </a>
                </nav>
            </x-container>
        </header>

        <main role="main">
            @yield('body')
        </main>

        {{-- Footer --}}
        <footer class="border-t border-zinc-100 dark:border-zinc-800 py-8 agent:hidden">
            <x-container class="flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-zinc-500 dark:text-zinc-400">
                <p>Brought you by Alessio and Gianluca, <a href="https://oneofftech.de" target="_blank" rel="noopener noreferrer" class="hover:text-zinc-950 dark:hover:text-white transition-button">OneOff-Tech</a> and contributors.</p>

                <x-oot class="text-accent-600 dark:text-zinc-200" />
            </x-container>
        </footer>

        <div class="h-10 flex justify-end items-center mb-3.5">
            <p class="font-hand">See like an AI Agent →</p>
            <p class="w-64"></p>
        </div>

        {{-- Markdown template for agent/copy-as-markdown --}}
        <template id="markdown-template">
---
title: '{{ $page->title }}'
description: '{{ $page->description }}'
---

@stack('markdown')
        </template>

    </body>
</html>
