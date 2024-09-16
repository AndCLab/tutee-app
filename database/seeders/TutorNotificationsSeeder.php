<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TutorNotification;
use Carbon\Carbon;

class TutorNotificationsSeeder extends Seeder
{
    public function run()
    {
        $notifications = [
            [
                'title' => 'New Assignment',
                'content' => 'You have been assigned to a new tutorial session on Advanced Web Technologies with Elanor Pera, see details.',
                'date' => Carbon::today()->subDay()->format('Y-m-d'),
                'type' => 'assignment',
                'read' => false, // Add the read field
            ],
            [
                'title' => 'Scheduled Tutorial',
                'content' => 'You have a scheduled tutorial to teach Data Structures and Algorithms with student Regina Frey on Wednesday, May 1, 2024 (9:00AM - 12:00NN).',
                'date' => Carbon::parse('2024-09-10')->format('Y-m-d'),
                'type' => 'schedule',
                'read' => false, // Add the read field
            ],
            [
                'title' => 'New Assignment',
                'content' => 'You have been assigned to a new tutorial session on Advanced Web Technologies with Elanor Pera, see details.',
                'date' => Carbon::today()->subDay()->format('Y-m-d'),
                'type' => 'assignment',
                'read' => false, // Add the read field
            ],
            [
                'title' => 'Scheduled Tutorial',
                'content' => 'You have a scheduled tutorial to teach Data Structures and Algorithms with student Regina Frey on Wednesday, May 1, 2024 (9:00AM - 12:00NN).',
                'date' => Carbon::parse('2024-09-10')->format('Y-m-d'),
                'type' => 'schedule',
                'read' => false, // Add the read field
            ],
            [
                'title' => 'New Assignment',
                'content' => 'You have been assigned to a new tutorial session on Advanced Web Technologies with Elanor Pera, see details.',
                'date' => Carbon::today()->subDay()->format('Y-m-d'),
                'type' => 'assignment',
                'read' => false, // Add the read field
            ],
            [
                'title' => 'Scheduled Tutorial',
                'content' => 'You have a scheduled tutorial to teach Data Structures and Algorithms with student Regina Frey on Wednesday, May 1, 2024 (9:00AM - 12:00NN).',
                'date' => Carbon::parse('2024-09-10')->format('Y-m-d'),
                'type' => 'schedule',
                'read' => false, // Add the read field
            ],
            [
                'title' => 'New Assignment',
                'content' => 'You have been assigned to a new tutorial session on Advanced Web Technologies with Elanor Pera, see details.',
                'date' => Carbon::today()->subDay()->format('Y-m-d'),
                'type' => 'assignment',
                'read' => false, // Add the read field
            ],
            [
                'title' => 'Scheduled Tutorial',
                'content' => 'You have a scheduled tutorial to teach Data Structures and Algorithms with student Regina Frey on Wednesday, May 1, 2024 (9:00AM - 12:00NN).',
                'date' => Carbon::parse('2024-09-10')->format('Y-m-d'),
                'type' => 'schedule',
                'read' => false, // Add the read field
            ],
            [
                'title' => 'New Assignment',
                'content' => 'You have been assigned to a new tutorial session on Advanced Web Technologies with Elanor Pera, see details.',
                'date' => Carbon::today()->subDay()->format('Y-m-d'),
                'type' => 'assignment',
                'read' => false, // Add the read field
            ],
            [
                'title' => 'Scheduled Tutorial',
                'content' => 'You have a scheduled tutorial to teach Data Structures and Algorithms with student Regina Frey on Wednesday, May 1, 2024 (9:00AM - 12:00NN).',
                'date' => Carbon::parse('2024-09-10')->format('Y-m-d'),
                'type' => 'schedule',
                'read' => false, // Add the read field
            ],
            [
                'title' => 'New Assignment',
                'content' => 'You have been assigned to a new tutorial session on Advanced Web Technologies with Elanor Pera, see details.',
                'date' => Carbon::today()->subDay()->format('Y-m-d'),
                'type' => 'assignment',
                'read' => false, // Add the read field
            ],
            [
                'title' => 'Scheduled Tutorial',
                'content' => 'You have a scheduled tutorial to teach Data Structures and Algorithms with student Regina Frey on Wednesday, May 1, 2024 (9:00AM - 12:00NN).',
                'date' => Carbon::parse('2024-09-10')->format('Y-m-d'),
                'type' => 'schedule',
                'read' => false, // Add the read field
            ],
            [
                'title' => 'New Assignment',
                'content' => 'You have been assigned to a new tutorial session on Advanced Web Technologies with Elanor Pera, see details.',
                'date' => Carbon::today()->subDay()->format('Y-m-d'),
                'type' => 'assignment',
                'read' => false, // Add the read field
            ],
            [
                'title' => 'Scheduled Tutorial',
                'content' => 'You have a scheduled tutorial to teach Data Structures and Algorithms with student Regina Frey on Wednesday, May 1, 2024 (9:00AM - 12:00NN).',
                'date' => Carbon::parse('2024-09-10')->format('Y-m-d'),
                'type' => 'schedule',
                'read' => false, // Add the read field
            ],
            [
                'title' => 'New Assignment',
                'content' => 'You have been assigned to a new tutorial session on Advanced Web Technologies with Elanor Pera, see details.',
                'date' => Carbon::today()->subDay()->format('Y-m-d'),
                'type' => 'assignment',
                'read' => false, // Add the read field
            ],
            [
                'title' => 'Scheduled Tutorial',
                'content' => 'You have a scheduled tutorial to teach Data Structures and Algorithms with student Regina Frey on Wednesday, May 1, 2024 (9:00AM - 12:00NN).',
                'date' => Carbon::parse('2024-09-10')->format('Y-m-d'),
                'type' => 'schedule',
                'read' => false, // Add the read field
            ],
            [
                'title' => 'New Assignment',
                'content' => 'You have been assigned to a new tutorial session on Advanced Web Technologies with Elanor Pera, see details.',
                'date' => Carbon::today()->subDay()->format('Y-m-d'),
                'type' => 'assignment',
                'read' => false, // Add the read field
            ],
            [
                'title' => 'Scheduled Tutorial',
                'content' => 'You have a scheduled tutorial to teach Data Structures and Algorithms with student Regina Frey on Wednesday, May 1, 2024 (9:00AM - 12:00NN).',
                'date' => Carbon::parse('2024-09-10')->format('Y-m-d'),
                'type' => 'schedule',
                'read' => false, // Add the read field
            ],
            [
                'title' => 'New Assignment',
                'content' => 'You have been assigned to a new tutorial session on Advanced Web Technologies with Elanor Pera, see details.',
                'date' => Carbon::today()->subDay()->format('Y-m-d'),
                'type' => 'assignment',
                'read' => false, // Add the read field
            ],
            [
                'title' => 'Scheduled Tutorial',
                'content' => 'You have a scheduled tutorial to teach Data Structures and Algorithms with student Regina Frey on Wednesday, May 1, 2024 (9:00AM - 12:00NN).',
                'date' => Carbon::parse('2024-09-10')->format('Y-m-d'),
                'type' => 'schedule',
                'read' => false, // Add the read field
            ],
            [
                'title' => 'New Assignment',
                'content' => 'You have been assigned to a new tutorial session on Advanced Web Technologies with Elanor Pera, see details.',
                'date' => Carbon::today()->subDay()->format('Y-m-d'),
                'type' => 'assignment',
                'read' => false, // Add the read field
            ],
            [
                'title' => 'Scheduled Tutorial',
                'content' => 'You have a scheduled tutorial to teach Data Structures and Algorithms with student Regina Frey on Wednesday, May 1, 2024 (9:00AM - 12:00NN).',
                'date' => Carbon::parse('2024-09-10')->format('Y-m-d'),
                'type' => 'schedule',
                'read' => false, // Add the read field
            ],
            [
                'title' => 'New Assignment',
                'content' => 'You have been assigned to a new tutorial session on Advanced Web Technologies with Elanor Pera, see details.',
                'date' => Carbon::today()->subDay()->format('Y-m-d'),
                'type' => 'assignment',
                'read' => false, // Add the read field
            ],
            [
                'title' => 'Scheduled Tutorial',
                'content' => 'You have a scheduled tutorial to teach Data Structures and Algorithms with student Regina Frey on Wednesday, May 1, 2024 (9:00AM - 12:00NN).',
                'date' => Carbon::parse('2024-09-10')->format('Y-m-d'),
                'type' => 'schedule',
                'read' => false, // Add the read field
            ],
            [
                'title' => 'New Assignment',
                'content' => 'You have been assigned to a new tutorial session on Advanced Web Technologies with Elanor Pera, see details.',
                'date' => Carbon::today()->subDay()->format('Y-m-d'),
                'type' => 'assignment',
                'read' => false, // Add the read field
            ],
            [
                'title' => 'Scheduled Tutorial',
                'content' => 'You have a scheduled tutorial to teach Data Structures and Algorithms with student Regina Frey on Wednesday, May 1, 2024 (9:00AM - 12:00NN).',
                'date' => Carbon::parse('2024-09-10')->format('Y-m-d'),
                'type' => 'schedule',
                'read' => false, // Add the read field
            ],
            [
                'title' => 'New Assignment',
                'content' => 'You have been assigned to a new tutorial session on Advanced Web Technologies with Elanor Pera, see details.',
                'date' => Carbon::today()->subDay()->format('Y-m-d'),
                'type' => 'assignment',
                'read' => false, // Add the read field
            ],
            [
                'title' => 'Scheduled Tutorial',
                'content' => 'You have a scheduled tutorial to teach Data Structures and Algorithms with student Regina Frey on Wednesday, May 1, 2024 (9:00AM - 12:00NN).',
                'date' => Carbon::parse('2024-09-10')->format('Y-m-d'),
                'type' => 'schedule',
                'read' => false, // Add the read field
            ],
            [
                'title' => 'New Assignment',
                'content' => 'You have been assigned to a new tutorial session on Advanced Web Technologies with Elanor Pera, see details.',
                'date' => Carbon::today()->subDay()->format('Y-m-d'),
                'type' => 'assignment',
                'read' => false, // Add the read field
            ],
            [
                'title' => 'Scheduled Tutorial',
                'content' => 'You have a scheduled tutorial to teach Data Structures and Algorithms with student Regina Frey on Wednesday, May 1, 2024 (9:00AM - 12:00NN).',
                'date' => Carbon::parse('2024-09-10')->format('Y-m-d'),
                'type' => 'schedule',
                'read' => false, // Add the read field
            ],
            [
                'title' => 'New Assignment',
                'content' => 'You have been assigned to a new tutorial session on Advanced Web Technologies with Elanor Pera, see details.',
                'date' => Carbon::today()->subDay()->format('Y-m-d'),
                'type' => 'assignment',
                'read' => false, // Add the read field
            ],
            [
                'title' => 'Scheduled Tutorial',
                'content' => 'You have a scheduled tutorial to teach Data Structures and Algorithms with student Regina Frey on Wednesday, May 1, 2024 (9:00AM - 12:00NN).',
                'date' => Carbon::parse('2024-09-10')->format('Y-m-d'),
                'type' => 'schedule',
                'read' => false, // Add the read field
            ],

            // Add more notifications as needed
        ];

        foreach ($notifications as $notification) {
            TutorNotification::create($notification);
        }
    }
}

