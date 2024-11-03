<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'notifiable_id',
        'notifiable_type',
        'user_id', // Ensure this is included
        'class_id',
        'class_roster_id', // Corrected from class_roster to class_roster_id
        'recurring_schedule_id', // Add this line
        'post_id',
        'review_id',
        'report_content_id',
        'blacklist_id',
        'title',
        'content',
        'read',
        'read_at',
        'type',
        'role',
    ];

    // Define relationships and other model methods here
}
