<?php

namespace Database\Factories;

use App\Models\ClassCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ClassCategoryFactory extends Factory
{
    protected $model = ClassCategory::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
