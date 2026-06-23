<?php

use Mary\Traits\Toast;
use function Livewire\Volt\{state, rules, uses};
use Illuminate\Support\Str;

uses([Toast::class]);

state([
    'title' => '',
    'content' => '',
]);

rules([
    'title' => 'required|string|max:200',
    'content' => 'required|string|max:10000',
])->messages([
    'title.required' => 'Este campo es requerido.',
    'content.required' => 'Este campo es requerido.',
]);

$submit = function () {
    $this->validate();

    $question = \App\Models\Question::create([
        'title' => $this->title,
        'content' => $this->content,
    ]);

    $this->success('Se ha enviado tu confesión');
    $this->reset();
}

?>

<div class="panel p-5 sm:p-6">
    <div class="flex items-center gap-2 mb-4">
        <span class="grid place-items-center w-8 h-8 rounded-lg bg-brand-tint text-brand-soft">
            <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z"/>
            </svg>
        </span>
        <h2 class="font-display font-bold text-ink">Escribe tu confesión</h2>
        <span class="chip-muted ml-auto">Anónimo</span>
    </div>

    <form wire:submit.prevent="submit" class="space-y-4">
        <div>
            <x-input-label for="title" value="Título" />
            <x-text-input wire:model="title" id="title" class="block w-full mt-1.5" type="text" maxlength="200" placeholder="Dale un nombre a tu secreto..." />
            <x-input-error :messages="$errors->get('title')" class="mt-1.5" />
        </div>
        <div>
            <div class="flex items-center justify-between">
                <x-input-label for="content" value="Contenido" />
                <span class="text-xs text-ink-faint tabular-nums" x-data="{ c: $wire.content }" x-text="c.length + ' / 10000'"></span>
            </div>
            <x-textarea-input wire:model.live="content" id="content" class="block w-full mt-1.5" rows="6" maxlength="10000" placeholder="Desahógate... el gato no juzga(la mayoría de las veces)" />
            <x-input-error :messages="$errors->get('content')" class="mt-1.5" />
        </div>
        <div class="flex items-center justify-between pt-1">
            <p class="text-xs text-ink-faint flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/>
                </svg>
                100% anónimo
            </p>
            <x-primary-button type="submit">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.75 14.25m0 0 2.91 8.73a2.25 2.25 0 0 0 2.13 1.52h7.42a2.25 2.25 0 0 0 2.13-1.52l2.91-8.73M3.75 14.25h16.5"/>
                </svg>
                Enviar confesión
            </x-primary-button>
        </div>
    </form>
</div>
