@props(['title', 'variant' => null, 'level' => 'h3'])

<div {{ $attributes->class(['rounded-lg p-2 agent:p-0', 'bg-mauve-950/2.5 dark:bg-white/5' => is_null($variant), 'bg-white/50 dark:bg-white/10' => $variant === 'invert']) }}>
    <div class="h-full flex flex-col agent:flex-col">
        <div class="flex flex-col gap-4 p-6 lg:p-6 agent:p-0">
            <{{$level}} class="text-balance text-base/8 font-medium text-mauve-950 dark:text-white">{{ $title }}</{{$level}}>
            <div class="mt-2 gap-4 text-sm/7 text-mauve-700 dark:text-mauve-300">
                {{ $slot }}
            </div>
            {{ $cta ?? null }}
        </div>
    </div>
</div>
