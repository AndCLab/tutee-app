<?php

namespace Database\Factories;

use App\Models\Registration;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Registration>
 */
class RegistrationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Registration::class;

    public function definition(): array
    {
        return [
            'start_date' => Carbon::now()->addWeek(),
            'end_date' => Carbon::now()->addWeek()->addDay(),
        ];
    }
}
