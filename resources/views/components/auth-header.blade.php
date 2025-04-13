@props([
    'title',
    'description',
])

<div class="flex w-full flex-col gap-2 text-center">
    <h1 class="text-xl font-medium text-zinc-200">{{ $title }}</h1>
    <p class="text-center text-sm text-zinc-400">{{ $description }}</p>
</div>
