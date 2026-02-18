<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'from_id',
        'to_id',
        'listing_id',
        'message_id',
        'seen'
    ];

    public function from()
    {
        return $this->belongsTo(User::class, 'from_id');
    }
}
