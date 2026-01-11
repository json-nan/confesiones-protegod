<?php

use Livewire\WithFileUploads;
use function Livewire\Volt\{state, rules, uses};

uses([WithFileUploads::class]);

state([
    'question',
    "answer_content" => "",
    'blur' => true,
    'image' => null
]);

rules([
    'answer_content' => 'required|string|max:3000',
    'image' => 'nullable|image|max:5120'
])->messages([
    'answer_content.required' => 'Este campo es requerido.',
    'image.image' => 'El archivo debe ser una imagen.',
    'image.max' => 'La imagen no puede ser mayor a 5MB.',
]);

$submit = function () {
    $this->validate();

    $imagePath = null;
    if ($this->image) {
        $imagePath = $this->image->store('answers', 'public');
    }

    $this->question->answers()->create([
        'content' => $this->answer_content,
        'user_id' => auth()->id(),
        'image' => $imagePath
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

                <div class="mt-4">
                    @if($image)
                        <div class="relative inline-block">
                            <img src="{{ $image->temporaryUrl() }}" class="max-w-full h-48 object-cover rounded-lg"/>
                            <button type="button" class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600" wire:click="$set('image', null)">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    @else
                        <label class="flex flex-col items-center justify-center w-full h-24 border-2 border-gray-600 border-dashed rounded-lg cursor-pointer bg-[#251a34] hover:bg-[#2f2340]">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-6 h-6 mb-2 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                </svg>
                                <p class="text-sm text-gray-400"><span class="font-semibold">Click para subir</span> o arrastra una imagen</p>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF (Máx. 5MB)</p>
                            </div>
                            <input type="file" class="hidden" accept="image/*" wire:model="image"/>
                        </label>
                    @endif
                    <x-input-error :messages="$errors->get('image')" class="mt-1"/>
                </div>
            @else
                <div class="bg-[#251a34] shadow-lg p-4 rounded-lg flex justify-between">
                    <p class="text-white">{{$answer_content}}</p>
                </div>
                @if($image)
                    <img src="{{ $image->temporaryUrl() }}" class="mt-2 max-w-full h-48 object-cover rounded-lg"/>
                @endif
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
