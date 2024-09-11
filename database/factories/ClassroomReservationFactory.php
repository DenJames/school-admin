<?php

namespace Database\Factories;

use App\Models\Classroom;
use App\Models\ClassroomReservation;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ClassroomReservationFactory extends Factory
{
    protected $model = ClassroomReservation::class;

    public function definition(): array
    {
        return [
            'classroom_id' => Classroom::factory(),
            'teacher_id' => Teacher::factory(),
            'booked_from' => $this->faker->dateTimeBetween('now', '+1 month'),
            'booked_to' => $this->faker->dateTimeBetween('+1 month', '+2 months'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
