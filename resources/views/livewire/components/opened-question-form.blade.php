<?php

use function Livewire\Volt\{state, rules};

state([
    'question',
    "answer_content" => "",
    'blur' => true,
]);

rules([
    'answer_content' => 'required|string|max:3000',
])->messages([
    'answer_content.required' => 'Este campo es requerido.',
]);

$submit = function () {
    $this->validate();

    $this->question->answers()->create([
        'content' => $this->answer_content,
        'user_id' => auth()->id(),
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

<div class="panel p-5 space-y-4">
    <div class="flex items-start gap-3">
        <span class="grid place-items-center w-9 h-9 rounded-lg bg-brand-tint text-brand-soft shrink-0">
            <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.76c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.184-4.183a1.14 1.14 0 0 1 .778-.332 48.294 48.294 0 0 0 5.83-.498c1.585-.233 2.708-1.626 2.708-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z"/>
            </svg>
        </span>
        <div class="flex-1 min-w-0">
            <div class="flex items-start justify-between gap-3">
                <h3 class="font-display font-bold text-ink text-lg leading-snug break-words">{{ $question->title }}</h3>
                <button type="button" class="chip-muted hover:bg-panel-elevated transition-colors shrink-0" wire:click="toggleBlur">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        @if ($blur)
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                        @else
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88"/>
                        @endif
                    </svg>
                    {{ $blur ? 'Mostrar' : 'Ocultar' }}
                </button>
            </div>
            <p class="mt-1 whitespace-pre-line text-ink leading-relaxed transition-all {{ $blur ? 'blur-sm select-none' : '' }}">
                {{ $question->content }}
            </p>
            <div class="mt-2 flex items-center gap-1.5 text-xs text-ink-faint">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
                <time title="{{ $question->created_at->format('d M Y, H:i') }}">{{ $question->created_at->diffForHumans() }}</time>
            </div>
        </div>
    </div>

    @if ($question->status === \App\Enums\QuestionStatusEnum::OPEN)
        <div class="space-y-3 pt-2 border-t border-line">
            <textarea wire:model="answer_content" maxlength="3000" id="answer-textarea"
                      rows="4"
                      class="w-full px-3.5 py-2.5 text-ink bg-panel-muted border border-line rounded-lg focus:outline-none focus:ring-2 focus:ring-brand/50 focus:border-brand placeholder:text-ink-faint transition-colors"
                      placeholder="Tu respuesta..."></textarea>
            <x-input-error :messages="$errors->get('answer_content')" />

            <div class="flex justify-end gap-2 pt-1">
                <x-danger-button wire:click="ignore">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636"/>
                    </svg>
                    Ignorar
                </x-danger-button>
                <x-primary-button type="submit">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.75 14.25m0 0 2.91 8.73a2.25 2.25 0 0 0 2.13 1.52h7.42a2.25 2.25 0 0 0 2.13-1.52l2.91-8.73M3.75 14.25h16.5"/>
                    </svg>
                    Responder
                </x-primary-button>
            </div>
        </div>
    @elseif ($question->status === \App\Enums\QuestionStatusEnum::ANSWERED)
        <div class="flex items-center justify-end gap-1.5 pt-2 border-t border-line">
            <span class="chip-success">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.4" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                </svg>
                Respondida
            </span>
        </div>
    @elseif ($question->status === \App\Enums\QuestionStatusEnum::IGNORED)
        <div class="flex items-center justify-end gap-1.5 pt-2 border-t border-line">
            <span class="chip-error">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.4" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636"/>
                </svg>
                Ignorada
            </span>
        </div>
    @endif
</div>
