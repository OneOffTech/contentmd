---
title: content-md for Content Consumers
description: How to serve content-md from your server — content negotiation, HTTP headers, range requests, caching, and implementation guidance for common platforms.
card:
  template: '_og.page'
  path: /assets/og/consumers.png
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
                        <x-eyebrow>For Content Consumers</x-eyebrow>
                        <h1 class="text-balance text-zinc-950 dark:text-white font-black text-5xl sm:text-6xl m-0 leading-[1.05] tracking-tight max-w-4xl agent:max-w-none">Serve content-md from your server.</h1>
                        <p class="flex max-w-2xl flex-col gap-4 text-zinc-600 dark:text-zinc-300 text-base/7">
                            Implementation guide for serving content-md — content negotiation, response headers, range requests, caching strategy, and patterns for common server stacks.
                        </p>
                    </div>
                </div>
            </x-container>
        </div>
    </div>


    {{-- How it works --}}
    <div class="relative bg-zinc-200 dark:bg-zinc-700 pt-8 overflow-hidden">

        <x-section>
            <x-slot name="eyebrow"><x-eyebrow>How It Works</x-eyebrow></x-slot>

            Content negotiation.

            <x-slot name="subheadline">
                content-md uses the standard HTTP <code class="text-xs bg-zinc-100 dark:bg-zinc-800 px-1.5 py-0.5 rounded">Accept</code> header — a mechanism browsers and servers have used for decades. AI agents that know the protocol get the optimized version. Others get HTML as usual.
            </x-slot>
        </x-section>

        <x-container class="mt-8 pb-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 agent:flex agent:flex-col">

                <div class="flex flex-col gap-3">
                    <div class="counter-industrial text-zinc-300 dark:text-zinc-600 agent:hidden" aria-hidden="true">01</div>
                    <div class="spec-label text-zinc-400 dark:text-zinc-500 -mt-1 mb-0.5 agent:hidden">Request</div>
                    <h3 class="text-sm font-semibold text-zinc-950 dark:text-white">AI Agent requests</h3>
                    <p class="text-sm/7 text-zinc-700 dark:text-zinc-400">The AI agent sends an HTTP request with <code class="text-xs bg-zinc-100 dark:bg-zinc-800 px-1.5 py-0.5 rounded">text/markdown</code> as its highest preference in the <code class="text-xs bg-zinc-100 dark:bg-zinc-800 px-1.5 py-0.5 rounded">Accept</code> header.</p>
                    <div class="rounded bg-zinc-950 dark:bg-zinc-900 p-3 font-mono text-xs text-zinc-100 overflow-x-auto agent:hidden">
                        Accept: text/markdown, text/html;q=0.8
                    </div>
                </div>

                <div class="flex flex-col gap-3">
                    <div class="counter-industrial text-zinc-300 dark:text-zinc-600 agent:hidden" aria-hidden="true">02</div>
                    <div class="spec-label text-zinc-400 dark:text-zinc-500 -mt-1 mb-0.5 agent:hidden">Negotiate</div>
                    <h3 class="text-sm font-semibold text-zinc-950 dark:text-white">Server negotiates</h3>
                    <p class="text-sm/7 text-zinc-700 dark:text-zinc-400">The server detects the preference and checks if a content-md variant is available for the requested resource.</p>
                    <div class="rounded bg-zinc-950 dark:bg-zinc-900 p-3 font-mono text-xs text-zinc-100 overflow-x-auto agent:hidden">
                        Content-Type: text/markdown
                    </div>
                </div>

                <div class="flex flex-col gap-3">
                    <div class="counter-industrial text-zinc-300 dark:text-zinc-600 agent:hidden" aria-hidden="true">03</div>
                    <div class="spec-label text-zinc-400 dark:text-zinc-500 -mt-1 mb-0.5 agent:hidden">Deliver</div>
                    <h3 class="text-sm font-semibold text-zinc-950 dark:text-white">Delivers content-md</h3>
                    <p class="text-sm/7 text-zinc-700 dark:text-zinc-400">YAML frontmatter + Markdown body is returned with standard caching headers. Start with a 7-day expiry.</p>
                    <div class="rounded bg-zinc-950 dark:bg-zinc-900 p-3 font-mono text-xs text-zinc-100 overflow-x-auto agent:hidden">
                        Cache-Control: max-age=604800
                    </div>
                </div>

            </div>


        </x-container>
    </div>


    {{-- Response headers --}}
    <div class="pt-8">

        <x-section>
            <x-slot name="eyebrow"><x-eyebrow>Response Headers</x-eyebrow></x-slot>

            Signal support correctly.

            <x-slot name="subheadline">A correct content-md response requires a small set of HTTP headers. Getting these right ensures agents can discover, cache, and request content-md reliably.</x-slot>
        </x-section>

        <x-container class="mt-4 mb-12">
            <div class="overflow-x-auto agent:hidden">
                <table class="w-full text-sm border-collapse">
                    <thead>
                        <tr class="border-b border-zinc-300 dark:border-zinc-600">
                            <th class="text-left py-3 pr-6 font-semibold text-zinc-950 dark:text-white">Header</th>
                            <th class="text-left py-3 pr-6 font-semibold text-zinc-950 dark:text-white w-28">Required</th>
                            <th class="text-left py-3 font-semibold text-zinc-950 dark:text-white">Guidance</th>
                        </tr>
                    </thead>
                    <tbody class="text-zinc-700 dark:text-zinc-300">
                        <tr class="border-b border-zinc-300/60 dark:border-zinc-600/60">
                            <td class="py-3 pr-6"><code class="text-xs bg-zinc-100 dark:bg-zinc-800 px-1.5 py-0.5 rounded">Content-Type</code></td>
                            <td class="py-3 pr-6"><span class="inline-block text-xs font-medium px-2 py-0.5 rounded-full bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200">Required</span></td>
                            <td class="py-3">Must be <code class="text-xs bg-zinc-100 dark:bg-zinc-800 px-1.5 py-0.5 rounded">text/markdown; charset=utf-8</code> for all content-md responses.</td>
                        </tr>
                        <tr class="border-b border-zinc-300/60 dark:border-zinc-600/60">
                            <td class="py-3 pr-6"><code class="text-xs bg-zinc-100 dark:bg-zinc-800 px-1.5 py-0.5 rounded">Cache-Control</code></td>
                            <td class="py-3 pr-6"><span class="inline-block text-xs font-medium px-2 py-0.5 rounded-full bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200">Required</span></td>
                            <td class="py-3">Set a meaningful TTL. Start with <code class="text-xs bg-zinc-100 dark:bg-zinc-800 px-1.5 py-0.5 rounded">max-age=604800</code> (7 days) for stable content. Shorter for frequently updated pages.</td>
                        </tr>
                        <tr class="border-b border-zinc-300/60 dark:border-zinc-600/60">
                            <td class="py-3 pr-6"><code class="text-xs bg-zinc-100 dark:bg-zinc-800 px-1.5 py-0.5 rounded">Vary</code></td>
                            <td class="py-3 pr-6"><span class="inline-block text-xs font-medium px-2 py-0.5 rounded-full bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200">Required</span></td>
                            <td class="py-3">Must include <code class="text-xs bg-zinc-100 dark:bg-zinc-800 px-1.5 py-0.5 rounded">Accept</code> so that caches store HTML and content-md variants separately for the same URL.</td>
                        </tr>
                        <tr>
                            <td class="py-3 pr-6"><code class="text-xs bg-zinc-100 dark:bg-zinc-800 px-1.5 py-0.5 rounded">ETag</code></td>
                            <td class="py-3 pr-6"><span class="inline-block text-xs font-medium px-2 py-0.5 rounded-full bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400">Recommended</span></td>
                            <td class="py-3">A content hash or revision identifier enables efficient conditional requests (<code class="text-xs bg-zinc-100 dark:bg-zinc-800 px-1.5 py-0.5 rounded">If-None-Match</code>) for re-crawls.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="hidden agent:block prose prose-zinc dark:prose-invert max-w-none">
                <table>
                    <thead>
                        <tr><th>Header</th><th>Required</th><th>Guidance</th></tr>
                    </thead>
                    <tbody>
                        <tr><td><code>Content-Type</code></td><td>Required</td><td>Must be <code>text/markdown; charset=utf-8</code>.</td></tr>
                        <tr><td><code>Cache-Control</code></td><td>Required</td><td>Set a meaningful TTL. Start with <code>max-age=604800</code> (7 days).</td></tr>
                        <tr><td><code>Vary</code></td><td>Required</td><td>Must include <code>Accept</code> so caches store HTML and content-md variants separately.</td></tr>
                        <tr><td><code>ETag</code></td><td>Recommended</td><td>Enables efficient conditional requests for re-crawls.</td></tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-6 rounded-lg overflow-hidden agent:hidden">
                <div class="bg-zinc-800 dark:bg-zinc-900 px-4 py-2 flex items-center gap-2">
                    <span class="text-xs text-zinc-400">Full response headers example</span>
                </div>
                <div class="bg-zinc-950 p-5 font-mono text-xs leading-relaxed text-zinc-100 overflow-x-auto">
