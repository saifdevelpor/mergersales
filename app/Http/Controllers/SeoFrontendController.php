<?php

namespace App\Http\Controllers;

use App\Helpers\SeoHelper;
use App\Models\Blog;
use App\Models\Enquiry;
use App\Models\Industry;
use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SeoFrontendController extends Controller
{
    public function showBusiness(Listing $listing)
    {
        abort_unless($listing->status === 'Approved', 404);

        $business = Listing::with(['industry', 'subIndustry', 'user'])->findOrFail($listing->id);
        $enquiries = Enquiry::where('listing_id', $business->id)->latest()->take(3)->get();
        $inquiryCount = Enquiry::where('listing_id', $business->id)->count();
        $similar = Listing::where('industry_id', $business->industry_id)
            ->where('id', '!=', $business->id)
            ->where('status', 'Approved')
            ->latest()
            ->take(6)
            ->get();
        $listing = $business;
        $faqItems = $this->buildListingFaq($business);
        $seo = SeoHelper::forListing($business);
        $schemas = array_values(array_filter([
            $this->buildListingSchema($business),
            $this->buildFaqSchema($faqItems),
        ]));

        return view('website-business-single', compact(
            'business',
            'similar',
            'inquiryCount',
            'enquiries',
            'listing',
            'faqItems',
            'seo',
            'schemas'
        ));
    }

    public function showBlog(Blog $blog)
    {
        $prevBlog = Blog::where('id', '<', $blog->id)->orderByDesc('id')->first();
        $nextBlog = Blog::where('id', '>', $blog->id)->orderBy('id')->first();
        $relatedBlogs = Blog::where('id', '!=', $blog->id)->latest()->take(3)->get();
        $popularPosts = Blog::select('id', 'title', 'details', 'image', 'created_at', 'slug', 'featured_image_alt')->latest()->take(4)->get();
        $archives = Blog::selectRaw('
            YEAR(created_at) as year,
            MONTH(created_at) as month,
            DAY(created_at) as day,
            COUNT(*) as total
        ')
            ->groupBy('year', 'month', 'day')
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->orderByDesc('day')
            ->take(5)
            ->get();
        $seo = SeoHelper::forBlog($blog);
        $schemas = [$this->buildBlogSchema($blog)];

        return view('website-blog-single', compact(
            'blog',
            'prevBlog',
            'nextBlog',
            'relatedBlogs',
            'popularPosts',
            'archives',
            'seo',
            'schemas'
        ));
    }

    public function showIndustry(Industry $industry, Request $request)
    {
        $industries = Industry::with('subIndustries')->orderBy('name')->get();
        $listings = Listing::with(['user', 'industry', 'subIndustry'])
            ->where('status', 'Approved')
            ->where('industry_id', $industry->id)
            ->latest()
            ->paginate(6)
            ->appends($request->query());
        $seo = SeoHelper::forIndustry($industry);

        return view('website-business', compact('industries', 'listings', 'request', 'seo'));
    }

    public function showCountry(string $slug, Request $request)
    {
        $countryName = Str::of($slug)->replace('-', ' ')->title()->toString();
        $industries = Industry::with('subIndustries')->orderBy('name')->get();
        $listings = Listing::with(['user', 'industry', 'subIndustry'])
            ->where('status', 'Approved')
            ->whereRaw('LOWER(country) = ?', [Str::lower($countryName)])
            ->latest()
            ->paginate(6)
            ->appends($request->query());
        $seo = SeoHelper::build([
            'title' => $countryName . ' Businesses for Sale | Mergersales',
            'description' => 'Browse businesses for sale and investment opportunities in ' . $countryName . '.',
            'canonical' => route('seo.country.show', $slug),
            'schema_type' => 'CollectionPage',
        ]);

        return view('website-business', compact('industries', 'listings', 'request', 'seo'));
    }

    private function buildListingFaq(Listing $listing): array
    {
        return [
            [
                'question' => 'What type of opportunity is this listing?',
                'answer' => 'This business is listed as ' . Str::lower((string) $listing->deal_type) . '.',
            ],
            [
                'question' => 'Where is the business located?',
                'answer' => 'The business is currently marketed in ' . ($listing->country ?: 'a confidential location') . '.',
            ],
            [
                'question' => 'What financial information is available?',
                'answer' => 'The listing currently highlights revenue of ' . ($listing->revenue_range ?: 'confidential') . ' and EBITDA of ' . ($listing->ebitda_range ?: 'confidential') . '.',
            ],
        ];
    }

    private function buildListingSchema(Listing $listing): array
    {
        if ($listing->schema_json) {
            $decoded = json_decode($listing->schema_json, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded;
            }
        }

        return array_filter([
            '@context' => 'https://schema.org',
            '@type' => 'Offer',
            'name' => $listing->seo_title ?: $listing->business_name,
            'description' => $listing->seo_description ?: Str::limit(strip_tags((string) $listing->description), 200, ''),
            'url' => route('seo.business.show', $listing->slug),
            'image' => $listing->og_image ? SeoHelper::imageUrl($listing->og_image) : ($listing->business_img ? asset('storage/' . ltrim($listing->business_img, '/')) : null),
            'category' => $listing->industry?->name,
            'areaServed' => $listing->country,
        ]);
    }

    private function buildBlogSchema(Blog $blog): array
    {
        return array_filter([
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $blog->seo_title ?: $blog->title,
            'description' => $blog->seo_description ?: Str::limit(strip_tags((string) $blog->details), 200, ''),
            'image' => $blog->og_image ? SeoHelper::imageUrl($blog->og_image) : ($blog->image ? asset($blog->image) : null),
            'datePublished' => optional($blog->created_at)?->toIso8601String(),
            'dateModified' => optional($blog->updated_at)?->toIso8601String(),
            'author' => [
                '@type' => 'Person',
                'name' => $blog->user?->name ?: 'Mergersales',
            ],
            'publisher' => SeoHelper::organizationSchema(),
            'mainEntityOfPage' => route('seo.blog.show', $blog->slug),
        ]);
    }

    private function buildFaqSchema(array $faqItems): ?array
    {
        if ($faqItems === []) {
            return null;
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => collect($faqItems)->map(fn (array $faq) => [
                '@type' => 'Question',
                'name' => $faq['question'],
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $faq['answer'],
                ],
            ])->values()->all(),
        ];
    }
}
