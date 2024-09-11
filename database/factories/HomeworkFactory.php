<?php

namespace Database\Factories;

use App\Models\Homework;
use App\Models\Lesson;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class HomeworkFactory extends Factory
{
    protected $model = Homework::class;

    public function definition(): array
    {
        return [
            'lesson_id' => Lesson::factory(),
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'due_at' => $this->faker->dateTimeBetween('now', '+1 month'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
