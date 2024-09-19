<?php

namespace Database\Factories;

use App\Models\AssignedGrade;
use App\Models\ClassCategory;
use App\Models\Teacher;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class AssignedGradeFactory extends Factory
{
    protected $model = AssignedGrade::class;

    public function definition(): array
    {
        $grades = [
            '-3',
            '00',
            '02',
            '4',
            '7',
            '10',
            '12',
        ];

        return [
            'team_id' => Team::factory(),
            'user_id' => User::factory(),
            'teacher_id' => Teacher::factory(),
            'class_category_id' => ClassCategory::factory(),
            'grade' => $this->faker->randomElement($grades),
            'comment' => $this->faker->sentence,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
