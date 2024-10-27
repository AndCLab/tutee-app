<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Message;
use App\Models\Conversation;
use App\Models\Group;
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
            'fname' => 'John',
            'lname' => 'Doe',
            'name' => 'John Doe',
            'is_stepper' => 0,
            'address' => 'address',
            'zip_code' => '6015',
            'user_type' => 'tutee',
            'email' => 'john@example.com',
            'password' => bcrypt('password'),
            'phone_prefix' => '+63',
            'phone_number' => '9582935679',
        ]);

        User::factory()->create([
            'fname' => 'Jane',
            'lname' => 'Doe',
            'name' => 'Jane Doe',
            'is_stepper' => 0,
            'address' => 'address',
            'zip_code' => '6015',
            'user_type' => 'tutor',
            'email' => 'jane@example.com',
            'password' => bcrypt('password'),
            'phone_prefix' => '+63',
            'phone_number' => '9584638379',
        ]);

        // Fields::factory()->create([
        //     'user_id' => $user->id,
        //     'field_name' => fake()->randomElement($fields),
        //     'active_in' => $user->user_type
        // ]);

        User::factory()->create([
            'fname' => 'Tutee',
            'lname' => 'Example',
            'name' => 'Tutee Example',
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
            'name' => 'Tutor Example',
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

        // Adding the Tutor and Tutee Notification seeders
        $this->call(TutorNotificationsSeeder::class);
        $this->call(TuteeNotificationsSeeder::class);

        // $this->call(ClassesSeeder::class);
        // $this->call(ClassRosterSeeder::class);

        User::factory(10)->create();

        for($i = 0; $i < 8; $i++){
            $group = Group::factory()->create(['owner_id' => 1,]);

            $users = User::inRandomOrder()->limit(rand(2,5))->pluck('id');
            $group->users()->attach(array_unique([1, ...$users]));
        }

        Message::factory(1000)->create();
        $messages = Message::whereNull('group_id')->orderBy('created_at')->get();

        $conversations = $messages->groupBy(function ($message) {
            return collect([$message->sender_id, $message->receiver_id])->sort()->implode('_');
        })->map(function ($groupedMessages) {
            return [
                'user_id1' => $groupedMessages->first()->sender_id,
                'user_id2' => $groupedMessages->first()->receiver_id,
                'last_message_id' => $groupedMessages->last()->id,
                'created_at' => new Carbon(),
                'updated_at' => new Carbon(),
            ];
        })->values();

        Conversation::insertOrIgnore($conversations->toArray());
    }
}
