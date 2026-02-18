<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubIndustry extends Model
{
    use HasFactory;

    protected $fillable = ['industry_id', 'name', 'slug'];

    public function industry()
    {
        return $this->belongsTo(Industry::class);
    }
}
