<div class="relative mt-8">
    <x-container class="mb-6 {{ $inner_classes ?? '' }}">
        <div class="flex flex-col gap-4">
            <div class="flex flex-col items-start gap-6">
                {{ $eyebrow ?? '' }}
                <h1 class="text-balance text-mauve-950 dark:text-blue-300 font-extrabold font-serif text-4xl m-0 leading-[1.1111111] tracking-wide max-w-4xl agent:max-w-none">{{ $slot }}</h1>
                <p class="flex max-w-3xl flex-col gap-4 text-mauve-700 dark:text-mauve-300 text-base/7">
                    {{ $subheadline ?? '' }}
                </p>
                {{ $cta ?? '' }}
            </div>
        </div>
    </x-container>
</div>
