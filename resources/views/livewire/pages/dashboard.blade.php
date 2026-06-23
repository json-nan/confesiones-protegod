<?php

use function Livewire\Volt\layout;
use function Livewire\Volt\{state};

layout('layouts.app');

state([
    'selectedTab' => 'questions-opened',
    'unansweredQuestionsCount' => fn() => \App\Models\Question::open()->count(),
    'answeredCount' => fn() => \App\Models\Question::answered()->count(),
    'ignoredCount' => fn() => \App\Models\Question::ignored()->count(),
]);

?>

<x-slot name="header">
    <div class="flex flex-wrap items-end justify-between gap-3">
        <div>
            <p class="text-sm text-ink-muted">Panel del gato</p>
            <h1 class="font-display font-extrabold text-ink text-2xl sm:text-3xl tracking-tight">Buzón de confesiones</h1>
        </div>
        <div class="flex items-center gap-2.5">
            <div class="panel px-4 py-2.5 text-center">
                <div class="text-xl font-bold text-ink tabular-nums leading-none">{{ $unansweredQuestionsCount }}</div>
                <div class="text-[11px] uppercase tracking-wider text-ink-faint mt-1">Sin responder</div>
            </div>
            <div class="panel px-4 py-2.5 text-center">
                <div class="text-xl font-bold text-success tabular-nums leading-none">{{ $answeredCount }}</div>
                <div class="text-[11px] uppercase tracking-wider text-ink-faint mt-1">Respondidas</div>
            </div>
            <div class="panel px-4 py-2.5 text-center">
                <div class="text-xl font-bold text-ink-muted tabular-nums leading-none">{{ $ignoredCount }}</div>
                <div class="text-[11px] uppercase tracking-wider text-ink-faint mt-1">Ignoradas</div>
            </div>
        </div>
    </div>
</x-slot>

<div class="py-8">
    <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
        <x-mary-tabs
            wire:model="selectedTab"
            active-class="!border-b-brand !text-ink"
            label-class="!inline-flex items-center gap-2 font-semibold text-sm text-ink-muted hover:text-ink px-4 py-3 border-b-2 border-transparent whitespace-nowrap"
            label-div-class="flex gap-1 border-b border-line overflow-x-auto">
            <x-mary-tab name="questions-opened" label="Sin responder">
                <livewire:components.opened-questions-tab />
            </x-mary-tab>
            <x-mary-tab name="questions-answered" label="Respondidas">
                <livewire:components.answered-questions-tab />
            </x-mary-tab>
            <x-mary-tab name="questions-ignored" label="Ignoradas">
                <livewire:components.ignored-questions-tab />
            </x-mary-tab>
        </x-mary-tabs>
    </div>
</div>
