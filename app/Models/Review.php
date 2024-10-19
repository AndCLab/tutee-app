<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $table = 'reviews';

    protected $fillable = [
        'reviewer',
        'class_id',
        'rating',
        'remarks'
    ];

    public function classes()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    public function tutee()
    {
        return $this->belongsTo(Tutee::class, 'tutee_id');
    }

}
