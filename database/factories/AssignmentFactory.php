<?php

namespace Database\Factories;

use App\Models\Assignment;
use App\Models\Teacher;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class AssignmentFactory extends Factory
{
    protected $model = Assignment::class;

    public function definition(): array
    {
        return [
            'teacher_id' => Teacher::factory(),
            'team_id' => Team::factory(),
            'user_id' => User::Factory(),
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'due_at' => $this->faker->dateTimeBetween('now', '+1 month'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
