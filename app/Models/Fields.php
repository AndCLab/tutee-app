<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User;

class Fields extends Model
{
    use HasFactory;

    protected $table = 'fields';

    protected $fillable = ['user_id', 'field_name', 'class_count'];

    /**
     * Get all of the comments for the Fields
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fields(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
