<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportContent extends Model
{
    use HasFactory;

    protected $table = 'report_contents';

    protected $fillable = [
        'reporter',
        'class_id',
        'post_id',
        'report_option',
        'comment',
        'status',
        'date_reported',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'date_reported' => 'datetime',
    ];

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter');
    }

    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }
}
