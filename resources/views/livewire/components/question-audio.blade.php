<?php

use App\Actions\GenerateQuestionAudio;
use App\Services\Tts\SpokenText;
use App\Services\Tts\TtsException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Mary\Traits\Toast;

use function Livewire\Volt\{computed, state, uses};

uses([Toast::class]);

state(['question']);

$audio = computed(fn () => $this->question->audio);

// Pure local HMAC signing — no round trip to R2 — so recomputing this on every
// render costs nothing.
$url = computed(fn () => $this->audio?->temporaryUrl());

// Confessions have no edit screen today, so this stays false in practice. It
// costs one hash to keep the panel honest if that ever changes.
$stale = computed(function () {
    if (! $this->audio) {
        return false;
    }

    return ! $this->audio->matches(SpokenText::forQuestion($this->question)->value);
});

$generate = function () {
    // Two fast clicks would otherwise be two billable Lemonfox calls. The lock
    // lives in the file cache, which is per-container — fine while this runs
    // as a single instance, and the button is disabled client-side meanwhile.
    $lock = Cache::lock("tts:question:{$this->question->id}", 180);

    if (! $lock->get()) {
        $this->warning('Ese audio ya se está generando.');

        return;
    }

    try {
        app(GenerateQuestionAudio::class)($this->question);

        unset($this->audio, $this->url, $this->stale);

        $this->success('Audio listo.');
    } catch (TtsException $e) {
        $this->error($e->getMessage());
    } catch (\Throwable $e) {
        Log::error('Falló la generación de audio de una confesión.', [
            'question_id' => $this->question->id,
            'exception' => $e,
        ]);

        $this->error('No se pudo guardar el audio. Revisa los logs.');
    } finally {
        $lock->release();
    }
};

?>

<div class="pt-3 border-t border-line">
    @if ($this->audio)
        <div class="flex flex-wrap items-center gap-x-3 gap-y-2">
            {{-- preload="none" keeps a panel full of confessions from pulling
                 every mp3 out of R2 before anyone presses play. The key is the
                 object path so Livewire only swaps the element when the audio
                 actually changes, instead of restarting playback on re-render. --}}
            <audio
                controls
                preload="none"
                src="{{ $this->url }}"
                wire:key="audio-{{ $this->audio->path }}"
                style="color-scheme: dark"
                class="h-9 flex-1 min-w-[15rem] max-w-md"></audio>

            <button type="button"
                    wire:click="generate"
                    wire:loading.attr="disabled"
                    wire:target="generate"
                    class="chip-muted hover:bg-panel-elevated transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"
                     wire:loading.class="animate-spin" wire:target="generate">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992V4.356m-4.992 4.992-3.181-3.183a8.25 8.25 0 0 0-13.803 3.7M4.031 9.865v4.99m0 0h4.99m-4.99 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7"/>
                </svg>
                <span wire:loading.remove wire:target="generate">Regenerar</span>
                <span wire:loading wire:target="generate">Generando…</span>
            </button>

            @if ($this->stale)
                <span class="chip-warning" title="La confesión cambió después de generar este audio.">
                    Texto desactualizado
                </span>
            @endif
        </div>
    @else
        <button type="button"
                wire:click="generate"
                wire:loading.attr="disabled"
                wire:target="generate"
                class="inline-flex items-center gap-2 rounded-lg px-3 py-1.5 text-sm font-semibold bg-brand-tint text-brand-soft border border-brand/30 hover:bg-brand/20 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
            <span wire:loading.remove wire:target="generate" class="contents">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.114 5.636a9 9 0 0 1 0 12.728M16.463 8.288a5.25 5.25 0 0 1 0 7.424M6.75 8.25l4.72-4.72a.75.75 0 0 1 1.28.53v15.88a.75.75 0 0 1-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.009 9.009 0 0 1 2.25 12c0-.83.112-1.633.322-2.396C2.806 8.756 3.63 8.25 4.51 8.25H6.75Z"/>
                </svg>
                Escuchar
            </span>
            <span wire:loading wire:target="generate" class="contents">
                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0 1 8-8v4a4 4 0 0 0-4 4H4Z"/>
                </svg>
                Generando…
            </span>
        </button>
        <p class="mt-1.5 text-xs text-ink-faint">Genera la narración con la voz del gato. Se guarda para no volver a pagarla.</p>
    @endif
</div>
