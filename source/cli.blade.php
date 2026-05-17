---
title: contentmd CLI
description: Command-line tool for browsing and validating content-md sites — fetch pages as an AI agent, run compliance checks, and convert content to agent skills.
card:
  template: '_og.page'
  path: /assets/og/cli.png
---

@extends('_layouts.main')

@section('body')

<x-page-hero>
    Validate and browse content-md sites

    <x-slot name="label"><x-eyebrow>CLI</x-eyebrow></x-slot>

    <x-slot name="description">
        A command-line tool for interacting with content-md sites. Fetch pages as an AI agent would, run compliance checks against the spec, and convert content into agent skills.
    </x-slot>

    <x-slot name="extra">
        <div class="space-y-1">
            <p>
                <a href="https://github.com/OneOffTech/contentmd/releases/latest" class="select-none inline-block text-center px-3 py-2 leading-4 font-medium text-zinc-700 bg-white hover:bg-zinc-50 dark:bg-zinc-600 dark:text-white dark:hover:bg-zinc-500 focus:outline-none focus:ring-2 focus:ring-orange-500 whitespace-nowrap transition-button ring-1 ring-zinc-950/10 dark:ring-zinc-950/10 shadow-sm" >
                    Download contentmd
                </a>
            </p>
            <p class="text-sm text-zinc-700">for Windows, Linux and MacOS</p>
        </div>

    </x-slot>
</x-page-hero>




