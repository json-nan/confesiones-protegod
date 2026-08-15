<?php

use App\Models\Question;
use App\Models\QuestionAudio;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Livewire\Volt\Volt;

beforeEach(function () {
    config()->set('services.lemonfox.key', 'test-key');
    config()->set('services.lemonfox.disk', 'r2');

    Storage::fake('r2');
});

function fakeSpeech(string $bytes = 'ID3-fake-mp3-bytes'): void
{
    Http::fake([
        'api.lemonfox.ai/*' => Http::response($bytes, 200, ['Content-Type' => 'audio/mpeg']),
    ]);
}

it('renders a confession to speech and stores the mp3 off-container', function () {
    fakeSpeech();

    $question = Question::factory()->create([
        'title' => 'Mi secreto',
        'content' => 'Nunca devolví el libro.',
    ]);

    Volt::test('components.question-audio', ['question' => $question])
        ->call('generate')
        ->assertHasNoErrors();

    $audio = $question->fresh()->audio;

    expect($audio)->not->toBeNull()
        ->and($audio->disk)->toBe('r2')
        ->and($audio->voice)->toBe('dora')
        ->and($audio->size)->toBe(strlen('ID3-fake-mp3-bytes'));

    Storage::disk('r2')->assertExists($audio->path);
});

it('asks Lemonfox for the dora voice in Spanish as mp3', function () {
    fakeSpeech();

    $question = Question::factory()->create(['title' => 'Hola', 'content' => 'Adiós.']);

    Volt::test('components.question-audio', ['question' => $question])->call('generate');

    Http::assertSent(function (Request $request) {
        expect($request->url())->toBe('https://api.lemonfox.ai/v1/audio/speech')
            ->and($request->hasHeader('Authorization', 'Bearer test-key'))->toBeTrue()
            // The snippet in the docs posts a raw string, which goes out as
            // text/plain and gets rejected — this guards against regressing to it.
            ->and($request->hasHeader('Content-Type', 'application/json'))->toBeTrue();

        return $request->data() === [
            'input' => 'Hola. Adiós.',
            'voice' => 'dora',
            'language' => 'es',
            'response_format' => 'mp3',
        ];
    });
});

it('replaces the old object when regenerating', function () {
    fakeSpeech('first-take');

    $question = Question::factory()->create();

    $component = Volt::test('components.question-audio', ['question' => $question]);
    $component->call('generate');

    $first = $question->fresh()->audio->path;

    fakeSpeech('second-take');
    $component->call('generate');

    $second = $question->fresh()->audio->path;

    expect($second)->not->toBe($first)
        ->and(QuestionAudio::count())->toBe(1);

    // A stale object would keep billing and could be served from a cache.
    Storage::disk('r2')->assertMissing($first);
    Storage::disk('r2')->assertExists($second);
});

it('reads the whole confession however long it is', function () {
    fakeSpeech();

    // The public form allows 10 000 characters and Lemonfox documents no cap
    // on `input`, so nothing here should be trimmed.
    $content = str_repeat('Una frase mas. ', 700);

    $question = Question::factory()->create([
        'title' => 'Largo',
        'content' => $content,
    ]);

    Volt::test('components.question-audio', ['question' => $question])->call('generate');

    $expected = 'Largo. '.trim($content);

    Http::assertSent(fn (Request $r) => $r->data()['input'] === $expected);

    expect($question->fresh()->audio->char_count)->toBe(mb_strlen($expected));
});

it('keeps no audio row when Lemonfox fails', function () {
    Http::fake([
        'api.lemonfox.ai/*' => Http::response(['error' => ['message' => 'Insufficient credits']], 402),
    ]);

    $question = Question::factory()->create();

    Volt::test('components.question-audio', ['question' => $question])->call('generate');

    expect($question->fresh()->audio)->toBeNull();
    expect(Storage::disk('r2')->allFiles())->toBeEmpty();
});

it('does not call Lemonfox when the api key is missing', function () {
    config()->set('services.lemonfox.key', '');
    Http::fake();

    $question = Question::factory()->create();

    Volt::test('components.question-audio', ['question' => $question])->call('generate');

    Http::assertNothingSent();
    expect($question->fresh()->audio)->toBeNull();
});

it('offers the button in both panel tabs', function () {
    Question::factory()->create(['status' => \App\Enums\QuestionStatusEnum::OPEN]);
    Question::factory()->create(['status' => \App\Enums\QuestionStatusEnum::ANSWERED]);

    Volt::test('components.opened-questions-tab')->assertSee('Escuchar');
    Volt::test('components.answered-questions-tab')->assertSee('Escuchar');
});

it('drops the stored object when the question is deleted', function () {
    fakeSpeech();

    $question = Question::factory()->create();

    Volt::test('components.question-audio', ['question' => $question])->call('generate');

    $path = $question->fresh()->audio->path;

    $question->fresh()->delete();

    Storage::disk('r2')->assertMissing($path);
    expect(QuestionAudio::count())->toBe(0);
});
