<?php

namespace Database\Seeders;

use App\Models\Fields;
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
            'is_stepper' => 1,
            'user_type' => null,
            'email' => 'test@example.com',
            'address' => 'address',
            'zip_code' => '6015',
            'phone_prefix' => '+63',
            'phone_number' => '9582938479',
        ]);

        // Fields::factory()->create([
        //     'user_id' => $user->id,
        //     'field_name' => fake()->randomElement($fields),
        //     'active_in' => $user->user_type
        // ]);

        User::factory()->create([
            'fname' => 'Tutee',
            'lname' => 'Example',
            'is_stepper' => 0,
            'user_type' => 'tutee',
            'email' => 'tutee@example.com',
            'address' => 'address',
            'zip_code' => '6015',
            'phone_prefix' => '+63',
            'phone_number' => '9582938379',
        ]);

        User::factory()->create([
            'fname' => 'Tutor',
            'lname' => 'Example',
            'is_stepper' => 0,
            'user_type' => 'tutor',
            'email' => 'tutor@example.com',
            'address' => 'address',
            'zip_code' => '6015',
            'phone_prefix' => '+63',
            'phone_number' => '9576238479',
        ]);

        $this->call(UserSeeder::class);
        $this->call(FieldsSeeder::class);
        $this->call(TutorSeeder::class);
        $this->call(TuteeSeeder::class);

        // Adding the Tutor and Tutee Notification seeders
        $this->call(TutorNotificationsSeeder::class);
        $this->call(TuteeNotificationsSeeder::class);
        
        // $this->call(ClassesSeeder::class);
        // $this->call(ClassRosterSeeder::class);

    }
}
