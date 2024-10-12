<?php

namespace App\Console\Commands;

use App\Models\Classes;
use App\Models\RecurringSchedule;
use App\Models\Schedule;
use App\Models\Tutor;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CloseExpiredClassSchedules extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'class:close-expired';

    /**
     * The console command description.
     *
     * @var string
     */

    protected $description = 'Close class schedules that have passed their end date';


    /*
        run this command:
        php artisan schedule:work
    */

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // fetch all tutors, or target specific tutors as needed
        $tutors = Tutor::all();

        foreach ($tutors as $tutor) {
            // get the most recent recurring schedule for the tutor
            $reccur = RecurringSchedule::whereHas('schedules', function($query) use ($tutor) {
                $query->where('tutor_id', $tutor->id);
            })->orderBy('dates', 'desc')->first();

            // initialize expiredSchedules
            $expiredSchedules = collect(); // Using a collection for easy merging

            if ($reccur && $reccur->dates <= Carbon::now()) {
                $expiredSchedules = Schedule::where('tutor_id', $tutor->id)
                                            ->whereTime('end_time', '<=', Carbon::now())
                                            ->whereHas('classes', function ($query) {
                                                $query->where('class_status', '=', 1);
                                            })
                                            ->where('never_end', '!=', 1)
                                            ->get();
            }

            // updating class statuses
            foreach ($expiredSchedules as $schedule) {
                foreach ($schedule->classes as $class) {
                    $class->class_status = 0;
                }
            }

            // save all classes at once to reduce database calls
            if ($expiredSchedules->isNotEmpty()) {
                // assuming classes are a relationship on Schedule
                $classesToUpdate = $expiredSchedules->flatMap(function($schedule) {
                    return $schedule->classes;
                });

                $classesToUpdate->each(function($class) {
                    $class->class_status = 0;
                });

                // update all classes in a single query
                $classIds = $classesToUpdate->pluck('id');
                Classes::whereIn('id', $classIds)->update(['class_status' => 0]);
            }
        }

        $this->info('Expired class schedules have been closed.');
    }

}
