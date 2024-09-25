<?php

namespace Database\Factories;

use App\Models\GroupInvitation;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class GroupInvitationFactory extends Factory
{
    protected $model = GroupInvitation::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
