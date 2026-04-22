<?php

return [
    'defaults' => [
        'title' => env('SEO_DEFAULT_TITLE', config('app.name', 'Mergersales')),
        'description' => env(
            'SEO_DEFAULT_DESCRIPTION',
            'Confidential marketplace for buying, selling, and investing in businesses worldwide.'
        ),
        'og_image' => env('SEO_DEFAULT_OG_IMAGE', '/assets/favicon.jpeg'),
        'twitter_card' => env('SEO_TWITTER_CARD', 'summary_large_image'),
        'organization_name' => env('SEO_ORGANIZATION_NAME', config('app.name', 'Mergersales')),
        'organization_logo' => env('SEO_ORGANIZATION_LOGO', '/images/logo.png'),
        'organization_same_as' => array_values(array_filter([
            env('SEO_FACEBOOK_URL'),
            env('SEO_LINKEDIN_URL'),
            env('SEO_TWITTER_URL'),
            env('SEO_INSTAGRAM_URL'),
        ])),
    ],
];