{{-- Browse --}}
<div class="pt-8">
    <x-section>
        <x-slot name="eyebrow"><x-eyebrow>Browse</x-eyebrow></x-slot>

        Fetch pages like an AI agent.

        <x-slot name="subheadline">The default command sends <code class="text-xs bg-zinc-100 dark:bg-zinc-800 px-1.5 py-0.5 rounded">Accept: text/markdown</code> and prints the content-md response. Falls back to HTML on 406. Works with single URLs, lists, or entire sitemaps.</x-slot>
    </x-section>

    <x-container class="mt-4 mb-12">
        <x-card-grid cols="2-lg">
            <div class="flex flex-col gap-4">
                <div class="rounded-lg overflow-hidden" x-data="{ copied: false }" x-bind:data-copied="copied">
                    <div class="bg-zinc-800 dark:bg-zinc-900 px-4 py-2 flex items-center justify-between gap-2">
                        <span class="text-xs text-zinc-400">Single page</span>
                        <button type="button" @click="navigator.clipboard.writeText($refs.code.innerText.trim()).then(() => { copied = true; setTimeout(() => copied = false, 2000) })" class="text-zinc-500 hover:text-zinc-300 transition-colors" aria-label="Copy to clipboard">
                            <svg x-show="!copied" class="size-3.5" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                            <svg x-show="copied" class="size-3.5 text-emerald-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd"></path></svg>
                        </button>
                    </div>
                    <div class="bg-zinc-950 p-4 font-mono text-xs leading-relaxed text-zinc-100 overflow-x-auto" x-ref="code">
                        contentmd https://contentmd.org/specification/
                    </div>
                </div>

                <div class="rounded-lg overflow-hidden" x-data="{ copied: false }" x-bind:data-copied="copied">
                    <div class="bg-zinc-800 dark:bg-zinc-900 px-4 py-2 flex items-center justify-between gap-2">
                        <span class="text-xs text-zinc-400">Multiple pages → folder</span>
                        <button type="button" @click="navigator.clipboard.writeText($refs.code.innerText.trim()).then(() => { copied = true; setTimeout(() => copied = false, 2000) })" class="text-zinc-500 hover:text-zinc-300 transition-colors" aria-label="Copy to clipboard">
                            <svg x-show="!copied" class="size-3.5" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                            <svg x-show="copied" class="size-3.5 text-emerald-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd"></path></svg>
                        </button>
                    </div>
                    <div class="bg-zinc-950 p-4 font-mono text-xs leading-relaxed text-zinc-100 overflow-x-auto" x-ref="code">
                        contentmd <span class="text-yellow-300">--output</span> ./pages \<br>
                        &nbsp;&nbsp;https://contentmd.org/specification/ \<br>
                        &nbsp;&nbsp;https://contentmd.org/consumers/
                    </div>
                </div>

                <div class="rounded-lg overflow-hidden" x-data="{ copied: false }" x-bind:data-copied="copied">
                    <div class="bg-zinc-800 dark:bg-zinc-900 px-4 py-2 flex items-center justify-between gap-2">
                        <span class="text-xs text-zinc-400">Full site via sitemap</span>
                        <button type="button" @click="navigator.clipboard.writeText($refs.code.innerText.trim()).then(() => { copied = true; setTimeout(() => copied = false, 2000) })" class="text-zinc-500 hover:text-zinc-300 transition-colors" aria-label="Copy to clipboard">
                            <svg x-show="!copied" class="size-3.5" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                            <svg x-show="copied" class="size-3.5 text-emerald-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd"></path></svg>
                        </button>
                    </div>
                    <div class="bg-zinc-950 p-4 font-mono text-xs leading-relaxed text-zinc-100 overflow-x-auto" x-ref="code">
                        contentmd <span class="text-yellow-300">--sitemap</span> <span class="text-yellow-300">--output</span> ./pages \<br>
                        &nbsp;&nbsp;https://contentmd.org
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-4">
                <div class="overflow-x-auto agent:hidden">
                    <table class="w-full text-sm border-collapse">
                        <thead>
                            <tr class="border-b border-zinc-300 dark:border-zinc-600">
                                <th class="text-left py-3 pr-6 font-semibold text-zinc-950 dark:text-white">Flag</th>
                                <th class="text-left py-3 font-semibold text-zinc-950 dark:text-white">Description</th>
                            </tr>
                        </thead>
                        <tbody class="text-zinc-700 dark:text-zinc-300">
                            <tr class="border-b border-zinc-300/60 dark:border-zinc-600/60">
                                <td class="py-3 pr-6"><code class="text-xs bg-zinc-100 dark:bg-zinc-800 px-1.5 py-0.5 rounded">--agent</code></td>
                                <td class="py-3 text-xs">Raw markdown only, no size/token header</td>
                            </tr>
                            <tr class="border-b border-zinc-300/60 dark:border-zinc-600/60">
                                <td class="py-3 pr-6"><code class="text-xs bg-zinc-100 dark:bg-zinc-800 px-1.5 py-0.5 rounded">--sitemap</code></td>
                                <td class="py-3 text-xs">Fetch <code class="text-xs bg-zinc-100 dark:bg-zinc-800 px-1.5 py-0.5 rounded">/sitemap.xml</code> and iterate every URL in it</td>
                            </tr>
                            <tr class="border-b border-zinc-300/60 dark:border-zinc-600/60">
                                <td class="py-3 pr-6"><code class="text-xs bg-zinc-100 dark:bg-zinc-800 px-1.5 py-0.5 rounded">--output &lt;folder&gt;</code></td>
                                <td class="py-3 text-xs">Save each response as a <code class="text-xs bg-zinc-100 dark:bg-zinc-800 px-1.5 py-0.5 rounded">.md</code> file (required for multiple URLs)</td>
                            </tr>
                            <tr class="border-b border-zinc-300/60 dark:border-zinc-600/60">
                                <td class="py-3 pr-6"><code class="text-xs bg-zinc-100 dark:bg-zinc-800 px-1.5 py-0.5 rounded">--frontmatter-only</code></td>
                                <td class="py-3 text-xs">Send <code class="text-xs bg-zinc-100 dark:bg-zinc-800 px-1.5 py-0.5 rounded">Range: x-frontmatter</code> to fetch only the frontmatter</td>
                            </tr>
                            <tr>
                                <td class="py-3 pr-6"><code class="text-xs bg-zinc-100 dark:bg-zinc-800 px-1.5 py-0.5 rounded">--follow-redirect</code></td>
                                <td class="py-3 text-xs">Follow HTTP redirects (reported as errors by default)</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="hidden agent:block prose prose-zinc dark:prose-invert max-w-none">
                    <table>
                        <thead>
                            <tr><th>Flag</th><th>Description</th></tr>
                        </thead>
                        <tbody>
                            <tr><td><code>--agent</code></td><td>Raw markdown only, no size/token header</td></tr>
                            <tr><td><code>--sitemap</code></td><td>Fetch /sitemap.xml and iterate every URL in it</td></tr>
                            <tr><td><code>--output &lt;folder&gt;</code></td><td>Save each response as a .md file</td></tr>
                            <tr><td><code>--frontmatter-only</code></td><td>Fetch only the frontmatter via Range header</td></tr>
                            <tr><td><code>--follow-redirect</code></td><td>Follow HTTP redirects</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </x-card-grid>
    </x-container>