<span class="text-zinc-500">HTTP/1.1 200 OK</span><br>
<span class="text-purple-400">Content-Type:</span>  <span class="text-yellow-300">text/markdown; charset=utf-8</span><br>
<span class="text-purple-400">Cache-Control:</span> <span class="text-yellow-300">max-age=604800</span><br>
<span class="text-purple-400">Vary:</span>          <span class="text-yellow-300">Accept</span><br>
<span class="text-purple-400">Accept-Ranges:</span> <span class="text-yellow-300">x-frontmatter, bytes</span><br>
<span class="text-purple-400">ETag:</span>          <span class="text-yellow-300">"a3f8c2d1"</span>
                </div>
            </div>
        </x-container>
    </div>


    {{-- Implementation patterns --}}
    <div class="bg-zinc-200 dark:bg-zinc-700 pt-8">

        <x-section>
            <x-slot name="eyebrow"><x-eyebrow>Implementation</x-eyebrow></x-slot>

            Common patterns.

            <x-slot name="subheadline">content-md works with any server that can inspect request headers and return different responses. The pattern is the same across frameworks — check the Accept header, serve the right variant.</x-slot>
        </x-section>

        <x-container class="pb-12 mt-4">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 agent:flex agent:flex-col">

                <div>
                    <div class="rounded-lg overflow-hidden">
                        <div class="bg-zinc-800 dark:bg-zinc-900 px-4 py-2 flex items-center gap-2">
                            <span class="text-xs text-zinc-400">Generic — pseudo-code</span>
                        </div>
                        <div class="bg-zinc-950 p-5 font-mono text-xs leading-relaxed text-zinc-100 overflow-x-auto">
