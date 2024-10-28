<?php

namespace Database\Factories;

use App\Models\Notification;
use App\Models\User; // Import the User model
use App\Models\Classes; // Import the Classes model
use App\Models\ClassRoster; // Import the ClassRoster model
use App\Models\Schedule; // Import the Schedule model
use App\Models\RecurringSchedule; // Import the RecurringSchedule model

use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
{
    protected $model = Notification::class;

    public function definition()
    {
        // Get a random existing user, class, and class roster
        $user = User::inRandomOrder()->first(); // Get a random user
        $class = Classes::inRandomOrder()->first(); // Get a random class
        $classRoster = ClassRoster::inRandomOrder()->first(); // Get a random class roster

        // Create a new Schedule instance first
        $schedule = Schedule::factory()->create(); // Create a schedule

        // Create RecurringSchedule instances after creating a Schedule
        $dates = collect();

        // Generate unique random dates for recurring schedules
        while ($dates->count() < 5) { // Change 5 to the number of unique dates you want
            // Generate a random date within the next 5 weeks
            $randomDate = now()->addDays(rand(1, 35))->format('Y-m-d');
            $dates->push($randomDate)->unique(); // Ensure uniqueness
        }

        // Create recurring schedules for the created schedule
        foreach ($dates as $date) {
            RecurringSchedule::create([
                'schedule_id' => $schedule->id,
                'dates' => $date,
            ]);
        }

        return [
            'user_id' => $user ? $user->id : null, // Set user_id to the selected user's ID
            'class_id' => $class ? $class->id : null, // Set class_id to the selected class's ID
            'class_roster_id' => $classRoster ? $classRoster->id : null, // Set class_roster_id to the selected class roster's ID
            'recurring_schedule_id' => $schedule->id, // Assuming you want to link to the created schedule
            'notifiable_type' => User::class, // Assuming notifications are for users
            'notifiable_id' => $user ? $user->id : null, // Set notifiable_id to the selected user's ID
            'title' => $this->faker->sentence,
            'content' => $this->faker->paragraph,
            'type' => $this->faker->randomElement(['venue', 'schedule', 'assignment', 'payment', 'attendance']),
            'role' => $this->faker->randomElement(['tutor', 'tutee']),
        ];
    }
}
