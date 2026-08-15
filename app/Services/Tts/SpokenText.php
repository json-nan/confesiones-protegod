<?php

namespace App\Services\Tts;

use App\Models\Question;

/**
 * The text handed to the TTS engine.
 *
 * The whole confession is read, however long it is: Lemonfox documents no
 * limit on `input` and bills $2.50 per million characters, so even a
 * maxed-out 10 000 character confession costs about two cents.
 *
 * This is the single definition of "what text represents this confession" —
 * generation and the staleness check in QuestionAudio both go through it, so
 * they can't drift apart.
 */
readonly class SpokenText
{
    private function __construct(public string $value) {}

    public static function forQuestion(Question $question): self
    {
        // The trailing period matters: without it the engine runs the title
        // straight into the body with no pause.
        $title = rtrim(trim($question->title), " \t\n\r\0\x0B.,;:");

        return new self(trim($title.'. '.trim($question->content)));
    }

    public function hash(): string
    {
        return hash('sha256', $this->value);
    }

    public function length(): int
    {
        return mb_strlen($this->value);
    }
}
