<?php

namespace Database\Factories;

use App\Models\RecurringSchedule;
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

    /**
     * Define the factory default data with random values.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tutor_id = fake()->numberBetween(1, 100); // assuming tutor_id is an integer
        $start_time = fake()->dateTimeThisMonth();
        $end_time = Carbon::instance($start_time)->addHours(2); // Example: add 2 hours to start_time

        // Ensure unique start and end times for the same tutor
        while (Schedule::where('tutor_id', $tutor_id)
            ->whereTime('start_time', '<=', $end_time)
            ->whereTime('end_time', '>', $start_time)
            ->exists()) {
            $start_time = fake()->dateTimeThisMonth();
            $end_time = Carbon::instance($start_time)->addHours(2);
        }

        // Conditionally set 'never_end' based on whether recurring schedules exist
        $hasRecurringSchedule = fake()->boolean(50); // 50% chance to have recurring schedules

        $initial_start_date = now()->subDays(5)->addDays(rand(1, 10))->format('Y-m-d');

        return [
            'initial_start_date' => $initial_start_date,
            'start_time' => $start_time,
            'tutor_id' => $tutor_id,
            'end_time' => $end_time,
            'never_end' => !$hasRecurringSchedule, // 'never_end' is true if no recurring schedules
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Schedule $sched) {
            $dates = collect();

            // Generate unique random dates within the next 5 weeks
            while ($dates->count() < 5) {
                $randomDate = now()->addDays(rand(1, 35))->format('Y-m-d');
                $dates->push($randomDate)->unique(); // Ensure uniqueness
            }

            foreach ($dates as $date) {
                $sched->recurring_schedule()->create([
                    'schedule_id' => $sched->id,
                    'dates' => $date,
                ]);
            }
        });
    }

}
