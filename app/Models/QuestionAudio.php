<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class QuestionAudio extends Model
{
    // The inflector treats "audio" as uncountable and would look for
    // `question_audio`.
    protected $table = 'question_audios';

    protected $fillable = [
        'disk',
        'path',
        'voice',
        'text_hash',
        'char_count',
        'size',
    ];

    protected $casts = [
        'char_count' => 'integer',
        'size' => 'integer',
    ];

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * A pre-signed URL the browser hits directly, so the audio bytes never
     * pass through this container on playback — only the ~200 byte URL does.
     */
    public function temporaryUrl(): string
    {
        return Storage::disk($this->disk)->temporaryUrl(
            $this->path,
            now()->addMinutes((int) config('services.lemonfox.url_ttl')),
        );
    }

    /**
     * True when the confession has been edited since the audio was rendered.
     * Surfaced in the panel as a hint; it never regenerates on its own.
     */
    public function matches(string $text): bool
    {
        return hash_equals($this->text_hash, hash('sha256', $text));
    }

    /**
     * Drop the stored object, tolerating a storage outage.
     *
     * The disk is configured to throw so that a failed *upload* can't leave a
     * row pointing at nothing. Deletion is the opposite trade: a leftover
     * object costs a fraction of a cent a month, which is not worth failing
     * the generation the user just paid for — or blocking a question delete.
     */
    public function discardObject(): void
    {
        try {
            Storage::disk($this->disk)->delete($this->path);
        } catch (Throwable $e) {
            Log::warning('No se pudo borrar el audio de una confesión.', [
                'question_id' => $this->question_id,
                'disk' => $this->disk,
                'path' => $this->path,
                'exception' => $e->getMessage(),
            ]);
        }
    }
}
