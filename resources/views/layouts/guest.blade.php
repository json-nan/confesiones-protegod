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
    {{-- Ambient backdrop: layered solid panels, no gradients --}}
    <div class="pointer-events-none fixed inset-0 -z-10 overflow-hidden">
        <div class="absolute -top-32 -left-24 w-[36rem] h-[36rem] rounded-full bg-brand/10 blur-3xl"></div>
        <div class="absolute top-1/3 -right-24 w-[30rem] h-[30rem] rounded-full bg-brand/5 blur-3xl"></div>
    </div>

    <div class="min-h-screen flex flex-col">
        <livewire:welcome.navigation />

        <div class="flex-1 flex flex-col sm:justify-center items-center pt-6 sm:pt-0 w-full">
            {{ $slot }}
        </div>
    </div>

    <x-mary-toast />
</body>

</html>
