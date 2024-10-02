<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $casts = [
        'data' => 'array', // To cast 'data' field as array
        'created_at' => 'datetime', // To cast 'created_at' as datetime
    ];

    // Remove this line since $listeners are used in Livewire components, not Eloquent models.
    // protected $listeners = ['load-more' => 'loadNotifications'];

    // Define polymorphic relation
    public function notifiable()
    {
        return $this->morphTo();
    }
}
