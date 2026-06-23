<?php

use function Livewire\Volt\{state};

state([
    'questions' => fn() => \App\Models\Question::open()->latest('updated_at')->get(),
]);

?>

<div class="space-y-4 pt-4">
    <h2 class="font-display font-bold text-ink text-lg">Preguntas sin responder</h2>

    @forelse ($questions as $question)
        <livewire:components.opened-question-form :question="$question" :key="$question->id" />
    @empty
        <div class="panel p-10 text-center space-y-3">
            <div class="mx-auto grid place-items-center w-12 h-12 rounded-full bg-panel-muted text-success">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                </svg>
            </div>
            <p class="text-ink font-medium">Bandeja vacía</p>
            <p class="text-ink-muted text-sm">No hay confesiones esperando respuesta. Buen trabajo.</p>
        </div>
    @endforelse
</div>
