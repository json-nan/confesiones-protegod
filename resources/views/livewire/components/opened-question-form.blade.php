<?php

use function Livewire\Volt\{state, rules};

state([
    'question',
    "answer_content" => "",
    'blur' => true
]);

rules([
    'answer_content' => 'required|string|max:3000'
])->messages([
    'answer_content.required' => 'Este campo es requerido.',
]);

$submit = function () {
    $this->validate();

    $this->question->answers()->create([
        'content' => $this->answer_content,
        'user_id' => auth()->id()
    ]);

    $this->question->update(['status' => \App\Enums\QuestionStatusEnum::ANSWERED]);
};

$ignore = function () {
    $this->question->update(['status' => \App\Enums\QuestionStatusEnum::IGNORED]);
};

$toggleBlur = function () {
    $this->blur = !$this->blur;
};
?>

<div class="p-4 bg-[#1a1224] shadow-lg rounded-lg">
    <form class="space-y-2" wire:submit.prevent="submit">
        <div class="">
            <div class="flex">
                <p class="text-2xl font-semibold text-white">
                    {{$question->title}}
                </p>
                <button type="button" class="ml-auto text-sm text-gray-400" wire:click="toggleBlur">
                    {{$blur ? 'Mostrar' : 'Ocultar'}} contenido
                </button>
            </div>
            <p class="whitespace-pre-line text-gray-200 {{$blur ? 'blur' : ''}}">
                {{$question->content}}
            </p>
        </div>
        <div>
            @if($question->status === \App\Enums\QuestionStatusEnum::OPEN)
                <x-textarea-input class="w-full" wire:model="answer_content" maxlength="3000"/>
                <x-input-error :messages="$errors->get('answer_content')" class=""/>
            @else
                <div class="bg-[#251a34] shadow-lg p-4 rounded-lg flex justify-between ">
                    <p class="text-white">{{$answer_content}}</p>
                </div>
            @endif
        </div>
        @if($question->status === \App\Enums\QuestionStatusEnum::ANSWERED)
            <div class="text-sm text-green-600 text-right">
                Pregunta respondida
            </div>
        @elseif($question->status === \App\Enums\QuestionStatusEnum::IGNORED)
            <div class="text-sm text-red-600 text-right">
                Pregunta ignorada
            </div>
        @else
            <div class="flex justify-end space-x-2">
                <x-danger-button wire:click="ignore">Ignorar</x-danger-button>
                <x-primary-button type="submit">Responder</x-primary-button>
            </div>
        @endif
    </form>
</div>
