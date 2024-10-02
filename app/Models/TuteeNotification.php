<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TuteeNotification extends Model
{
    protected $fillable = ['user_id', 'title', 'content', 'type', 'read', 'read_at'];
    public $timestamps = false; // Disable automatic timestamps
    // Relationship back to the user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

