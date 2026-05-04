<x-button
    x-data="{ copied: false }" x-bind:data-copied="copied"
    @click="navigator.clipboard.writeText(document.getElementById('markdown-template').content.textContent.trim()).then(() => { copied = true; setTimeout(() => copied = false, 2000) })"
    {{-- class="grid-cols-2 grid-flow-col-dense gap-2" --}}
    class="gap-2"
    aria-label="Copy markdown to clipboard"
    >

    <svg
        data-icon
        class="shrink-0 [:where(&)]:size-5 [[data-copied]_&]:hidden size-5!" 
        aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
    </svg>

    <svg class="shrink-0 [:where(&)]:size-5 hidden text-accent-500 [[data-copied]_&]:block size-5!" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
    <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd"></path>
    </svg>

    <span class="grow relative">
        <span class="[[data-copied]_&]:opacity-0 transition-[opacity] duration-75 ease-in-out will-change-[opacity]" x-bind:aria-hidden="copied">Copy text</span>
        <span class="absolute agent:relative left-0 opacity-0 transition-[opacity] duration-100 ease-in-out will-change-[opacity] [[data-copied]_&]:opacity-100" x-bind:aria-hidden="!copied" x-bind:aria-live="copied">Copied!</span>
    </span>
</x-button>
