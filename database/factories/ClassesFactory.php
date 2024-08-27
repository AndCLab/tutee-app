<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Classes;
use App\Models\Fields;
use App\Models\Registration;
use App\Models\Schedule;
use App\Models\Tutor;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Classes>
 */
class ClassesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Classes::class;

    public function definition(): array
    {
        $is_group = fake()->randomElement(['individual', 'group']);

        return [
            'tutor_id' => Tutor::inRandomOrder()->first()->id,
            'class_name' => fake()->words(3, true),
            'class_description' => fake()->paragraph(),
            'class_fields' => json_encode(Fields::inRandomOrder()->limit(3)->pluck('field_name')->toArray()),
            'class_type' => fake()->randomElement(['virtual', 'physical']),
            'class_category' => $is_group,
            'class_location' => fake()->address(),
            'class_students' => $is_group == 'group' ? fake()->numberBetween(2, 30) : 1,
            'class_fee' => fake()->randomFloat(2, 0, 1000),
            'class_status' => fake()->boolean(90), // 90% chance of being "opened"
            'schedule_id' => Schedule::factory()->create()->id,
            'registration_id' => Registration::factory()->create()->id,
        ];
    }
}
