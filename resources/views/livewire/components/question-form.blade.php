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
    'title' => 'required|string|max:100',
    'content' => 'required|string|max:3000',
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

    $this->success('Se ha enviado tu pregunta');
    $this->reset();
}

?>

<div class="p-4 bg-[#1a1224] shadow-lg rounded-lg">
    <form wire:submit.prevent="submit" class="space-y-4">
        <div>
            <x-input-label for="title" value="Título" maxlength="100"/>
            <x-text-input wire:model="title" label="Name" class="w-full"/>
            <x-input-error :messages="$errors->get('title')" class=""/>
        </div>
        <div>
            <x-input-label for="content" value="Contenido"/>
            <x-textarea-input maxlength="3000" wire:model.live="content" label="Content" class="w-full" rows="6"/>
            <x-input-error :messages="$errors->get('content')" class=""/>
        </div>
        <div class="flex justify-end">
            <x-primary-button type="submit">Enviar</x-primary-button>
        </div>
    </form>
</div>
