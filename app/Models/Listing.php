<?php

namespace App\Models;

use App\Helpers\SeoHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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
        'status',
        'seo_title',
        'seo_description',
        'slug',
        'focus_keyword',
        'og_image',
        'schema_json',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $listing): void {
            if (! $listing->slug || $listing->isDirty('business_name')) {
                $listing->slug = SeoHelper::generateUniqueSlug($listing, (string) $listing->business_name);
            }

            $listing->seo_title = $listing->seo_title ? SeoHelper::sanitizeMeta($listing->seo_title) : $listing->seo_title;
            $listing->seo_description = $listing->seo_description ? SeoHelper::sanitizeMeta($listing->seo_description, 320) : $listing->seo_description;
            $listing->focus_keyword = $listing->focus_keyword ? SeoHelper::sanitizeMeta($listing->focus_keyword, 120) : $listing->focus_keyword;
            $listing->schema_json = SeoHelper::sanitizeSchemaJson($listing->schema_json) ?: $listing->schema_json;
        });
    }

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

    public function getExcerptAttribute(): string
    {
        return Str::limit(strip_tags((string) $this->description), 160, '');
    }
}
