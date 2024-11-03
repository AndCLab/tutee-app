<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tutor extends Model
{
    use HasFactory;

    protected $table = 'tutor';

    protected $fillable = [
        'user_id',
        'bio',
        'work',
        'degree',
        'verify_status',
        'average_rating',
    ];

    protected $casts = [
        'is_verified' => 'string',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function classes()
    {
        return $this->hasMany(Classes::class);
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class, 'tutor_id');
    }

    public function resume()
    {
        return $this->hasOne(Resume::class, 'tutor_id');
    }

    public function works()
    {
        return $this->hasMany(Work::class, 'tutor_id');
    }

    public function scopeSearch($query, $term)
    {
        return $query->whereHas('user', function ($q) use ($term) {
                            $q->where('fname', 'like', "%{$term}%")
                                ->orWhere('lname', 'like', "%{$term}%")
                                ->orWhere('name', 'like', "%{$term}%")
                                ->orWhere('email', 'like', "%{$term}%");
                        });
    }
}
