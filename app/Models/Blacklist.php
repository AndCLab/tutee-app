<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blacklist extends Model
{
    use HasFactory;

    protected $table = 'blacklists';

    protected $fillable = [
        'reported_user',
        'blocked_at',
        'report_count',
    ];

    public function reportedUser()
    {
        return $this->belongsTo(User::class, 'reported_user');
    }

    // scopes
    public function scopeBlocked($query)
    {
        return $query->whereNotNull('blocked_at');
    }

    public function scopeNotBlocked($query)
    {
        return $query->whereNull('blocked_at');
    }
}
