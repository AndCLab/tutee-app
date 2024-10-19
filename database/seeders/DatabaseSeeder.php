<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Classes;
use App\Models\ClassRoster;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    protected static ?string $password;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // admin
        Admin::create([
            'password' => static::$password ??= Hash::make('password'),
        ]);

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
        $this->call(ClassesSeeder::class);
        $this->call(ClassRosterSeeder::class);
        // $this->call(TutorSeeder::class);
        // $this->call(TuteeSeeder::class);
    }
}