</div>


{{-- Validate --}}
<x-section-panel>
    <x-section>
        <x-slot name="eyebrow"><x-eyebrow>Validate</x-eyebrow></x-slot>

        Check compliance against the spec.

        <x-slot name="subheadline">The <code class="text-xs bg-zinc-100 dark:bg-zinc-800 px-1.5 py-0.5 rounded">validate</code> subcommand runs a full compliance report against a URL — content negotiation, caching headers, frontmatter fields, document structure, and more. Every check is rated pass, warn, or fail, with a score from 0 to 100.</x-slot>
    </x-section>

    <x-container class="mt-4 pb-12">
        <x-card-grid cols="2-lg">
            <div class="flex flex-col gap-4">
                <div class="overflow-x-auto agent:hidden">
                    <table class="w-full text-sm border-collapse">
                        <thead>
                            <tr class="border-b border-zinc-300 dark:border-zinc-600">
                                <th class="text-left py-3 pr-6 font-semibold text-zinc-950 dark:text-white">Check</th>
                                <th class="text-left py-3 font-semibold text-zinc-950 dark:text-white">What it verifies</th>
                            </tr>
                        </thead>
                        <tbody class="text-zinc-700 dark:text-zinc-300">
                            <tr class="border-b border-zinc-300/60 dark:border-zinc-600/60">
                                <td class="py-3 pr-6"><code class="text-xs bg-zinc-100 dark:bg-zinc-800 px-1.5 py-0.5 rounded">content-negotiation</code></td>
                                <td class="py-3 text-xs">Server returns <code class="text-xs bg-zinc-100 dark:bg-zinc-800 px-1.5 py-0.5 rounded">text/markdown</code> for <code class="text-xs bg-zinc-100 dark:bg-zinc-800 px-1.5 py-0.5 rounded">Accept: text/markdown</code></td>
                            </tr>
                            <tr class="border-b border-zinc-300/60 dark:border-zinc-600/60">
                                <td class="py-3 pr-6"><code class="text-xs bg-zinc-100 dark:bg-zinc-800 px-1.5 py-0.5 rounded">vary-accept</code></td>
                                <td class="py-3 text-xs">Response includes <code class="text-xs bg-zinc-100 dark:bg-zinc-800 px-1.5 py-0.5 rounded">Vary: Accept</code></td>
                            </tr>
                            <tr class="border-b border-zinc-300/60 dark:border-zinc-600/60">
                                <td class="py-3 pr-6"><code class="text-xs bg-zinc-100 dark:bg-zinc-800 px-1.5 py-0.5 rounded">frontmatter-title</code></td>
                                <td class="py-3 text-xs"><code class="text-xs bg-zinc-100 dark:bg-zinc-800 px-1.5 py-0.5 rounded">title</code> field present and non-empty</td>
                            </tr>
                            <tr class="border-b border-zinc-300/60 dark:border-zinc-600/60">
                                <td class="py-3 pr-6"><code class="text-xs bg-zinc-100 dark:bg-zinc-800 px-1.5 py-0.5 rounded">frontmatter-description</code></td>
                                <td class="py-3 text-xs"><code class="text-xs bg-zinc-100 dark:bg-zinc-800 px-1.5 py-0.5 rounded">description</code> field present and non-empty</td>
                            </tr>
                            <tr class="border-b border-zinc-300/60 dark:border-zinc-600/60">
                                <td class="py-3 pr-6"><code class="text-xs bg-zinc-100 dark:bg-zinc-800 px-1.5 py-0.5 rounded">heading-h1</code></td>
                                <td class="py-3 text-xs">Markdown body starts with an H1</td>
                            </tr>
                            <tr class="border-b border-zinc-300/60 dark:border-zinc-600/60">
                                <td class="py-3 pr-6"><code class="text-xs bg-zinc-100 dark:bg-zinc-800 px-1.5 py-0.5 rounded">frontmatter-tokens</code></td>
                                <td class="py-3 text-xs">Frontmatter is within the ~100 token budget</td>
                            </tr>
                            <tr class="border-b border-zinc-300/60 dark:border-zinc-600/60">
                                <td class="py-3 pr-6"><code class="text-xs bg-zinc-100 dark:bg-zinc-800 px-1.5 py-0.5 rounded">range-frontmatter</code></td>
                                <td class="py-3 text-xs"><code class="text-xs bg-zinc-100 dark:bg-zinc-800 px-1.5 py-0.5 rounded">Range: x-frontmatter</code> returns only the frontmatter block</td>
                            </tr>
                            <tr>
                                <td class="py-3 pr-6"><code class="text-xs bg-zinc-100 dark:bg-zinc-800 px-1.5 py-0.5 rounded">title-html-match</code></td>
                                <td class="py-3 text-xs">Frontmatter title matches the HTML <code class="text-xs bg-zinc-100 dark:bg-zinc-800 px-1.5 py-0.5 rounded">&lt;title&gt;</code></td>
                            </tr>
                        </tbody>
                    </table>
                    <p class="mt-3 text-xs text-zinc-600 dark:text-zinc-400">And 9 more checks — heading hierarchy, link headers, robots.txt, sitemap presence, description length, and more.</p>
                </div>

                <div class="hidden agent:block prose prose-zinc dark:prose-invert max-w-none">
                    <table>
                        <thead>
                            <tr><th>Check</th><th>What it verifies</th></tr>
                        </thead>
                        <tbody>
                            <tr><td><code>content-negotiation</code></td><td>Server returns text/markdown for Accept: text/markdown</td></tr>
                            <tr><td><code>vary-accept</code></td><td>Response includes Vary: Accept</td></tr>
                            <tr><td><code>frontmatter-title</code></td><td>title field present and non-empty</td></tr>
                            <tr><td><code>frontmatter-description</code></td><td>description field present and non-empty</td></tr>
                            <tr><td><code>heading-h1</code></td><td>Markdown body starts with an H1</td></tr>
                            <tr><td><code>frontmatter-tokens</code></td><td>Frontmatter within ~100 token budget</td></tr>
                            <tr><td><code>range-frontmatter</code></td><td>Range: x-frontmatter returns only the frontmatter</td></tr>
                            <tr><td><code>title-html-match</code></td><td>Frontmatter title matches HTML title element</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="flex flex-col gap-4">
                <div class="rounded-lg overflow-hidden agent:hidden" x-data="{ copied: false }" x-bind:data-copied="copied">
                    <div class="bg-zinc-800 dark:bg-zinc-900 px-4 py-2 flex items-center justify-between gap-2">
                        <span class="text-xs text-zinc-400">Plain output (default)</span>
                        <button type="button" @click="navigator.clipboard.writeText($refs.code.innerText.trim()).then(() => { copied = true; setTimeout(() => copied = false, 2000) })" class="text-zinc-500 hover:text-zinc-300 transition-colors" aria-label="Copy to clipboard">
                            <svg x-show="!copied" class="size-3.5" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                            <svg x-show="copied" class="size-3.5 text-emerald-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd"></path></svg>
                        </button>
                    </div>
                    <div class="bg-zinc-950 p-4 font-mono text-xs leading-relaxed text-zinc-100 overflow-x-auto" x-ref="code">
