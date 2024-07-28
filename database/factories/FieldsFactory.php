<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Fields;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Fields>
 */
class FieldsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Fields::class;

    public function definition(): array
    {

        $fields = [
            'Mathematics',
            'Physics',
            'Chemistry',
            'Biology',
            'Computer Science',
            'Engineering',
            'Medicine',
            'Psychology',
            'Economics',
            'Sociology',
            'History',
            'Geography',
            'Political Science',
            'Philosophy',
            'Arts',
            'Literature',
            'Law',
            'Business Administration',
            'Statistics',
            'Environmental Science',
            'Architecture',
            'Music',
            'Theatre',
            'Cultural Studies',
            'Religious Studies',
            'Anthropology',
            'Public Health',
            'Criminal Justice',
            'Journalism',
            'Education',
            'Social Work',
            'International Relations',
            'Mathematical Finance',
            'Nursing',
            'Biochemistry',
            'Neuroscience',
            'Veterinary Medicine',
            'Marine Biology',
            'Astronomy',
            'Astronautics'
        ];


        return [
            'user_id' => $this->faker->numberBetween(1, 3),
            'field_name' => $this->faker->randomElement($fields)
        ];
    }
}
