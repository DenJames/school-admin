<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\SchoolLocation;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class SchoolLocationFactory extends Factory
{
    protected $model = SchoolLocation::class;

    public function definition(): array
    {
        return [
            'city_id' => City::factory(),
            'address' => $this->faker->address,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
