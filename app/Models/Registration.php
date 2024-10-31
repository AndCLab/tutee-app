<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;

    protected $table = 'registrations';

    protected $fillable = [
        'start_date',
        'end_date'
    ];

    public function classes()
    {
        return $this->hasMany(Classes::class, 'registration_id');
    }
}
