<?php

namespace Database\Factories;

use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Schedule>
 */
class ScheduleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Schedule::class;

    public function definition(): array
    {
        return [
            'start_date' => Carbon::now()->addMonth(),
            'end_date' => Carbon::now()->addMonth()->addWeek(),
        ];
    }
}
