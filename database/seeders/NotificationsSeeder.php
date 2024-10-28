<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Notification; // Correctly import the Notification model


class NotificationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // In a seeder or test
        Notification::factory()->count(10)->create(); // Use Notification (singular)
    }
}
