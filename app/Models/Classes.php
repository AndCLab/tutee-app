<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    use HasFactory;

    protected $table = 'classes';

    protected $fillale = [
        'tutor_id',
        'class_name',
        'class_description',
        'class_type',
        'class_location',
        'class_fee',
        'class_status',
        'schedule_id'
    ];
}
