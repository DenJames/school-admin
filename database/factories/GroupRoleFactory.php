<?php

namespace Database\Factories;

use App\Models\GroupRole;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class GroupRoleFactory extends Factory
{
    protected $model = GroupRole::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
