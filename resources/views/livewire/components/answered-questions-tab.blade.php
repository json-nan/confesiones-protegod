<?php

use function Livewire\Volt\{state};

state([
    'questions' => fn() => \App\Models\Question::with('answers')->answered()->latest('updated_at')->get(),
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
