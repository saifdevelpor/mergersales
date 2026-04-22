<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class SeoPageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            ['name' => 'Home', 'slug' => 'home', 'route_name' => 'webite-home', 'schema_type' => 'Organization'],
            ['name' => 'About Us', 'slug' => 'about-us', 'route_name' => 'webite-about', 'schema_type' => 'WebPage'],
            ['name' => 'Business Directory', 'slug' => 'business', 'route_name' => 'webite-business', 'schema_type' => 'CollectionPage'],
            ['name' => 'Blog', 'slug' => 'blog', 'route_name' => 'webite-blog', 'schema_type' => 'Blog'],
            ['name' => 'Contact', 'slug' => 'contact-us', 'route_name' => 'webite-contact', 'schema_type' => 'ContactPage'],
            ['name' => 'Privacy Policy', 'slug' => 'policy', 'route_name' => 'webite-privacy-policy', 'schema_type' => 'WebPage'],
            ['name' => 'Terms & Conditions', 'slug' => 'terms-conditions', 'route_name' => 'webite-terms-conditions', 'schema_type' => 'WebPage'],
        ];

        foreach ($pages as $page) {
            Page::updateOrCreate(
                ['slug' => $page['slug']],
                array_merge($page, [
                    'meta_title' => 'Mergersales | ' . $page['name'],
                    'meta_description' => 'SEO metadata for the ' . $page['name'] . ' page.',
                    'robots_index' => true,
                    'robots_follow' => true,
                ])
            );
        }
    }
}
