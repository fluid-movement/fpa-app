<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

    <title>{{ $title ?? 'FPA Event Calendar' }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=work-sans:400,500,600&display=swap" rel="stylesheet"/>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="max-w-7xl mx-auto min-h-screen bg-[url(../images/background.jpg)] bg-no-repeat bg-right-bottom bg-fixed bg-cover">
<flux:sidebar sticky stashable class="bg-zinc-700/80 backdrop-blur-lg md:bg-zinc-700/70 md:backdrop-blur-xs">
    <flux:sidebar.toggle class="lg:hidden" icon="x-mark"/>
    <a href="{{ route('home') }}" class="flex items-center space-x-2" wire:navigate>
        <x-app-logo/>
    </a>
    <flux:navlist variant="outline" class="flex flex-col">
        <flux:navlist.group heading="Events" class="grid">
            <flux:navlist.item
                icon="home" :href="route('home')" :current="request()->routeIs('home')"
                wire:navigate>{{ __('Home') }}
            </flux:navlist.item>
            <flux:navlist.item
                icon="calendar-date-range" :href="route('events.index')" :current="request()->routeIs('events.index', 'events.show')"
                wire:navigate>{{ __('Event Calendar') }}
            </flux:navlist.item>
            <flux:navlist.item
                icon="fire" :href="route('rankings')" :current="request()->routeIs('rankings')"
                wire:navigate>{{ __('Rankings') }}
            </flux:navlist.item>
        </flux:navlist.group>
        <flux:navlist.group heading="User" class="grid">
            <flux:navlist.item
                icon="user" :href="route('user.profile')" :current="request()->routeIs('user.profile', 'settings.profile')"
                wire:navigate>{{ __('Profile') }}
            </flux:navlist.item>
            <flux:navlist.item
                icon="bell" :href="route('user.organizing')"
                :current="request()->routeIs('user.organizing', 'events.create', 'events.edit', 'events.admin', 'events.schedule.edit')"
                wire:navigate>{{ __('Event Organizer') }}
            </flux:navlist.item>
            <flux:navlist.item
                icon="heart" :href="route('user.attending')" :current="request()->routeIs('user.attending')"
                wire:navigate>{{ __('Attending') }}
            </flux:navlist.item>
        </flux:navlist.group>
        <flux:navlist.group heading="Admin" class="grid">
            <flux:navlist.item
                icon="chart-pie" :href="route('admin.dashboard')"
                :current="request()->routeIs('admin.dashboard')"
                wire:navigate>{{ __('Dashboard') }}
            </flux:navlist.item>
            <flux:navlist.item
                icon="bars-3-bottom-left" :href="route('admin.index')"
                :current="request()->routeIs('admin.index', 'admin.members.edit')"
                wire:navigate>{{ __('FPA Members') }}
            </flux:navlist.item>
        </flux:navlist.group>
    </flux:navlist>
    <flux:spacer/>
    <div class="flex justify-between">
        <flux:link href="{{route('imprint')}}" class="text-xs text-gray-400">Imprint</flux:link>
        <flux:link href="{{route('contact')}}" class="text-xs text-gray-400">Contact</flux:link>
    </div>
</flux:sidebar>
<!-- Desktop Header -->
<flux:header
    sticky
    class="hidden lg:flex z-20 gap-4 backdrop-blur-xs ">
    @isset($title)
        <!-- <flux:heading class="cursor-default" size="lg">{{$title}}</flux:heading> -->
    @endisset
    <flux:spacer/>
    <flux:button
        class="!text-blue-500"
        variant="ghost"
        icon="plus"
        href="{{ route('events.create') }}"
        wire:navigate>
        {{ __('Create Event') }}
    </flux:button>
    @auth
        <!-- Desktop User Menu -->
        <flux:dropdown position="bottom" align="start">
            <flux:button icon="bars-3" variant="ghost">
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
            <flux:button variant="ghost" icon="user" href="{{ route('login') }}"
                         wire:navigate>{{ __('Log in') }}</flux:button>
        @endguest
    </flux:header>

    <!-- Mobile User Menu -->
    <flux:header sticky class="lg:hidden z-20 bg-zinc-900/70 backdrop-blur-sm border-b border-zinc-700">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-3" inset="left"/>
        @isset($title)
            <flux:heading class="ml-4">{{$title}}</flux:heading>
        @endisset
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
        @guest
            <flux:link href="{{ route('login') }}" class="whitespace-nowrap">{{ __('Log in') }}</flux:link>
        @endguest
    </flux:header>

    <flux:main class="border-l border-zinc-700">
        {{ $slot }}
    </flux:main>
    @persist('toast')
    <flux:toast position="top right"/>
    @endpersist
    @fluxScripts
</div>
</body>
</html>