<span class="text-zinc-500">// Check if the client prefers text/markdown</span><br>
<span class="text-blue-400">if</span> request.accept.prefers(<span class="text-yellow-300">"text/markdown"</span>) {<br>
&nbsp;&nbsp;<span class="text-blue-400">if</span> markdownVariantExists(request.path) {<br>
&nbsp;&nbsp;&nbsp;&nbsp;response.setHeader(<span class="text-yellow-300">"Content-Type"</span>, <span class="text-yellow-300">"text/markdown; charset=utf-8"</span>)<br>
&nbsp;&nbsp;&nbsp;&nbsp;response.setHeader(<span class="text-yellow-300">"Cache-Control"</span>, <span class="text-yellow-300">"max-age=604800"</span>)<br>
&nbsp;&nbsp;&nbsp;&nbsp;response.setHeader(<span class="text-yellow-300">"Vary"</span>, <span class="text-yellow-300">"Accept"</span>)<br>
&nbsp;&nbsp;&nbsp;&nbsp;<span class="text-blue-400">return</span> markdownVariant(request.path)<br>
&nbsp;&nbsp;}<br>
}<br>
<span class="text-zinc-500">// Fall through to HTML response as normal</span>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-4">

                    <div class="rounded-lg p-5 bg-white/50 dark:bg-white/10 ring-1 ring-zinc-300 dark:ring-zinc-500">
                        <h3 class="text-sm font-semibold text-zinc-950 dark:text-white mb-2">Pre-generate, don't convert on the fly</h3>
                        <p class="text-xs/5 text-zinc-600 dark:text-zinc-400">Generate the <code class="bg-zinc-200 dark:bg-zinc-700 px-1 rounded">.md</code> variant at publish time and store it alongside the HTML. Serving a static file is fast, predictable, and cacheable. Live HTML-to-Markdown conversion is fragile and slow.</p>
                    </div>

                    <div class="rounded-lg p-5 bg-white/50 dark:bg-white/10 ring-1 ring-zinc-300 dark:ring-zinc-500">
                        <h3 class="text-sm font-semibold text-zinc-950 dark:text-white mb-2">Return 406 when no variant is available</h3>
                        <p class="text-xs/5 text-zinc-600 dark:text-zinc-400">If the agent requests <code class="bg-zinc-200 dark:bg-zinc-700 px-1 rounded">text/markdown</code> only (no HTML fallback) and no variant exists, return <code class="bg-zinc-200 dark:bg-zinc-700 px-1 rounded">406 Not Acceptable</code>. If the agent listed HTML as a fallback, serve HTML instead.</p>
                    </div>

                    <div class="rounded-lg p-5 bg-white/50 dark:bg-white/10 ring-1 ring-zinc-300 dark:ring-zinc-500">
                        <h3 class="text-sm font-semibold text-zinc-950 dark:text-white mb-2">The Vary header is non-optional</h3>
                        <p class="text-xs/5 text-zinc-600 dark:text-zinc-400">Without <code class="bg-zinc-200 dark:bg-zinc-700 px-1 rounded">Vary: Accept</code>, a CDN or proxy may serve a cached Markdown response to a browser, or vice versa. This is a common misconfiguration that breaks the experience for one audience or the other.</p>
                    </div>

                </div>

            </div>
        </x-container>
    </div>


    {{-- Caching guidance --}}
    <div class="pt-8">

        <x-section>
            <x-slot name="eyebrow"><x-eyebrow>Caching</x-eyebrow></x-slot>

            Content-md is cache-friendly by design.

            <x-slot name="subheadline">Text-only, no session state, no personalisation. Content-md responses are ideal CDN candidates. A well-cached content-md document costs your origin server nothing at scale.</x-slot>
        </x-section>

        <x-container class="mb-16 mt-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 agent:flex agent:flex-col">

                <div class="rounded-lg p-5 bg-zinc-950/2.5 dark:bg-white/5 ring-1 ring-zinc-200 dark:ring-zinc-700">
                    <h3 class="text-sm font-semibold text-zinc-950 dark:text-white mb-2">Stable content</h3>
                    <p class="text-xs/5 text-zinc-600 dark:text-zinc-400 mb-3">Articles, documentation pages, reference content that rarely changes.</p>
                    <div class="rounded bg-zinc-950 p-3 font-mono text-xs text-zinc-100">
                        Cache-Control: max-age=604800
                    </div>
                    <p class="mt-2 text-xs text-zinc-500 dark:text-zinc-400">7 days. Safe for most editorial content.</p>
                </div>

                <div class="rounded-lg p-5 bg-zinc-950/2.5 dark:bg-white/5 ring-1 ring-zinc-200 dark:ring-zinc-700">
                    <h3 class="text-sm font-semibold text-zinc-950 dark:text-white mb-2">Frequently updated</h3>
                    <p class="text-xs/5 text-zinc-600 dark:text-zinc-400 mb-3">News articles, changelogs, pricing pages, or content updated at least weekly.</p>
                    <div class="rounded bg-zinc-950 p-3 font-mono text-xs text-zinc-100">
                        Cache-Control: max-age=3600
                    </div>
                    <p class="mt-2 text-xs text-zinc-500 dark:text-zinc-400">1 hour. Balances freshness and origin load.</p>
                </div>

                <div class="rounded-lg p-5 bg-zinc-950/2.5 dark:bg-white/5 ring-1 ring-zinc-200 dark:ring-zinc-700">
                    <h3 class="text-sm font-semibold text-zinc-950 dark:text-white mb-2">Stale-while-revalidate</h3>
                    <p class="text-xs/5 text-zinc-600 dark:text-zinc-400 mb-3">Serve stale content immediately while refreshing in the background for zero-latency updates.</p>
                    <div class="rounded bg-zinc-950 p-3 font-mono text-xs text-zinc-100">
                        Cache-Control: max-age=3600,<br>&nbsp;stale-while-revalidate=86400
                    </div>
                    <p class="mt-2 text-xs text-zinc-500 dark:text-zinc-400">Recommended for high-traffic pages.</p>
                </div>

            </div>
        </x-container>
    </div>

    <div class="h-16 agent:hidden"></div>

