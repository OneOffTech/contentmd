<div class="flex flex-col gap-3">
    <div class="counter-industrial text-zinc-300 dark:text-zinc-600 agent:hidden" aria-hidden="true">{{ $number }}</div>
    @if (!empty($label ?? ''))
    <div class="spec-label text-zinc-400 dark:text-zinc-500 -mt-1 mb-0.5 agent:hidden">{{ $label }}</div>
    @endif
    <h3 class="text-sm font-semibold text-zinc-950 dark:text-white">{{ $title }}</h3>
    <div class="text-sm/7 text-zinc-700 dark:text-zinc-400">{{ $slot }}</div>
</div>
