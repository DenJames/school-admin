<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\ClassCategory;
use App\Models\Classroom;
use App\Models\ClassroomReservation;
use App\Models\Lesson;
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

            $schools->each(function ($school) {
                foreach (range(1, 10) as $index) {
                    $school->classrooms()->create([
                        'name' => "{$school->name} - Classroom $index"
                    ]);
                }
            });

            $teachers = Teacher::all();
            $classrooms = Classroom::all();

            if (!ClassroomReservation::count()) {
                $teachers->each(function ($teacher) use ($classrooms) {
                    // Ensure no overlapping reservations by spreading the booked time windows sequentially
                    $startDate = now();
                    $endDate = $startDate->clone()->addDays(10);

                    $classrooms->random(random_int(1, 5))->each(function ($classroom) use ($teacher, &$startDate, &$endDate) {
                        // Create the reservation with a sequential time window
                        $teacher->classroomReservations()->create([
                            'classroom_id' => $classroom->id,
                            'booked_from' => $startDate->clone(),
                            'booked_to' => $endDate->clone(),
                        ]);

                        // Update start and end dates for the next reservation to avoid overlap
                        $startDate = $endDate->clone()->addDays(1);
                        $endDate = $startDate->clone()->addDays(10);
                    });
                });
            }

            $classCategories = collect([
                'Math',
                'Science',
                'History',
                'Literature',
                'Physical Education',
                'Music',
                'Art',
                'Computer Science',
                'Foreign Language',
                'Geography',
            ]);

            $classCategories->each(function ($category) {
                ClassCategory::firstOrCreate(['name' => $category]);
            });

            if (!Lesson::count()) {
                $classCategories = ClassCategory::all();
                $classroomReservations = ClassroomReservation::all();

                $classroomReservations->each(function ($reservation) use ($classCategories, $teachers) {
                    $classCategories->random(random_int(1, 3))->each(function ($category) use ($reservation, $teachers) {
                        $teacher = $teachers->random();

                        // Ensure the school has teams before selecting a random one
                        if ($teacher->school->teams->isNotEmpty()) {
                            $team = $teacher->school->teams->random();
                        } else {
                            // Handle the case where there are no teams, e.g., skip or create a fallback team
                            return;
                        }

                        $reservation->lessons()->create([
                            'team_id' => $team->id,
                            'teacher_id' => $teacher->id,
                            'class_category_id' => $category->id,
                            'name' => "{$category->name} Lesson",
                            'duration' => random_int(30, 120),
                            'starts_at' => $reservation->booked_from->clone()->addDays(random_int(1, 10)),
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
