<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'fname',
        'lname',
        'email',
        'address',
        'zip_code',
        'phone_prefix',
        'phone_number',

        'is_stepper',
        'is_applied',
        'user_type',
        'avatar',
        'password',
        'google_id',
        'facebook_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function tutors()
    {
        return $this->hasMany(Tutor::class);
    }

    public function tutees()
    {
        return $this->hasMany(Tutee::class);
    }
    

    // Define the polymorphic relationship
    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }
    
    public function getNotifications()
    {
        return $this->notifications()->orderBy('created_at', 'desc')->get();
    }

    public function updateNotifications()
    {
        if ($this->user_type == 'tutor') {
            $this->tuteeNotifications()->delete();
            $this->tutorNotifications()->createMany($this->notifications()->get());
        } elseif ($this->user_type == 'tutee') {
            $this->tutorNotifications()->delete();
            $this->tuteeNotifications()->createMany($this->notifications()->get());
        }
    }
    
    public function fields()
    {
        return $this->hasMany(Fields::class);
    }
}
