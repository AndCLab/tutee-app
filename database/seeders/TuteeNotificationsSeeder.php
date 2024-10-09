<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TuteeNotificationsSeeder extends Seeder
{
    public function run()
    {
        // Restrict notification types
        $types = ['venue', 'schedule', 'assignment'];
        // Define tutee users with their IDs
        $tutees = [3,1];

        foreach ($tutees as $userId) {
            for ($i = 1; $i <= 20; $i++) {
                // Ensure at least 2 different types of notifications per user
                $notificationType = $types[$i % count($types)];

                // DB::table('tutee_notifications')->insert([
                //     'user_id' => $userId,
                //     'title' => 'Tutee Notification ' . $i,
                //     'content' => "This is tutee notification {$i} for user ID {$userId}.",
                //     'date' => Carbon::now()->subDays(rand(1, 10)),  // Random date from the past 10 days
                //     'type' => $notificationType,
                //     'created_at' => now(),
                //     'updated_at' => now(),
                // ]);

                $randomDate = now()->subDays(rand(1, 10));  // Generate a random date within the past 10 days

                DB::table('tutee_notifications')->insert([
                    'user_id' => $userId,
                    'title' => 'Tutee Notification ' . $i,
                    'content' => "This is tutee notification {$i} for user ID {$userId}.",
                    'type' => $notificationType,
                    'created_at' => $randomDate,  // Set created_at to the random date
                    'updated_at' => now(),  // Set updated_at to the current date and time
                ]);

            }
        }
    }
}
