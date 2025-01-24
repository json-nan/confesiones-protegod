<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

//Route::view('/', 'welcome');

Volt::route('/', 'pages.home')
    ->name('home');

Volt::route('dashboard', 'pages.dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__ . '/auth.php';
