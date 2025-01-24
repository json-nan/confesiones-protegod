<?php

namespace Database\Factories;

use App\Enums\QuestionStatusEnum;
use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class QuestionFactory extends Factory
{
    protected $model = Question::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'content' => $this->faker->paragraph,
            'status' => $this->faker->randomElement(QuestionStatusEnum::values()),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
