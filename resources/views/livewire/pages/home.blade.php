<?php

use Mary\Traits\Toast;
use App\Models\Question;
use function Livewire\Volt\{state, layout, uses};

layout('layouts.guest');


state([
    'questions' => fn() => Question::with('answers')->answered()->latest('updated_at')->get(),
]);

?>

<div class="max-w-3xl w-full mx-auto px-4 sm:px-6 py-10 sm:py-16 space-y-10">

    {{-- Hero --}}
    <header class="text-center space-y-5 animate-fade-up">
        <div class="relative inline-grid place-items-center">
            <span class="absolute inset-0 rounded-full bg-brand/15 blur-2xl" aria-hidden="true"></span>
            <img src="{{ asset('images/images_10.jpg') }}" alt="El gato confesoso"
                 class="relative w-28 h-28 sm:w-32 sm:h-32 rounded-full object-cover ring-4 ring-line-strong shadow-glow">
        </div>
        <div class="space-y-3">
            <span class="chip-brand mx-auto">Anónimo &middot; sin juicios</span>
            <h1 class="font-display font-extrabold tracking-tight text-ink text-4xl sm:text-5xl leading-[1.05]">
                El gato <span class="text-brand-soft">confesoso</span>
            </h1>
            <p class="text-ink-muted text-base sm:text-lg max-w-xl mx-auto">
                Cuénta lo que nadie más sabe. Escribe tu confesión y recibe una respuesta, en secreto.
            </p>
        </div>
    </header>

    {{-- Confession form --}}
    <section id="confesar" class="scroll-mt-24">
        <livewire:components.question-form />
    </section>

    {{-- Feed --}}
    <section class="space-y-5">
        <div class="flex items-center justify-between">
            <h2 class="font-display font-bold text-ink text-xl">Confesiones recientes</h2>
            <span class="chip-muted">{{ $questions->count() }} respuestas</span>
        </div>

        @forelse ($questions as $question)
            <article class="panel p-5 sm:p-6 space-y-4 animate-fade-up">
                <div class="flex items-start justify-between gap-4">
                    <h3 class="font-display font-bold text-ink text-lg leading-snug">{{ $question->title }}</h3>
                    <time class="chip-muted whitespace-nowrap">{{ $question->created_at->diffForHumans() }}</time>
                </div>

                <p class="text-ink-muted whitespace-pre-line leading-relaxed">{{ $question->content }}</p>

                <div class="space-y-3 pt-2 border-t border-line">
                    @foreach ($question->answers as $answer)
                        <div class="bg-panel-muted border border-line rounded-xl p-4 sm:p-5">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="grid place-items-center w-6 h-6 rounded-full bg-brand text-white shrink-0">
                                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h8M8 14h5"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c-3.5 0-6 2.4-6 5.5V12l-1.5 2.5h15L18 12V8.5C18 5.4 15.5 3 12 3Z"/>
                                    </svg>
                                </span>
                                <span class="text-xs font-semibold text-brand-soft">El gato responde</span>
                                <time class="text-xs text-ink-faint ml-auto">{{ $answer->created_at->diffForHumans() }}</time>
                            </div>
                            <p class="text-ink whitespace-pre-line leading-relaxed">{{ $answer->content }}</p>
                        </div>
                    @endforeach
                </div>
            </article>
        @empty
            <div class="panel p-10 text-center space-y-3">
                <div class="mx-auto grid place-items-center w-12 h-12 rounded-full bg-panel-muted text-ink-faint">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3.75h6m-6 3.75h3M4.5 5.25h15a.75.75 0 0 1 .75.75v12a.75.75 0 0 1-.75.75h-15a.75.75 0 0 1-.75-.75V6a.75.75 0 0 1 .75-.75Z"/>
                    </svg>
                </div>
                <p class="text-ink font-medium">Aún no hay confesiones respondidas</p>
                <p class="text-ink-muted text-sm">Sé el primero en escribirle al gato.</p>
            </div>
        @endforelse
    </section>
</div>
