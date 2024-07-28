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
            'lname' => 'User',
            'email' => 'test@example.com',
            'address' => 'address',
            'zip_code' => '6015',
            'phone_prefix' => '+63',
            'phone_number' => '9582938479',
        ]);

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

        $this->call(FieldsSeeder::class);
        $this->call(TutorSeeder::class);
        $this->call(TuteeSeeder::class);
    }
}
