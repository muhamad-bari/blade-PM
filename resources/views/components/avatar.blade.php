@props(['src' => null, 'alt' => 'Avatar', 'size' => 'md'])

@php
    $sizes = [
        'sm' => 'h-8 w-8',
        'md' => 'h-10 w-10',
        'lg' => 'h-12 w-12',
    ];
    $sizeClass = $sizes[$size] ?? $sizes['md'];
@endphp

@if($src)
    <img src="{{ $src }}" alt="{{ $alt }}" {{ $attributes->merge(['class' => "$sizeClass rounded-full object-cover"]) }}>
@else
    <div {{ $attributes->merge(['class' => "$sizeClass rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-500 dark:text-gray-400 font-bold"]) }}>
        {{ substr($alt, 0, 1) }}
    </div>
@endif
