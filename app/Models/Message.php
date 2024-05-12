<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\GroupChat;
use App\Models\User;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'render',
        'user_id',
        'group_chat_id'
    ];

    public function group_chat()
    {
        return $this->belongsTo(GroupChat::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
