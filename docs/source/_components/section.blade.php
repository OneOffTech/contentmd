<div class="relative">
    <x-container class="mb-6 {{ $inner_classes ?? '' }}">
        <div class="flex flex-col gap-6">
            <div class="flex flex-col items-start gap-6">
                {{ $eyebrow ?? '' }}
                <h2 class="text-mauve-950 dark:text-blue-300 font-extrabold font-serif text-2xl m-0 leading-[1.1111111] max-w-5xl agent:max-w-none">{{ $slot }}</h2>
                @if (!empty($subheadline ?? ''))
                <p class="flex max-w-3xl flex-col gap-4 text-mauve-700 dark:text-mauve-300 text-base/7">
                    {{ $subheadline }}
                </p>
                @endif
                {{ $cta ?? '' }}
            </div>
        </div>
    </x-container>
</div>
