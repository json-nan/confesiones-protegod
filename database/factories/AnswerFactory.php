<?php

namespace Database\Factories;

use App\Models\Answer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class AnswerFactory extends Factory
{
    protected $model = Answer::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'content' => $this->faker->paragraph,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
