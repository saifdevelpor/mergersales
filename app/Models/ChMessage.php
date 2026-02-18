<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Chatify\Traits\UUID;

class ChMessage extends Model
{
    use UUID;

    public $incrementing = false; // UUID is not auto-increment
    protected $keyType = 'string'; // Primary key is string

    // allow mass assignment for these fields
    protected $fillable = [
        'from_id',
        'to_id',
        'listing_id',
        'body',
        'attachment',
        'seen',
    ];
}
