<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $table = 'posts';

    protected $fillable = [
        'tutee_id',
        'post_desc',
        'class_fields',
        'class_date',
        'class_fee',
        'class_category',
        'class_type',
        'class_location',
    ];

    public function tutees()
    {
        return $this->belongsTo(Tutee::class, 'tutee_id');
    }
}
