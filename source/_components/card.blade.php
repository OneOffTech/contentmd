@php
$badgeClasses = match($badge ?? null) {
    'required'    => 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200',
    'encouraged'  => 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200',
    default       => 'bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400',
};
$bgClasses = match($variant ?? 'default') {
    'panel'   => 'bg-white/50 dark:bg-white/10 ring-1 ring-zinc-300 dark:ring-zinc-500',
    default   => 'bg-zinc-950/2.5 dark:bg-white/5 ring-1 ring-zinc-200 dark:ring-zinc-700',
};
$sizeClass = match($size ?? 'sm') {
    'xs'    => 'text-xs/5',
    default => 'text-sm/5',
};
@endphp
<div class="flex flex-col rounded-lg p-5 {{ $bgClasses }}">
    @if (!empty($title ?? ''))
    <div class="flex items-center gap-2 mb-2">
        @if (!empty($badge ?? ''))
        <span class="inline-block text-xs font-medium px-2 py-0.5 rounded-full {{ $badgeClasses }}">{{ ucfirst($badge) }}</span>
        @endif
        <h3 class="text-sm font-semibold text-zinc-950 dark:text-white">{{ $title }}</h3>
    </div>
    @endif
    <div class="grow {{ $sizeClass }} text-zinc-600 dark:text-zinc-400">{{ $slot }}</div>
    @isset($action)
    <div class="mt-3">{{ $action }}</div>
    @endisset
</div>
