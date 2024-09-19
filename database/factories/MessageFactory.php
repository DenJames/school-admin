<?php

namespace Database\Factories;

use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class MessageFactory extends Factory
{
    protected $model = Message::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'receiver_id' => User::factory(),
            'subject' => $this->faker->sentence,
            'content' => $this->faker->sentence,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
