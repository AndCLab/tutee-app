<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'message',
        'sender_id',
        'group_id',
        'receiver_id',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function attachments()
    {
        return $this->hasMany(MessageAttachment::class);
    }

    protected static function booted()
    {
        static::created(function ($message) {
            if ($message->group_id) {
                $group = Group::find($message->group_id);

                if ($group) {
                    $group->last_message_id = $message->id;
                    $group->save();
                }
            }
        });
    }


}