<span class="text-zinc-400">contentmd validate https://contentmd.org/specification/</span><br>
<br>
<span class="text-zinc-500">Validating https://contentmd.org/specification/ ...</span><br>
<br>
<span class="text-emerald-400">  ✓</span> <span class="text-zinc-300">content-negotiation</span><br>
<span class="text-emerald-400">  ✓</span> <span class="text-zinc-300">vary-accept</span><br>
<span class="text-emerald-400">  ✓</span> <span class="text-zinc-300">frontmatter-title</span><br>
<span class="text-emerald-400">  ✓</span> <span class="text-zinc-300">frontmatter-description</span><br>
<span class="text-emerald-400">  ✓</span> <span class="text-zinc-300">heading-h1</span><br>
<span class="text-emerald-400">  ✓</span> <span class="text-zinc-300">frontmatter-tokens</span><br>
<span class="text-yellow-400">  !</span> <span class="text-zinc-300">link-header</span>      <span class="text-zinc-500">— Link header not found</span><br>
<span class="text-yellow-400">  !</span> <span class="text-zinc-300">html-alternate-link</span> <span class="text-zinc-500">— rel=alternate not set</span><br>
<br>
<span class="text-zinc-300">Score: <span class="text-emerald-400">87</span>/100</span>
                    </div>
                </div>

                <x-card variant="panel" title="Machine-readable output" size="xs">
                    Use <code class="bg-zinc-200 dark:bg-zinc-700 px-1 rounded">--format json</code> for structured output — useful in CI pipelines or when comparing reports over time.
                    <div class="relative rounded bg-zinc-950 p-3 font-mono text-xs text-zinc-100 mt-3 overflow-x-auto agent:hidden" x-data="{ copied: false }" x-bind:data-copied="copied">
                        <span x-ref="code">contentmd validate <span class="text-yellow-300">--format json</span> https://contentmd.org/</span>
                        <button type="button" @click="navigator.clipboard.writeText($refs.code.innerText.trim()).then(() => { copied = true; setTimeout(() => copied = false, 2000) })" class="absolute top-2 right-2 text-zinc-500 hover:text-zinc-300 transition-colors" aria-label="Copy to clipboard">
                            <svg x-show="!copied" class="size-3" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                            <svg x-show="copied" class="size-3 text-emerald-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd"></path></svg>
                        </button>
                    </div>
                </x-card>

                <x-card variant="panel" title="Snapshot and diff" size="xs">
                    Save a baseline report with <code class="bg-zinc-200 dark:bg-zinc-700 px-1 rounded">--save baseline.json</code> and compare it later. Useful for tracking compliance regressions as a site evolves.
                    <div class="relative rounded bg-zinc-950 p-3 font-mono text-xs text-zinc-100 mt-3 overflow-x-auto agent:hidden" x-data="{ copied: false }" x-bind:data-copied="copied">
                        <span x-ref="code">contentmd validate <span class="text-yellow-300">--save baseline.json</span> https://contentmd.org/</span>
                        <button type="button" @click="navigator.clipboard.writeText($refs.code.innerText.trim()).then(() => { copied = true; setTimeout(() => copied = false, 2000) })" class="absolute top-2 right-2 text-zinc-500 hover:text-zinc-300 transition-colors" aria-label="Copy to clipboard">
                            <svg x-show="!copied" class="size-3" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 00-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                            <svg x-show="copied" class="size-3 text-emerald-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd"></path></svg>
                        </button>
                    </div>
                </x-card>

                <x-card variant="panel" title="CI-friendly Markdown table" size="xs">
                    Use <code class="bg-zinc-200 dark:bg-zinc-700 px-1 rounded">--format markdown</code> to generate a compliance table suitable for posting in pull request comments.
                    <div class="relative rounded bg-zinc-950 p-3 font-mono text-xs text-zinc-100 mt-3 overflow-x-auto agent:hidden" x-data="{ copied: false }" x-bind:data-copied="copied">
                        <span x-ref="code">contentmd validate <span class="text-yellow-300">--format markdown</span> https://contentmd.org/</span>
                        <button type="button" @click="navigator.clipboard.writeText($refs.code.innerText.trim()).then(() => { copied = true; setTimeout(() => copied = false, 2000) })" class="absolute top-2 right-2 text-zinc-500 hover:text-zinc-300 transition-colors" aria-label="Copy to clipboard">
                            <svg x-show="!copied" class="size-3" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                            <svg x-show="copied" class="size-3 text-emerald-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd"></path></svg>
                        </button>
                    </div>
                </x-card>
            </div>
        </x-card-grid>
    </x-container>
