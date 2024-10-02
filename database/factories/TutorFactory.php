<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Tutor;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tutor>
 */
class TutorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Tutor::class;

    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'bio' => $this->faker->paragraph,
            'work' => $this->faker->company,
            'degree' => json_encode([
                'type' => 'Bachelor',
                'field' => 'Computer Science',
                'year' => 2020,
            ]),
            'verify_status' => 'not_verified',
        ];
    }
}
