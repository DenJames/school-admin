<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\School;
use App\Models\SchoolLocation;
use App\Models\Teacher;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (!City::count()) {
            $cities = City::factory(10)->create();
        } else {
            $cities = City::all();
        }

        if (!SchoolLocation::count()) {
            $cities->each(function ($city) {
                SchoolLocation::factory(3)->create(['city_id' => $city->id]);
            });
        }

        $schoolLocations = SchoolLocation::all();
        if (!School::count()) {
            $schoolLocations->each(function ($location) {
                School::factory(2)->create(['school_location_id' => $location->id]);
            });
        }

        $schools = School::all();

        if (!User::count()) {
            // Part 1: Create the first user and team
            $user = User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);

            // Create a team for the first user
            $team = Team::factory()->create([
                'user_id' => $user->id,
                'name' => "{$user->name}'s Team",
                'school_id' => $schools->random()->id,
            ]);

            // Set the user's current team
            $user->update(['current_team_id' => $team->id]);

            // Create additional users and assign them to the first user's team
            $users = User::factory(10)->create([
                'current_team_id' => $team->id
            ]);

            foreach ($users as $newUser) {
                $team->users()->attach($newUser, ['role' => 'member']);
            }

            // Part 2: Create second user and team
            $user2 = User::factory()->create([
                'name' => 'Test User 2',
                'email' => 'test2@example.com',
            ]);

            // Create a team for the second user
            $team2 = Team::factory()->create([
                'user_id' => $user2->id,
                'name' => "{$user2->name}'s Team",
                'school_id' => $schools->random()->id,
            ]);

            $user2->update(['current_team_id' => $team2->id]);

            // Create additional users and assign them to the second user's team
            $users2 = User::factory(15)->create([
                'current_team_id' => $team2->id
            ]);

            foreach ($users2 as $newUser) {
                $team2->users()->attach($newUser, ['role' => 'member']);
            }

            // Part 3: Create third user and team
            $user3 = User::factory()->create([
                'name' => 'Test User 3',
                'email' => 'test3@example.com',
            ]);

            // Create a team for the third user
            $team3 = Team::factory()->create([
                'user_id' => $user3->id,
                'name' => "{$user3->name}'s Team",
                'school_id' => $schools->random()->id,
            ]);

            $user3->update(['current_team_id' => $team3->id]);

            // Create additional users and assign them to the third user's team
            $users3 = User::factory(7)->create([
                'current_team_id' => $team3->id
            ]);

            foreach ($users3 as $newUser) {
                $team3->users()->attach($newUser, ['role' => 'member']);
            }

            $userIds = User::pluck('id');
            if (!Teacher::count()) {
                $schools->each(function ($school) use (&$userIds) {
                    $teachersAmount = random_int(1, 7);

                    // Grab unique user ids before creating teachers for this school
                    $availableUserIds = $userIds->take($teachersAmount);

                    // Remove the selected user ids from the original collection
                    $userIds = $userIds->diff($availableUserIds);

                    $availableUserIds->each(function ($userId) use ($school) {
                        Teacher::factory()->create([
                            'school_id' => $school->id,
                            'user_id' => $userId,
                        ]);
                    });
                });
            }
        }

        // Add seeders here
        /* $this->call([
            //
        ]); */
    }
}
