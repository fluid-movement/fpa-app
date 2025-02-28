<x-layouts.app.sidebar>
    @isset($banner)
        {{ $banner }}
    @endisset
    <flux:main>
        {{ $slot }}
    </flux:main>
</x-layouts.app.sidebar>
