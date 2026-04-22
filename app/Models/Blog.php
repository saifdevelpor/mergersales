<?php

namespace App\Models;

use App\Helpers\SeoHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'details',
        'image',
        'user_id',
        'seo_title',
        'seo_description',
        'slug',
        'og_image',
        'featured_image_alt',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $blog): void {
            if (! $blog->title) {
                $blog->title = self::extractReadableText((string) $blog->details, 255);
            }

            if (! $blog->slug || $blog->isDirty('title')) {
                $blog->slug = SeoHelper::generateUniqueSlug($blog, (string) $blog->title);
            }

            $blog->seo_title = $blog->seo_title ? SeoHelper::sanitizeMeta($blog->seo_title) : $blog->seo_title;
            $blog->seo_description = $blog->seo_description ? SeoHelper::sanitizeMeta($blog->seo_description, 320) : $blog->seo_description;
            $blog->featured_image_alt = $blog->featured_image_alt ? SeoHelper::sanitizeMeta($blog->featured_image_alt) : $blog->featured_image_alt;
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getReadableTitleAttribute(): string
    {
        $title = self::extractReadableText((string) ($this->title ?? ''), 255);

        if ($title !== '') {
            return $title;
        }

        return self::extractReadableText((string) ($this->details ?? ''), 255) ?: 'Untitled Blog';
    }

    public function getReadableSlugAttribute(): string
    {
        if (! empty($this->slug)) {
            return self::extractReadableSlug((string) $this->slug);
        }

        return Str::slug($this->readable_title) ?: 'auto-generated';
    }

    private static function extractReadableText(string $value, int $limit = 255): string
    {
        $decoded = html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $decoded = str_replace("\xc2\xa0", ' ', $decoded);
        $decoded = preg_replace('/&nbsp;/i', ' ', $decoded);
        $decoded = preg_replace('#<style\b[^>]*>.*?</style>#is', ' ', (string) $decoded);
        $decoded = preg_replace('#<script\b[^>]*>.*?</script>#is', ' ', (string) $decoded);
        $decoded = strip_tags((string) $decoded);
        $decoded = preg_replace('/\s+/u', ' ', (string) $decoded);

        return Str::limit(trim((string) $decoded), $limit, '');
    }

    private static function extractReadableSlug(string $value): string
    {
        $text = self::extractReadableText($value, 255);

        return Str::slug($text) ?: trim($value);
    }
}
