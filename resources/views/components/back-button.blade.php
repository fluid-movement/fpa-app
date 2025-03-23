@props(['href' => ''])
<flux:button
    icon="chevron-left"
    href="{{$href}}"
    {{ $attributes->class(['mb-8']) }}
>
    Back
</flux:button>
