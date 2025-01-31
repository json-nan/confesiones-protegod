<?php

use function Livewire\Volt\layout;
use function Livewire\Volt\{state};

layout('layouts.app');

state([
    'selectedTab' => 'questions-opened',
    'unansweredQuestionsCount' => fn() => \App\Models\Question::open()->count(),
]);

?>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <x-mary-tabs
            wire:model="selectedTab"
            active-class="bg-primary rounded text-white"
            label-class="font-semibold"
            label-div-class="bg-primary/5 p-2 rounded">
            <x-mary-tab name="questions-opened">
                <x-slot:label>
                    Sin responder
                    <x-mary-badge value="{{$unansweredQuestionsCount}}"
                                  class="badge-secondary indicator-item text-white"/>
                </x-slot:label>
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
