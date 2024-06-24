<?php

namespace Database\Seeders;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        //dummy record for stepper testing
        User::factory()->create([
            'fname' => 'Test',
            'lname' => 'User',
            'email' => 'test@example.com',
        ]);
        
        User::factory()->create([
            'id' => 30,
            'fname' => 'Tutee',
            'lname' => 'Example',
            'is_stepper' => 0,
            'user_type' => 'tutee',
            'email' => 'tutee@example.com',
        ]);
        
        User::factory()->create([
            'id' => 31,
            'fname' => 'Tutor',
            'lname' => 'Example',
            'is_stepper' => 0,
            'user_type' => 'tutor',
            'email' => 'tutor@example.com',
        ]);

        $this->call(TutorSeeder::class);
        $this->call(TuteeSeeder::class);
    }
}
