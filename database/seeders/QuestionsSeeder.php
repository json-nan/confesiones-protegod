<?php

namespace Database\Seeders;

use App\Models\Answer;
use App\Models\Question;
use Illuminate\Database\Seeder;

class QuestionsSeeder extends Seeder
{
    public function run(): void
    {
        Question::factory()->count(20)->has(Answer::factory())->create();
    }
}
