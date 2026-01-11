<?php

use Mary\Traits\Toast;
use App\Models\Question;
use function Livewire\Volt\{state, layout, uses};

layout('layouts.guest');


state([
    'questions' => fn() => Question::with('answers')->answered()->latest('updated_at')->get(),
]);

?>

<div class="max-w-7xl w-full mx-auto py-10 space-y-6 px-4">
    <h1 class="text-4xl text-center text-white font-bold">El gato confesoso</h1>
    <img src="{{asset("images/images_10.jpg")}}" alt="protegod" class="rounded-full shadow-md mx-auto">
    <livewire:components.question-form/>
    <div class="space-y-4">
        @foreach($questions as $question)
            <div class="bg-[#1a1224] p-4 rounded-lg shadow-lg">
                <div class="flex justify-between">
                    <h2 class="text-lg font-bold text-white">{{$question->title}}</h2>
                    <span class="text-sm text-gray-400">{{$question->created_at->diffForHumans()}}</span>
                </div>
                <p class="text-white whitespace-pre-line">{{$question->content}}</p>
                <div class="flex flex-col gap-2 mt-4">
                    @foreach($question->answers as $answer)
                        <div class="bg-[#251a34] shadow-lg p-4 rounded-lg">
                            <div class="flex justify-between">
                                <p class="text-white">{{$answer->content}}</p>
                                <span class="text-sm text-gray-400 flex-shrink-0">{{$answer->created_at->diffForHumans()}}</span>
                            </div>
                            @if($answer->image)
                                <img src="{{ asset('storage/' . $answer->image) }}" class="mt-3 max-w-full h-64 object-cover rounded-lg"/>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>
