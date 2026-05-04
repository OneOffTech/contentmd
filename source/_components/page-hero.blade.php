<div class="relative overflow-hidden agent:contents grid grid-cols-1 grid-rows-1">

    <div class="col-start-1 row-start-1 grid grid-cols-5 grid-flow-row-dense" aria-hidden="true">
        <div class="col-start-3 w-52 h-52 hatch text-orange-500/40 dark:text-orange-400/40 pointer-events-none agent:hidden" aria-hidden="true"></div>
        <div class="col-start-4 place-self-end w-24 h-24 hatch text-zinc-500/40 dark:text-zinc-400/40 pointer-events-none agent:hidden" aria-hidden="true"></div>
        {{ $decoration ?? '' }}
    </div>

    <div class="relative mt-8 pb-12 col-start-1 row-start-1">
        <x-container class="mb-6">
            <div class="flex flex-col gap-6">
                <div class="flex flex-col items-start gap-6">
                    {{ $label ?? '' }}
                    <h1 class="text-balance text-zinc-950 dark:text-white font-black text-5xl sm:text-6xl m-0 leading-[1.05] tracking-tight max-w-4xl agent:max-w-none">{{ $slot }}</h1>
                    @if (!empty($description ?? ''))
                    <p class="flex max-w-2xl flex-col gap-4 text-zinc-600 dark:text-zinc-300 text-base/7">
                        {{ $description }}
                    </p>
                    @endif
                    {{ $extra ?? '' }}
                </div>
            </div>
        </x-container>
    </div>
</div>
