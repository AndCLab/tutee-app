<?php

namespace App\Console\Commands;

use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateRecurringScheduleDates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:recurring-schedule-dates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update dates for recurring schedules based on repeat conditions';

    /*
        run this command:
        php artisan schedule:work

        or

        php artisan update:recurring-schedule-dates
    */

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get all schedules with `never_end` set to 1
        $schedules = Schedule::where('never_end', 1)->get();

        foreach ($schedules as $schedule) {
            if (Carbon::parse($schedule->initial_start_date)->lessThan(Carbon::now()->format('Y-m-d'))) {
                $initialDate = Carbon::parse($schedule->initial_start_date);

                // Determine the increment based on repeat_every value
                switch ($schedule->repeat_every) {
                    case 'days':
                        $newDate = $initialDate->addDay();
                        break;

                    case 'weeks':
                        $newDate = $initialDate->addWeek();
                        break;

                    case 'months':
                        $newDate = $initialDate->addMonth();
                        break;

                    case 'weekdays':
                        // Skip weekends
                        $newDate = $initialDate;
                        do {
                            $newDate->addDay();
                        } while ($newDate->isWeekend());
                        break;

                    default:
                        continue 2;
                }

                // Update or create a new recurring schedule record
                $schedule->recurring_schedule()->update(['dates' => $newDate->format('Y-m-d')]);
                $schedule->update(['initial_start_date' => $newDate->format('Y-m-d')]);

                $this->info("Updated dates for Schedule ID: {$schedule->id} to {$newDate->format('F j, Y')}");
            }
        }
    }
}
