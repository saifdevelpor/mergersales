<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\Listing;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SeoExampleSeeder extends Seeder
{
    public function run(): void
    {
        Listing::query()->limit(5)->get()->each(function (Listing $listing): void {
            $listing->forceFill([
                'seo_title' => $listing->seo_title ?: $listing->business_name . ' for Sale | Mergersales',
                'seo_description' => $listing->seo_description ?: Str::limit(strip_tags((string) $listing->description), 155, ''),
                'focus_keyword' => $listing->focus_keyword ?: Str::limit($listing->business_name, 120, ''),
                'og_image' => $listing->og_image ?: $listing->business_img,
            ])->saveQuietly();
        });

        Blog::query()->limit(5)->get()->each(function (Blog $blog): void {
            $title = $blog->title ?: Str::limit(strip_tags((string) $blog->details), 80, '');

            $blog->forceFill([
                'title' => $title,
                'seo_title' => $blog->seo_title ?: $title . ' | Mergersales Blog',
                'seo_description' => $blog->seo_description ?: Str::limit(strip_tags((string) $blog->details), 155, ''),
                'featured_image_alt' => $blog->featured_image_alt ?: $title,
                'og_image' => $blog->og_image ?: $blog->image,
            ])->saveQuietly();
        });
    }
}
