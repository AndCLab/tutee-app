<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $table = 'schedules';

    protected $fillable = [
        'initial_start_date',
        'start_time',
        'tutor_id',
        'end_time',
        'never_end',
        'repeat_every',
    ];

    /**
     * Get all of the comments for the Schedule
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function recurring_schedule()
    {
        return $this->hasMany(RecurringSchedule::class);
    }

    public function classes()
    {
        return $this->hasMany(Classes::class, 'schedule_id');
    }

}
