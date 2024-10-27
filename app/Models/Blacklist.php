<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blacklist extends Model
{
    use HasFactory;

    protected $table = 'blacklists';

    protected $fillable = [
        'reported_user_id',
        'blocked_at',
        'report_count',
        'request_status',
    ];

    public function reportedUser()
    {
        return $this->belongsTo(User::class, 'reported_user_id');
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

    public function scopeSearch($query, $term)
    {
        return $query->whereHas('reportedUser', function ($q) use ($term) {
            $q->where('fname', 'like', "%{$term}%")
            ->orWhere('lname', 'like', "%{$term}%")
            ->orWhere('email', 'like', "%{$term}%");
        });
    }
}
