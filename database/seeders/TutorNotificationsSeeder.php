<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TutorNotificationsSeeder extends Seeder
{
    public function run()
    {
        // Restrict notification types
        $types = ['venue', 'schedule', 'assignment'];
        // Define tutor user with their ID
        $tutorId = 3;

        for ($i = 1; $i <= 20; $i++) {
            // Ensure at least 2 different types of notifications
            $notificationType = $types[$i % count($types)];

            // DB::table('tutor_notifications')->insert([
            //     'user_id' => $tutorId,
            //     'title' => 'Tutor Notification ' . $i,
            //     'content' => "This is tutor notification {$i} for user ID {$tutorId}.",
            //     'date' => Carbon::now()->subDays(rand(1, 10)),  // Random date from the past 10 days
            //     'type' => $notificationType,
            //     'created_at' => now(),
            //     'updated_at' => now(),
            // ]);


            $randomDate = now()->subDays(rand(1, 10));  // Generate a random date within the past 10 days

            DB::table('tutor_notifications')->insert([
                'user_id' => $tutorId,
                'title' => 'Tutor Notification ' . $i,
                'content' => "This is tutor notification {$i} for user ID {$tutorId}.",
                'type' => $notificationType,
                'created_at' => $randomDate,  // Set created_at to the random date
                'updated_at' => now(),  // Set updated_at to the current date and time
            ]);
        }
    }
}
