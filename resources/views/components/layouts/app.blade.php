<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    @include('partials.head')
</head>
<body class="min-h-screen bg-white dark:bg-zinc-800">
<flux:sidebar sticky stashable class="bg-zinc-50 dark:bg-zinc-900">
    <flux:sidebar.toggle class="lg:hidden" icon="x-mark"/>
    <a href="{{ route('home') }}" class="flex items-center space-x-2" wire:navigate>
        <x-app-logo/>
    </a>
    <flux:navlist variant="outline">
        <flux:navlist.group heading="Events" class="grid">
            <flux:navlist.item
                icon="calendar-date-range" :href="route('events.index')" :current="request()->routeIs('events.index')"
                wire:navigate>{{ __('Upcoming Events') }}
            </flux:navlist.item>
            <flux:navlist.item
                icon="clock" :href="route('events.archive.index')" :current="request()->routeIs('events.archive.index')"
                wire:navigate>{{ __('Past Events') }}
            </flux:navlist.item>
        </flux:navlist.group>
        <flux:navlist.group heading="User" class="grid">
            <flux:navlist.item
                icon="building-storefront" :href="route('user.organizing')"
                :current="request()->routeIs('user.organizing')"
                wire:navigate>{{ __('Organizing') }}
            </flux:navlist.item>
            <flux:navlist.item
                icon="heart" :href="route('user.attending')" :current="request()->routeIs('user.attending')"
                wire:navigate>{{ __('Attending') }}
            </flux:navlist.item>
        </flux:navlist.group>
    </flux:navlist>
    <flux:spacer/>
</flux:sidebar>
<flux:header
    sticky
    class="hidden lg:flex gap-8 bg-zinc-50 dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700">
    @isset($title)
        <flux:heading size="lg">{{$title}}</flux:heading>
    @endisset
    <flux:spacer/>
    <flux:navlist variant="outline">
        <flux:navlist.item icon="plus" href="{{ route('events.create') }}" wire:navigate>
            {{ __('Create Event') }}
        </flux:navlist.item>
    </flux:navlist>
    @auth
        <!-- Desktop User Menu -->
        <flux:dropdown position="bottom" align="start">
            <flux:button icon-trailing="chevrons-up-down" variant="ghost">
                {{ auth()->user()->name }}
            </flux:button>
            <flux:menu class="w-[220px]">
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                            <div class="grid flex-1 text-left text-sm leading-tight">
                                <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator/>

                <flux:menu.radio.group>
                    <flux:menu.item href="/settings/profile" icon="cog"
                                    wire:navigate>{{ __('Settings') }}</flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator/>

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                        {{ __('Log Out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    @endauth
    @guest
        <div class="flex justify-center">
            <a href="{{ route('login') }}" class="btn btn-primary">{{ __('Log in') }}</a>
        </div>
    @endguest
</flux:header>
<!-- Mobile User Menu -->
<flux:header class="lg:hidden">
    <flux:sidebar.toggle class="lg:hidden" icon="bars-3" inset="left"/>

    <flux:spacer/>

    @auth
        <flux:dropdown position="top" align="end">
            <flux:profile
                :initials="auth()->user()->initials()"
                icon-trailing="chevron-down"
            />

            <flux:menu>
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                            <div class="grid flex-1 text-left text-sm leading-tight">
                                <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator/>

                <flux:menu.radio.group>
                    <flux:menu.item href="/settings/profile" icon="cog" wire:navigate>Settings</flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator/>

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                        {{ __('Log Out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    @endauth
</flux:header>

<flux:main class="border-l border-zinc-200 dark:border-zinc-700">
    @isset($banner)
        {{ $banner }}
    @endisset
    {{ $slot }}
</flux:main>
@persist('toast')
<flux:toast position="top right"/>
@endpersist
@fluxScripts
</body>
</html>
