<?php

namespace App\Services\Tts;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class LemonfoxTts
{
    public function __construct(
        private readonly string $apiKey,
        private readonly string $endpoint,
        private readonly string $voice,
        private readonly string $language,
        private readonly int $timeout,
    ) {}

    /**
     * Render text to speech and return the raw mp3 bytes.
     *
     * @throws TtsException
     */
    public function speak(string $text): string
    {
        if ($this->apiKey === '') {
            throw new TtsException('LEMONFOX_API_KEY no está configurada.');
        }

        try {
            // Passing an array makes the client send application/json. Posting
            // a pre-encoded string instead would go out as text/plain and the
            // API rejects it.
            $response = Http::withToken($this->apiKey)
                ->timeout($this->timeout)
                ->post($this->endpoint, [
                    'input' => $text,
                    'voice' => $this->voice,
                    'language' => $this->language,
                    'response_format' => 'mp3',
                ]);
        } catch (ConnectionException $e) {
            throw new TtsException('No se pudo contactar a Lemonfox: '.$e->getMessage(), previous: $e);
        }

        if ($response->failed()) {
            throw new TtsException($this->describeFailure($response->status(), $response->body()));
        }

        $body = $response->body();

        if ($body === '') {
            throw new TtsException('Lemonfox devolvió una respuesta vacía.');
        }

        return $body;
    }

    /**
     * Errors come back as JSON while successes come back as binary, so the
     * body is only worth decoding on the failure path.
     */
    private function describeFailure(int $status, string $body): string
    {
        $decoded = json_decode($body, true);

        $detail = is_array($decoded)
            ? ($decoded['error']['message'] ?? $decoded['error'] ?? $decoded['message'] ?? null)
            : null;

        return is_string($detail) && $detail !== ''
            ? "Lemonfox respondió {$status}: {$detail}"
            : "Lemonfox respondió {$status}.";
    }
}
