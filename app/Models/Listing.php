<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'business_name',
        'deal_type',
        'industry_id',
        'sub_industry_id',
        'country',
        'region',
        'currency',
        'revenue_range',
        'ebitda_range',
        'asking_price_range',
        'employee_range',
        'year_established',
        'reason_for_sale',
        'description',
        'teaser_path',
        'im_path',
        'nda_required',
        'is_active',
        'business_img',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function industry()
    {
        return $this->belongsTo(Industry::class);
    }
    public function subIndustry()
    {
        return $this->belongsTo(SubIndustry::class);
    }

    public function enquiries()
    {
        return $this->hasMany(Enquiry::class);
    }

    public function chats()
    {
        return $this->hasMany(ChMessage::class, 'listing_id');
    }

    public function getDisplayPriceAttribute(): string
    {
        return price((float)$this->price, (string)$this->currency);
    }
}
