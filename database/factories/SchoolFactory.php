<?php

namespace Database\Factories;

use App\Models\School;
use App\Models\SchoolLocation;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class SchoolFactory extends Factory
{
    protected $model = School::class;

    public function definition(): array
    {
        return [
            'school_location_id' => SchoolLocation::factory(),
            'name' => $this->faker->company,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
