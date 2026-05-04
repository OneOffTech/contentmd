<x-container class="mt-4 mb-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start agent:flex agent:flex-col">

            <div class="rounded-lg overflow-hidden agent:hidden shadow-2xl">
                <div class="bg-zinc-800 dark:bg-zinc-900 px-4 py-2 flex items-center gap-2">
                    <span class="text-xs text-zinc-400">article.md</span>
                </div>
                <div class="bg-zinc-950 dark:bg-zinc-900 p-5 font-mono text-xs leading-relaxed text-zinc-100 overflow-x-auto">
<span class="text-zinc-500">---</span><br>
<span class="text-purple-400">title:</span>       <span class="text-yellow-300">Introducing Content-md</span><br>
<span class="text-purple-400">description:</span> <span class="text-yellow-300">AI agents should be first-class visitors,</span><br>
<span class="text-yellow-300">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; let's give them a tailored experience.</span><br>
<span class="text-purple-400">date:</span>        <span class="text-yellow-300">2026-04-29</span><br>
<span class="text-purple-400">author:</span>      <span class="text-yellow-300">Alessio</span><br>
<span class="text-purple-400">license:</span>     <span class="text-yellow-300">CC-BY-4.0</span><br>
<span class="text-zinc-500">---</span><br>
<br>
<span class="text-white"># Introducing Content-md</span><br>
<br>
<span class="text-zinc-300">AI Agents are increasingly browsing the web on behalf</span><br>
<span class="text-zinc-300">of humans. The web was built with humans in mind that</span><br>
<span class="text-zinc-300">demand quality and pleasant interaction. Agents go</span><br>
<span class="text-zinc-300">straight to the point and prefer a more structured approach.</span><br>
<br>
<span class="text-white">## The Problem</span><br>
<br>
<span class="text-zinc-300">Converting complex HTML pages with navigation, ads,</span><br>
<span class="text-zinc-300">and JavaScript into LLM-friendly plain text is both</span><br>
<span class="text-zinc-300">difficult and imprecise.</span>
                </div>
            </div>

            <div class="hidden agent:block prose prose-zinc dark:prose-invert max-w-none">
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
                    <p class="text-sm font-semibold text-zinc-950 dark:text-white mb-2">Frontmatter</p>
                    <p class="text-sm/7 text-zinc-700 dark:text-zinc-400">Serves as an introductory summary — ~100 tokens, ~540 characters. AI agents read this first to decide if the full document is relevant before fetching it. Functions as a lightweight preflighted index.</p>
                </div>
                <div class="border-t border-zinc-200 dark:border-zinc-700 pt-8">
                    <span class="inline-block text-xs font-medium px-2 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-900 text-emerald-800 dark:text-emerald-200 mb-3">MD</span>
                    <p class="text-sm font-semibold text-zinc-950 dark:text-white mb-2">Markdown body</p>
                    <p class="text-sm/7 text-zinc-700 dark:text-zinc-400">CommonMark or GitHub-flavored Markdown. Must open with a first-level heading. Prefer text over images — link images and include alternate text. Preserve document hierarchy starting from level two.</p>
                </div>
            </div>

        </div>
    </x-container>

