<button {{ $attributes->merge(['type' => 'button'])->class(['select-none inline-flex items-center px-3 h-[34px] text-sm leading-4  font-medium text-zinc-700 bg-white hover:bg-zinc-50 dark:bg-zinc-600 dark:text-white dark:hover:bg-zinc-500 focus:outline-none focus:ring-2 focus:ring-orange-500 whitespace-nowrap transition-button']) }} >
    {{ $slot }}
</button>