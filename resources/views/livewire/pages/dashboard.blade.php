<?php

use function Livewire\Volt\layout;
use function Livewire\Volt\{state};

layout('layouts.app');

state([
    'selectedTab' => 'questions-opened',
]);

?>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <x-mary-tabs
            wire:model="selectedTab"
            active-class="bg-primary rounded text-white"
            label-class="font-semibold"
            label-div-class="bg-primary/5 p-2 rounded">
            <x-mary-tab name="questions-opened" label="Preguntas sin responder">
                <livewire:components.opened-questions-tab/>
            </x-mary-tab>
            <x-mary-tab name="questions-answered" label="Preguntas respondidas">
                <livewire:components.answered-questions-tab/>
            </x-mary-tab>
            <x-mary-tab name="questions-ignored" label="Preguntas ignoradas">
                <livewire:components.ignored-questions-tab/>
            </x-mary-tab>
        </x-mary-tabs>
    </div>
</div>
