<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="protegod">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'El gato confesoso') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-ink antialiased bg-app min-h-screen">
    <div class="min-h-screen flex flex-col items-center justify-center px-6 text-center">
        <span class="grid place-items-center w-14 h-14 rounded-2xl bg-brand text-white shadow-glow mb-6">
            <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M4 5.5C4 4.67 4.67 4 5.5 4h13c.83 0 1.5.67 1.5 1.5v9c0 .83-.67 1.5-1.5 1.5H12l-4 3.5V16H5.5A1.5 1.5 0 0 1 4 14.5v-9Z" fill="currentColor"/>
                <circle cx="9" cy="10" r="1.1" fill="#1c1630"/>
                <circle cx="15" cy="10" r="1.1" fill="#1c1630"/>
            </svg>
        </span>
        <h1 class="font-display font-extrabold text-ink text-4xl tracking-tight">
            El gato <span class="text-brand-soft">confesoso</span>
        </h1>
        <p class="mt-3 text-ink-muted max-w-md">Confesiones anónimas, respondidas con misterio.</p>
        <a href="{{ route('home') }}" wire:navigate class="mt-6 inline-flex items-center gap-2 rounded-lg px-5 py-2.5 bg-brand text-white font-semibold hover:bg-brand-hover transition-colors">
            Entrar
        </a>
    </div>
</body>
</html>
