@props([
    'number',
    'text',
    'variant' => 'md'
])

@php
    $classes = match ($variant) {
        'md' => 'text-4xl font-bold',
        'sm' => 'text-xl font-bold',
    };
@endphp

<flux:card {{ $attributes }}>
    <div class="{{ $classes }}">{{ $number }}</div>
    <flux:text>{{ $text }}</flux:text>
</flux:card>
