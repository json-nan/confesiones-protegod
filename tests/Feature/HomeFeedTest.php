<?php

use App\Enums\QuestionStatusEnum;
use App\Models\Question;
use Livewire\Volt\Volt;

function answeredQuestions(int $count): void
{
    Question::factory()
        ->count($count)
        ->create(['status' => QuestionStatusEnum::ANSWERED]);
}

it('loads only the first batch and offers to load more', function () {
    answeredQuestions(25);

    Volt::test('pages.home')
        ->assertSet('perPage', 10)
        ->assertViewHas('total', 25)
        ->assertViewHas('hasMore', true)
        ->assertViewHas('questions', fn ($questions) => $questions->count() === 10);
});

it('appends the next batch while keeping the ones already visible', function () {
    answeredQuestions(25);

    $component = Volt::test('pages.home');

    $firstBatch = $component->viewData('questions')->pluck('id')->all();

    $component->call('loadMore')
        ->assertSet('perPage', 20)
        ->assertViewHas('questions', fn ($questions) => $questions->count() === 20);

    $secondBatch = $component->viewData('questions')->pluck('id')->all();

    // "Load more" appends — everything from the first render is still on screen,
    // in the same order, with the new page after it.
    expect(array_slice($secondBatch, 0, 10))->toBe($firstBatch);
});

it('stops offering more once the whole archive is shown', function () {
    answeredQuestions(12);

    Volt::test('pages.home')
        ->assertViewHas('hasMore', true)
        ->call('loadMore')
        ->assertViewHas('hasMore', false)
        ->assertViewHas('questions', fn ($questions) => $questions->count() === 12);
});

it('ignores questions that are not answered', function () {
    answeredQuestions(3);
    Question::factory()->count(5)->create(['status' => QuestionStatusEnum::OPEN]);
    Question::factory()->count(4)->create(['status' => QuestionStatusEnum::IGNORED]);

    Volt::test('pages.home')
        ->assertViewHas('total', 3)
        ->assertViewHas('hasMore', false)
        ->assertViewHas('questions', fn ($questions) => $questions->count() === 3);
});

it('shows no load more control when the archive is empty', function () {
    Volt::test('pages.home')
        ->assertViewHas('total', 0)
        ->assertViewHas('hasMore', false)
        ->assertDontSee('Cargar más');
});
