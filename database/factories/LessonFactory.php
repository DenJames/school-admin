<?php

namespace Database\Factories;

use App\Models\ClassCategory;
use App\Models\ClassroomReservation;
use App\Models\Lesson;
use App\Models\Teacher;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class LessonFactory extends Factory
{
    protected $model = Lesson::class;

    public function definition(): array
    {
        return [
            'team_id' => Team::factory(),
            'class_category_id' => ClassCategory::factory(),
            'classroom_reservation_id' => ClassRoomReservation::factory(),
            'teacher_id' => Teacher::factory(),
            'name' => $this->faker->word,
            'duration' => $this->faker->numberBetween(25, 120),
            'starts_at' => $this->faker->dateTimeBetween('now', '+2 months'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
