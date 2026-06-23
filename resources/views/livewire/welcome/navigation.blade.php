@php
    $appName = config('app.name', 'El gato confesoso');
@endphp

<nav class="sticky top-0 z-40 border-b border-line bg-app/70 backdrop-blur-md">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <a href="{{ route('home') }}" wire:navigate class="flex items-center gap-2.5 group">
                <span class="grid place-items-center w-9 h-9 rounded-xl bg-brand text-white shadow-glow transition-transform group-hover:scale-105">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path d="M4 5.5C4 4.67 4.67 4 5.5 4h13c.83 0 1.5.67 1.5 1.5v9c0 .83-.67 1.5-1.5 1.5H12l-4 3.5V16H5.5A1.5 1.5 0 0 1 4 14.5v-9Z" fill="currentColor"/>
                        <circle cx="9" cy="10" r="1.1" fill="#1c1630"/>
                        <circle cx="15" cy="10" r="1.1" fill="#1c1630"/>
                    </svg>
                </span>
                <span class="font-display font-extrabold tracking-tight text-ink text-lg leading-none">
                    {{ $appName }}
                </span>
            </a>

            <div class="flex items-center gap-1.5">
                @auth
                    <a href="{{ url('/dashboard') }}" wire:navigate
                       class="inline-flex items-center gap-1.5 rounded-lg px-3.5 py-2 text-sm font-semibold text-ink hover:bg-panel transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 8.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 8.25 20.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25A2.25 2.25 0 0 1 13.5 8.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z"/>
                        </svg>
                        Dashboard
                    </a>
                    <a href="{{ route('home') }}" wire:navigate
                       class="inline-flex items-center rounded-lg px-3.5 py-2 text-sm font-semibold bg-brand text-white hover:bg-brand-hover transition-colors">
                        Confesar
                    </a>
                @else
{{--                    @if (Route::has('login'))--}}
{{--                        <a href="{{ route('login') }}" wire:navigate--}}
{{--                           class="inline-flex items-center rounded-lg px-3.5 py-2 text-sm font-semibold text-ink-muted hover:text-ink hover:bg-panel transition-colors">--}}
{{--                            Iniciar sesión--}}
{{--                        </a>--}}
{{--                    @endif--}}
{{--                    @if (Route::has('register'))--}}
{{--                        <a href="{{ route('register') }}" wire:navigate--}}
{{--                           class="inline-flex items-center rounded-lg px-3.5 py-2 text-sm font-semibold bg-brand text-white hover:bg-brand-hover transition-colors">--}}
{{--                            Crear cuenta--}}
{{--                        </a>--}}
{{--                    @endif--}}
                    <a href="#confesar"
                       class="inline-flex items-center gap-1.5 rounded-lg px-3.5 py-2 text-sm font-semibold border border-line-strong text-ink hover:bg-panel transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 20.25c4.97 0 9-3.694 9-8.25s-4.03-8.25-9-8.25S3 7.444 3 12c0 2.104.859 4.023 2.273 5.48.432.447.74 1.04.586 1.641a4.483 4.483 0 0 1-.923 1.785A5.969 5.969 0 0 0 6 21c1.282 0 2.47-.402 3.445-1.087.81.22 1.668.337 2.555.337Z"/>
                        </svg>
                        Escribir confesión
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>
