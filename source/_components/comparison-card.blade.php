<div class="flex flex-col rounded-lg p-6 bg-white/50 dark:bg-white/10 ring-1 ring-zinc-300 dark:ring-zinc-500 agent:p-0">
    <div class="flex items-baseline gap-2 mb-3">
        <h3 class="text-base font-semibold text-zinc-950 dark:text-white"><span class="sr-only">Content-md</span> vs. {{ $versus }}</h3>
    </div>
    @if (!empty($href ?? ''))
    <a href="{{ $href }}" target="_blank" rel="noopener noreferrer" class="mb-3 block text-sm transition-button hover:underline">{{ $link ?? $href }} ↗</a>
    @endif
    <div class="grow text-zinc-700 dark:text-zinc-100">
        {{ $slot }}
    </div>
    @isset($footer)
    <p class="text-sm text-zinc-600 dark:text-zinc-100 border-t border-zinc-200 dark:border-zinc-600 pt-3">{{ $footer }}</p>
    @endisset
</div>
