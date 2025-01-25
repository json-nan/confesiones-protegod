<?php

use function Livewire\Volt\{state};

state([
    'questions' => fn() => \App\Models\Question::open()->latest('updated_at')->get(),
]);

?>

<div class="space-y-4">
    <h2 class="text-2xl font-semibold text-gray-200 leading-tight">
        Preguntas sin responder
    </h2>
    <div class="space-y-4">
        @foreach($questions as $question)
            <livewire:components.opened-question-form :question="$question" :key="$question->id"/>
        @endforeach
    </div>
</div>
