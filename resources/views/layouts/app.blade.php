<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="protegod">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'El gato confesoso') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-ink antialiased bg-app min-h-screen">
    <div class="min-h-screen flex flex-col">
        <livewire:layout.navigation />

        @if (isset($header))
            <header class="border-b border-line bg-app/80 backdrop-blur sticky top-0 z-30">
                <div class="max-w-5xl mx-auto py-5 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <main class="flex-1">
            {{ $slot }}
        </main>

        <footer class="border-t border-line py-6 mt-10">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-2 text-sm text-ink-faint">
                <span>{{ config('app.name', 'El gato confesoso') }} &middot; confesiones anónimas</span>
                <span>Hecho con cariño &amp; misterio</span>
            </div>
        </footer>
    </div>

    <x-mary-toast />
</body>

</html>
