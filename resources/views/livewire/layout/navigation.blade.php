<?php

use App\Livewire\Actions\Logout;

$logout = function (Logout $logout) {
    $logout();

    $this->redirect('/', navigate: true);
};

?>

<nav x-data="{ open: false }" class="sticky top-0 z-40 border-b border-line bg-app/70 backdrop-blur-md">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center gap-6">
                <a href="{{ route('home') }}" wire:navigate class="flex items-center gap-2.5 group">
                    <span class="grid place-items-center w-9 h-9 rounded-xl bg-brand text-white shadow-glow transition-transform group-hover:scale-105">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path d="M4 5.5C4 4.67 4.67 4 5.5 4h13c.83 0 1.5.67 1.5 1.5v9c0 .83-.67 1.5-1.5 1.5H12l-4 3.5V16H5.5A1.5 1.5 0 0 1 4 14.5v-9Z" fill="currentColor"/>
                            <circle cx="9" cy="10" r="1.1" fill="#1c1630"/>
                            <circle cx="15" cy="10" r="1.1" fill="#1c1630"/>
                        </svg>
                    </span>
                    <span class="font-display font-extrabold tracking-tight text-ink text-lg leading-none hidden sm:block">
                        {{ config('app.name', 'El gato confesoso') }}
                    </span>
                </a>

                <div class="hidden sm:flex items-center gap-1">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')" wire:navigate>
                        {{ __('Confesiones') }}
                    </x-nav-link>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:gap-2">
                <x-dropdown align="right" width="56">
                    <x-slot name="trigger">
                        <button type="button"
                                class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg border border-line bg-panel hover:bg-panel-muted hover:border-line-strong text-sm font-medium text-ink transition-colors">
                            <span class="grid place-items-center w-7 h-7 rounded-full bg-brand-tint text-brand-soft text-xs font-bold uppercase"
                                  x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name?.charAt(0)"
                                  x-on:profile-updated.window="name = $event.detail.name"></span>
                            <span class="blur-sm" x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name"
                                  x-on:profile-updated.window="name = $event.detail.name"></span>
                            <svg class="w-4 h-4 text-ink-muted" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.06l3.71-3.83a.75.75 0 1 1 1.08 1.04l-4.25 4.39a.75.75 0 0 1-1.08 0L5.21 8.27a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile')" wire:navigate>
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <button wire:click="logout" class="w-full text-start">
                            <x-dropdown-link>
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </button>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                        class="inline-flex items-center justify-center p-2 rounded-lg text-ink-muted hover:text-ink hover:bg-panel transition-colors"
                        aria-label="Menu">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                              stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1 px-4">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')" wire:navigate>
                {{ __('Confesiones') }}
            </x-responsive-nav-link>
        </div>

        <div class="pt-4 pb-3 border-t border-line px-4" >
            <div class="font-medium text-base text-ink"
                 x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name"
                 x-on:profile-updated.window="name = $event.detail.name"></div>
            <div class="font-medium text-sm text-ink-muted">{{ auth()->user()->email }}</div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile')" wire:navigate>
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <button wire:click="logout" class="w-full text-start">
                    <x-responsive-nav-link>
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </button>
            </div>
        </div>
    </div>
</nav>