@endsection

@push('markdown')

# content-md for Content Consumers

Implementation guide for serving content-md — content negotiation, response headers, range requests, caching strategy, and patterns for common server stacks.

## How It Works — Content Negotiation

content-md uses the standard HTTP `Accept` header. AI agents that know the protocol get the optimized version. Others get HTML as usual.

**01 — AI Agent requests**: The AI agent sends an HTTP request with `text/markdown` as its highest preference.

```
Accept: text/markdown, text/html;q=0.8
```

**02 — Server negotiates**: The server detects the preference and checks if a content-md variant is available.

```
Content-Type: text/markdown
```

**03 — Delivers content-md**: YAML frontmatter + Markdown body is returned with standard caching headers.

```
Cache-Control: max-age=604800
```
## Response Headers

| Header | Required | Guidance |
| --- | --- | --- |
| Content-Type | Required | Must be `text/markdown; charset=utf-8`. |
| Cache-Control | Required | Set a meaningful TTL. Start with `max-age=604800` (7 days). |
| Vary | Required | Must include `Accept` so caches store HTML and content-md variants separately. |
| ETag | Recommended | Enables efficient conditional requests for re-crawls. |

## Implementation

### Pre-generate, don't convert on the fly

Generate the `.md` variant at publish time and store it alongside the HTML. Serving a static file is fast, predictable, and cacheable.

### Return 406 when no variant is available

If the agent requests `text/markdown` only and no variant exists, return `406 Not Acceptable`. If the agent listed HTML as a fallback, serve HTML instead.

### The Vary header is non-optional

Without `Vary: Accept`, a CDN or proxy may serve a cached Markdown response to a browser, or vice versa.

## Caching

Content-md is cache-friendly by design — text-only, no session state, no personalisation.

- **Stable content**: `Cache-Control: max-age=604800` (7 days)
- **Frequently updated**: `Cache-Control: max-age=3600` (1 hour)
- **Stale-while-revalidate**: `Cache-Control: max-age=3600, stale-while-revalidate=86400`

@endpush
