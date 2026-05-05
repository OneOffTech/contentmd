<div class="relative">
    <x-container class="mb-6 {{ $inner_classes ?? '' }}">
        <div class="flex flex-col gap-6">
            <div class="flex flex-col items-start gap-6">
                {{ $eyebrow ?? '' }}
                <h2 class="text-zinc-950 dark:text-white font-black text-3xl m-0 leading-[1.05] tracking-tight max-w-5xl agent:max-w-none">{{ $slot }}</h2>
                @if (!empty($subheadline ?? ''))
                <p class="max-w-3xl text-zinc-700 dark:text-zinc-300 text-base/7">
                    {{ $subheadline }}
                </p>
                @endif
                {{ $cta ?? '' }}
            </div>
        </div>
    </x-container>
</div>
