<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $casts = [
        'date' => 'datetime', // Add this line
    ];

    protected $listeners = ['load-more' => 'loadNotifications'];

}
