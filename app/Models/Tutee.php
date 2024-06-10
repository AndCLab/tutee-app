<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tutee extends Model
{
    use HasFactory;

    protected $table = 'tutee';

    protected $fillable = ['user_id', 'grade_level'];
}
