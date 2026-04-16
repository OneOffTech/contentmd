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
    </head>
    <body class="font-inter antialiased bg-white dark:bg-mauve-950 tap-highlight-accent dark:text-white">

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
            class="border-b border-gray-100 dark:border-white/10 dark:bg-gray-900">
            <x-container class="flex items-center justify-end gap-3 py-2 text-xs agent:text-sm">
                <span class="text-mauve-500 dark:text-mauve-300">Mode:</span>
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
        <header class="border-b border-mauve-100 dark:border-mauve-800 agent:hidden">
            <x-container class="flex items-center justify-between py-4">
                <a href="/" class="font-mono font-medium text-mauve-950 dark:text-white text-sm hover:text-accent-600 dark:hover:text-accent-300 transition-button">
                    content-md
                    <span class="ml-1 text-xs font-normal text-mauve-400 dark:text-mauve-500">spec</span>
                </a>
                <nav class="flex items-center gap-6 text-sm">
                    <a href="https://github.com/OneOffTech/content-md" target="_blank" rel="noopener noreferrer" class="text-mauve-600 dark:text-mauve-300 hover:text-mauve-950 dark:hover:text-white transition-button">
                        GitHub ↗
                    </a>
                </nav>
            </x-container>
        </header>

        <main role="main">
            @yield('body')
        </main>

        {{-- Footer --}}
        <footer class="border-t border-mauve-100 dark:border-mauve-800 py-8 agent:hidden">
            <x-container class="flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-mauve-500 dark:text-mauve-400">
                <p>content-md is an open specification. <a href="https://github.com/OneOffTech/content-md" target="_blank" rel="noopener noreferrer" class="hover:text-mauve-950 dark:hover:text-white transition-button">Read the spec on GitHub ↗</a></p>
                <p>By <a href="https://oneofftech.de" target="_blank" rel="noopener noreferrer" class="hover:text-mauve-950 dark:hover:text-white transition-button">OneOff-Tech</a></p>
            </x-container>
        </footer>

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
