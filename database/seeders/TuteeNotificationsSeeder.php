<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TuteeNotification;
use Carbon\Carbon;

class TuteeNotificationsSeeder extends Seeder
{
    public function run()
    {
        $notifications = [
            [
                'title' => 'Venue Change',
                'content' => 'The venue for your tutorial on Advanced Web Technologies with Elanor Pera has been changed, see details.',
                'date' => Carbon::today()->format('Y-m-d'),
                'type' => 'change',

                'read' => false,
            ],
            [
                'title' => 'Scheduled Tutorial',
                'content' => 'You have a scheduled tutorial on Data Structures and Algorithms with tutor Regina Frey on Wednesday, May 1, 2024 (9:00AM - 12:00NN).',
                'date' => Carbon::parse('2024-04-29')->format('Y-m-d'),
                'type' => 'schedule',

                'read' => false,
            ],
            [
                'title' => 'Venue Change',
                'content' => 'The venue for your tutorial on Advanced Web Technologies with Elanor Pera has been changed, see details.',
                'date' => Carbon::today()->format('Y-m-d'),
                'type' => 'change',

                'read' => false,
            ],
            [
                'title' => 'Scheduled Tutorial',
                'content' => 'You have a scheduled tutorial on Data Structures and Algorithms with tutor Regina Frey on Wednesday, May 1, 2024 (9:00AM - 12:00NN).',
                'date' => Carbon::parse('2024-04-29')->format('Y-m-d'),
                'type' => 'schedule',

                'read' => false,
            ],
            [
                'title' => 'Venue Change',
                'content' => 'The venue for your tutorial on Advanced Web Technologies with Elanor Pera has been changed, see details.',
                'date' => Carbon::today()->format('Y-m-d'),
                'type' => 'change',

                'read' => false,
            ],
            [
                'title' => 'Scheduled Tutorial',
                'content' => 'You have a scheduled tutorial on Data Structures and Algorithms with tutor Regina Frey on Wednesday, May 1, 2024 (9:00AM - 12:00NN).',
                'date' => Carbon::parse('2024-04-29')->format('Y-m-d'),
                'type' => 'schedule',

                'read' => false,
            ],
            [
                'title' => 'Venue Change',
                'content' => 'The venue for your tutorial on Advanced Web Technologies with Elanor Pera has been changed, see details.',
                'date' => Carbon::today()->format('Y-m-d'),
                'type' => 'change',

                'read' => false,
            ],
            [
                'title' => 'Scheduled Tutorial',
                'content' => 'You have a scheduled tutorial on Data Structures and Algorithms with tutor Regina Frey on Wednesday, May 1, 2024 (9:00AM - 12:00NN).',
                'date' => Carbon::parse('2024-04-29')->format('Y-m-d'),
                'type' => 'schedule',

                'read' => false,
            ],
            // Add more notifications as needed
        ];

        foreach ($notifications as $notification) {
            TuteeNotification::create($notification);
        }
    }
}

