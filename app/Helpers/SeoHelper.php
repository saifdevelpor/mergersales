<?php

namespace App\Helpers;

use App\Models\Blog;
use App\Models\Industry;
use App\Models\Listing;
use App\Models\Page;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SeoHelper
{
    public static function forPage(?Page $page = null, array $overrides = []): array
    {
        $cacheKey = 'seo:page:' . ($page?->id ?? 'default') . ':' . ($page?->updated_at?->timestamp ?? '0') . ':' . md5(json_encode($overrides));

        return Cache::remember($cacheKey, now()->addHours(6), function () use ($page, $overrides) {
            return self::build(
                [
                    'title' => $page?->meta_title,
                    'description' => $page?->meta_description,
                    'canonical' => $page?->canonical_url,
                    'og_title' => $page?->og_title,
                    'og_description' => $page?->og_description,
                    'og_image' => $page?->og_image,
                    'robots_index' => $page?->robots_index,
                    'robots_follow' => $page?->robots_follow,
                    'schema_type' => $page?->schema_type,
                ],
                $overrides
            );
        });
    }

    public static function forListing(Listing $listing, array $overrides = []): array
    {
        $cacheKey = 'seo:listing:' . $listing->id . ':' . $listing->updated_at?->timestamp . ':' . md5(json_encode($overrides));

        return Cache::remember($cacheKey, now()->addHours(6), function () use ($listing, $overrides) {
            return self::build([
                'title' => $listing->seo_title ?: $listing->business_name,
                'description' => $listing->seo_description ?: Str::limit(strip_tags((string) $listing->description), 155, ''),
                'canonical' => route('seo.business.show', $listing->slug),
                'og_title' => $listing->seo_title ?: $listing->business_name,
                'og_description' => $listing->seo_description ?: Str::limit(strip_tags((string) $listing->description), 200, ''),
                'og_image' => $listing->og_image ?: ($listing->business_img ? asset('storage/' . ltrim($listing->business_img, '/')) : null),
                'schema_type' => 'Offer',
            ], $overrides);
        });
    }

    public static function forBlog(Blog $blog, array $overrides = []): array
    {
        $cacheKey = 'seo:blog:' . $blog->id . ':' . $blog->updated_at?->timestamp . ':' . md5(json_encode($overrides));

        return Cache::remember($cacheKey, now()->addHours(6), function () use ($blog, $overrides) {
            $title = $blog->seo_title ?: $blog->title ?: Str::limit(strip_tags((string) $blog->details), 60, '');

            return self::build([
                'title' => $title,
                'description' => $blog->seo_description ?: Str::limit(strip_tags((string) $blog->details), 155, ''),
                'canonical' => route('seo.blog.show', $blog->slug),
                'og_title' => $title,
                'og_description' => $blog->seo_description ?: Str::limit(strip_tags((string) $blog->details), 200, ''),
                'og_image' => $blog->og_image ?: ($blog->image ? asset($blog->image) : null),
                'schema_type' => 'Article',
            ], $overrides);
        });
    }

    public static function forIndustry(Industry $industry, array $overrides = []): array
    {
        return self::build([
            'title' => $industry->meta_title ?: $industry->name . ' Businesses for Sale',
            'description' => $industry->meta_description ?: 'Explore businesses for sale in the ' . $industry->name . ' industry.',
            'canonical' => route('seo.industry.show', $industry->slug),
            'og_title' => $industry->meta_title ?: $industry->name . ' Businesses for Sale',
            'og_description' => $industry->meta_description ?: 'Explore businesses for sale in the ' . $industry->name . ' industry.',
            'og_image' => $industry->og_image,
            'schema_type' => 'CollectionPage',
        ], $overrides);
    }

    public static function build(array $base = [], array $overrides = []): array
    {
        $defaults = config('seo.defaults');
        $data = array_merge($base, $overrides);
        $title = self::sanitizeMeta($data['title'] ?? $defaults['title']);
        $description = self::sanitizeMeta($data['description'] ?? $defaults['description'], 320);
        $canonical = $data['canonical'] ?? url()->current();
        $ogImage = self::imageUrl($data['og_image'] ?? $defaults['og_image']);
        $ogTitle = self::sanitizeMeta($data['og_title'] ?? $title);
        $ogDescription = self::sanitizeMeta($data['og_description'] ?? $description, 320);
        $robotsIndex = Arr::exists($data, 'robots_index') ? (bool) $data['robots_index'] : true;
        $robotsFollow = Arr::exists($data, 'robots_follow') ? (bool) $data['robots_follow'] : true;

        return [
            'title' => $title,
            'description' => $description,
            'canonical' => $canonical,
            'robots' => ($robotsIndex ? 'index' : 'noindex') . ',' . ($robotsFollow ? 'follow' : 'nofollow'),
            'og' => [
                'title' => $ogTitle,
                'description' => $ogDescription,
                'image' => $ogImage,
                'url' => $canonical,
                'type' => strtolower((string) ($data['schema_type'] ?? 'website')) === 'article' ? 'article' : 'website',
            ],
            'twitter' => [
                'card' => config('seo.defaults.twitter_card', 'summary_large_image'),
                'title' => $ogTitle,
                'description' => $ogDescription,
                'image' => $ogImage,
            ],
            'schema_type' => $data['schema_type'] ?? null,
        ];
    }

    public static function sanitizeMeta(?string $value, int $limit = 255): string
    {
        return Str::limit(trim(strip_tags((string) $value)), $limit, '');
    }

    public static function sanitizeSchemaJson(?string $json): ?string
    {
        if (! $json) {
            return null;
        }

        $decoded = json_decode($json, true);

        return json_last_error() === JSON_ERROR_NONE
            ? json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
            : null;
    }

    public static function generateUniqueSlug(Model $model, string $source, string $field = 'slug'): string
    {
        $base = Str::slug($source);
        $slug = $base !== '' ? $base : Str::slug(class_basename($model)) . '-' . ($model->getKey() ?: Str::random(6));
        $original = $slug;
        $counter = 2;

        while (
            $model->newQuery()
                ->where($field, $slug)
                ->when($model->exists, fn ($query) => $query->whereKeyNot($model->getKey()))
                ->exists()
        ) {
            $slug = $original . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    public static function organizationSchema(): array
    {
        return array_filter([
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => config('seo.defaults.organization_name'),
            'url' => url('/'),
            'logo' => self::imageUrl(config('seo.defaults.organization_logo')),
            'sameAs' => config('seo.defaults.organization_same_as'),
        ]);
    }

    public static function imageUrl(?string $value): ?string
    {
        if (! $value) {
            return null;
        }

        if (Str::startsWith($value, ['http://', 'https://'])) {
            return $value;
        }

        if (Str::startsWith($value, ['storage/', '/storage/', 'uploads/', '/uploads/', 'assets/', '/assets/'])) {
            return self::absolutePathUrl($value);
        }

        if (Storage::disk('public')->exists($value)) {
            return self::absolutePathUrl('public/storage/' . ltrim($value, '/'));
        }

        if (Str::startsWith($value, '/')) {
            return url($value);
        }

        return self::absolutePathUrl($value);
    }

    private static function absolutePathUrl(string $path): string
    {
        $path = ltrim($path, '/');

        if (app()->bound('request')) {
            $request = request();

            return rtrim($request->getSchemeAndHttpHost() . $request->getBaseUrl(), '/') . '/' . $path;
        }

        return url($path);
    }
}