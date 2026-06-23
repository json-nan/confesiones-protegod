<?php

use function Livewire\Volt\{state};

state([
    'questions' => fn() => \App\Models\Question::ignored()->latest('updated_at')->get(),
]);

?>

<div class="space-y-4 pt-4">
    @forelse ($questions as $question)
        <article class="panel p-5 space-y-2 opacity-90">
            <div class="flex items-start justify-between gap-3">
                <h3 class="font-display font-bold text-ink-muted text-lg leading-snug break-words line-through decoration-line-strong">{{ $question->title }}</h3>
                <time class="chip-muted whitespace-nowrap">{{ $question->created_at->diffForHumans() }}</time>
            </div>
            <p class="text-ink-faint whitespace-pre-line leading-relaxed">{{ $question->content }}</p>
        </article>
    @empty
        <div class="panel p-10 text-center space-y-3">
            <div class="mx-auto grid place-items-center w-12 h-12 rounded-full bg-panel-muted text-ink-faint">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z"/>
                </svg>
            </div>
            <p class="text-ink font-medium">Nada ignorado</p>
            <p class="text-ink-muted text-sm">Las confesiones que ignores se acumularán aquí.</p>
        </div>
    @endforelse
</div>
