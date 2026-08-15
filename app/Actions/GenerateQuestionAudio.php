<?php

namespace App\Actions;

use App\Models\Question;
use App\Models\QuestionAudio;
use App\Services\Tts\LemonfoxTts;
use App\Services\Tts\SpokenText;
use App\Services\Tts\TtsException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

/**
 * Renders a confession to speech and parks the mp3 in object storage.
 *
 * Runs inline — the container has no queue worker (QUEUE_CONNECTION=sync) and
 * the button is explicitly on-demand, so a few seconds of blocking is the
 * expected cost of a click.
 */
class GenerateQuestionAudio
{
    public function __construct(private readonly LemonfoxTts $tts) {}

    /**
     * @throws TtsException
     */
    public function __invoke(Question $question): QuestionAudio
    {
        $spoken = SpokenText::forQuestion($question);

        if ($spoken->value === '') {
            throw new TtsException('La confesión no tiene texto que convertir.');
        }

        $bytes = $this->tts->speak($spoken->value);

        $disk = (string) config('services.lemonfox.disk');
        $voice = (string) config('services.lemonfox.voice');

        // A fresh key per generation, so regenerating can never be served a
        // stale copy out of a browser or CDN cache.
        $path = sprintf('confesiones/%d/%s.mp3', $question->id, Str::uuid());

        Storage::disk($disk)->put($path, $bytes, [
            'ContentType' => 'audio/mpeg',
        ]);

        // Read the outgoing object's location before the upsert overwrites it.
        $previous = $question->audio()->first();

        try {
            $audio = $question->audio()->updateOrCreate([], [
                'disk' => $disk,
                'path' => $path,
                'voice' => $voice,
                'text_hash' => $spoken->hash(),
                'char_count' => $spoken->length(),
                'size' => strlen($bytes),
            ]);
        } catch (Throwable $e) {
            // Nothing points at the upload now, so don't leave it billing.
            // Wrapped because the disk throws: a failure to clean up must not
            // mask the error that actually broke the generation.
            rescue(fn () => Storage::disk($disk)->delete($path));

            throw $e;
        }

        if ($previous && $previous->path !== $path) {
            $previous->discardObject();
        }

        $question->setRelation('audio', $audio);

        return $audio;
    }
}