</x-section-panel>


{{-- Skill + Agent mode --}}
<div class="pt-8">
    <x-section>
        <x-slot name="eyebrow"><x-eyebrow>More</x-eyebrow></x-slot>

        Skills and agent mode.

        <x-slot name="subheadline">Two more built-in features: convert any content-md page into an agent skill file, and a detection mechanism that lets AI coding agents use the CLI without extra flags.</x-slot>
    </x-section>

    <x-container class="mb-16 mt-4">
        <x-card-grid cols="2-lg">
            <div class="flex flex-col gap-4">
                <div class="flex items-center gap-2 mb-1">
                    <code class="text-xs font-medium px-2 py-0.5 rounded bg-zinc-200 dark:bg-zinc-700 text-zinc-700 dark:text-zinc-300">contentmd skill</code>
                    <span class="text-sm font-semibold text-zinc-950 dark:text-white">Convert to agent skill</span>
                </div>
                <p class="text-sm/7 text-zinc-700 dark:text-zinc-400">Converts a content-md page into an <a href="https://agentskills.io" target="_blank" rel="noopener noreferrer" class="underline hover:text-zinc-950 dark:hover:text-white">Agent Skill</a> (<code class="bg-zinc-200 dark:bg-zinc-700 px-1 rounded text-xs">SKILL.md</code>) — remapping frontmatter fields and adding a <code class="bg-zinc-200 dark:bg-zinc-700 px-1 rounded text-xs">source:</code> URL reference.</p>
                <div class="rounded-lg overflow-hidden agent:hidden" x-data="{ copied: false }" x-bind:data-copied="copied">
                    <div class="bg-zinc-800 dark:bg-zinc-900 px-4 py-2 flex items-center justify-between gap-2">
                        <span class="text-xs text-zinc-400">Skill command</span>
                        <button type="button" @click="navigator.clipboard.writeText($refs.code.innerText.trim()).then(() => { copied = true; setTimeout(() => copied = false, 2000) })" class="text-zinc-500 hover:text-zinc-300 transition-colors" aria-label="Copy to clipboard">
                            <svg x-show="!copied" class="size-3.5" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                            <svg x-show="copied" class="size-3.5 text-emerald-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd"></path></svg>
                        </button>
                    </div>
                    <div class="bg-zinc-950 p-4 font-mono text-xs leading-relaxed text-zinc-100 overflow-x-auto" x-ref="code">
                        contentmd skill https://contentmd.org/specification/<br>
                        <br>
                        <span class="text-zinc-500"># Write to a file</span><br>
                        contentmd skill <span class="text-yellow-300">--output SKILL.md</span> https://contentmd.org/specification/
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-4">
                <div class="flex items-center gap-2 mb-1">
                    <code class="text-xs font-medium px-2 py-0.5 rounded bg-zinc-200 dark:bg-zinc-700 text-zinc-700 dark:text-zinc-300">--agent</code>
                    <span class="text-sm font-semibold text-zinc-950 dark:text-white">Agent mode</span>
                </div>
                <p class="text-sm/7 text-zinc-700 dark:text-zinc-400">All three commands support <code class="bg-zinc-200 dark:bg-zinc-700 px-1 rounded text-xs">--agent</code>. When active, output is stripped of human-readable formatting: raw markdown for browse, JSON for validate, and a structured object for skill.</p>
                <x-card variant="panel" title="Auto-detected from the environment" size="xs">
                    Agent mode is implied automatically when coding agent environment variables are set — <code class="bg-zinc-200 dark:bg-zinc-700 px-1 rounded">CLAUDE_CODE</code>, <code class="bg-zinc-200 dark:bg-zinc-700 px-1 rounded">CURSOR_AGENT</code>, <code class="bg-zinc-200 dark:bg-zinc-700 px-1 rounded">GEMINI_CLI</code>, <code class="bg-zinc-200 dark:bg-zinc-700 px-1 rounded">CODEX_SANDBOX</code>, and more. No flag needed when running inside an AI coding agent.
                </x-card>
            </div>
        </x-card-grid>
    </x-container>
