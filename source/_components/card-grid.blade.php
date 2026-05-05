@php
$gridClass = match($cols ?? '2') {
    '3'       => 'grid-cols-1 md:grid-cols-3 gap-6',
    '3-sm'    => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4',
    '2-lg'    => 'grid-cols-1 lg:grid-cols-2 gap-6',
    default   => 'grid-cols-1 sm:grid-cols-2 gap-4',
};
@endphp
<div {{ $attributes->merge(['class' => "grid {$gridClass} agent:flex agent:flex-col"]) }}>
    {{ $slot }}
</div>
