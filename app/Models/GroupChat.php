<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Message;

class GroupChat extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status'
    ];

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
