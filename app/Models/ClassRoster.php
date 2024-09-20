<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassRoster extends Model
{
    use HasFactory;

    protected $table = 'class_rosters';

    protected $fillable = [
        'class_id',
        'tutee_id',
        'proof_of_payment',
        'attendance',
        'payment_status'
    ];

    public function tutees()
    {
        return $this->belongsTo(Tutee::class, 'tutee_id');
    }

    public function classes()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

}
