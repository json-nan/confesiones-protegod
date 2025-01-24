<?php

use function Livewire\Volt\{state};

state([
    'questions' => fn() => \App\Models\Question::ignored()->get(),
]);

?>

<div class="space-y-4">
    @foreach($questions as $question)
        <div class="bg-[#1a1224] p-4 rounded-lg shadow-lg">
            <div class="flex justify-between">
                <h2 class="text-lg font-bold text-white">{{$question->title}}</h2>
                <span class="text-sm text-gray-400">{{$question->created_at->diffForHumans()}}</span>
            </div>
            <p class="text-white whitespace-pre-line">{{$question->content}}</p>
        </div>
    @endforeach
</div>