</div>

<div class="h-16 agent:hidden"></div>

@endsection

@push('markdown')

# contentmd CLI

Command-line tool for browsing and validating content-md formatted web resources — fetch URLs as an AI agent, check compliance, and convert pages to skills.

## Install

Pre-built binaries for Linux, macOS, and Windows are available on [GitHub Releases](https://github.com/OneOffTech/contentmd/releases). No runtime required.

```sh
# Or build from source
cargo install contentmd
```

## Browse

Fetch a URL as an AI agent would — requesting `text/markdown` via content negotiation, falling back to HTML on 406.

```sh
contentmd https://contentmd.org/specification/

# Multiple pages saved to a folder
contentmd --output ./pages https://contentmd.org/specification/ https://contentmd.org/consumers/

# Entire site via sitemap
contentmd --sitemap --output ./pages https://contentmd.org
```

| Flag | Description |
| --- | --- |
| `--agent` | Raw markdown only, no size/token header |
| `--sitemap` | Fetch `/sitemap.xml` and iterate every URL in it |
| `--output <folder>` | Save each response as a `.md` file |
| `--frontmatter-only` | Send `Range: x-frontmatter` to fetch only the frontmatter |
| `--follow-redirect` | Follow HTTP redirects (reported as errors by default) |

## Validate

Check that a URL correctly serves content-md and report compliance.

```sh
contentmd validate https://contentmd.org/specification/
```

Each check is rated **pass**, **warn**, or **fail**. The report includes a **score from 0 to 100**.

| Check | What it verifies |
| --- | --- |
| `content-negotiation` | Server returns `text/markdown` for `Accept: text/markdown` |
| `vary-accept` | Response includes `Vary: Accept` |
| `frontmatter-title` | `title` field present and non-empty |
| `frontmatter-description` | `description` field present and non-empty |
| `heading-h1` | Markdown body starts with an H1 |
| `frontmatter-tokens` | Frontmatter within ~100 token budget |
| `range-frontmatter` | `Range: x-frontmatter` returns only the frontmatter |
| `title-html-match` | Frontmatter title matches HTML `<title>` |

And 9 more checks — heading hierarchy, link headers, robots.txt, sitemap presence, description length, and more.

### Output formats

```sh
# Machine-readable JSON
contentmd validate --format json https://contentmd.org/

# Save a baseline for later comparison
contentmd validate --save baseline.json https://contentmd.org/

# Markdown table for CI comments
contentmd validate --format markdown https://contentmd.org/
```

## Skill

Convert a content-md page into an [Agent Skill](https://agentskills.io) (`SKILL.md`).

```sh
contentmd skill https://contentmd.org/specification/

# Write to a file
contentmd skill --output SKILL.md https://contentmd.org/specification/
```

## Agent mode

All three commands support `--agent`. When active, output is stripped of human-readable formatting — raw markdown for browse, JSON for validate, a structured object for skill.

Agent mode is auto-detected from coding agent environment variables: `CLAUDE_CODE`, `CURSOR_AGENT`, `GEMINI_CLI`, `CODEX_SANDBOX`, and more. No flag needed when running inside an AI coding agent.

@endpush
