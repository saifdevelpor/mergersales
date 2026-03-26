<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Enquiry;
use App\Models\Industry;
use App\Models\Listing;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WebsiteController extends Controller
{
    public function home()
    {
        $listings = Listing::where('status', 'approved')
            ->latest()
            ->take(6)
            ->get();
        $businessCount = Listing::where('status', 'Approved')->count();
        $buyerCount = User::where('role', 'Buyer')->count();
        $dealCount = Enquiry::where('status', 'approved')->count();

        // 4. Countries Covered (distinct countries in listings)
        $countryCount = Listing::whereNotNull('country')
            ->distinct()
            ->count('country');
        $industries = Industry::orderBy('name')->get();
        $industryListingCounts = Listing::selectRaw('industry_id, COUNT(*) as total')
            ->whereNotNull('industry_id')
            ->where('status', 'Approved')
            ->groupBy('industry_id')
            ->pluck('total', 'industry_id');
        $blogs = Blog::latest()->take(5)->get();
        $user = User::all();
        return view('index', compact('listings', 'blogs', 'user', 'industries', 'industryListingCounts', 'businessCount', 'buyerCount', 'dealCount', 'countryCount'));
    }

    public function website_business(Request $request)
    {
        $industries = Industry::with('subIndustries')->orderBy('name')->get();
        $q = Listing::query()
            ->with(['user', 'industry', 'subIndustry'])
            ->where('status', 'Approved')     // ✅ FIX (case matches DB)
            ->orderBy('created_at', 'desc');

        // ✅ Keyword (business name / description)
        if ($request->filled('keyword')) {
            $kw = trim((string) $request->keyword);
            $q->where(function ($qq) use ($kw) {
                $qq->where('business_name', 'like', '%' . $kw . '%')
                    ->orWhere('description', 'like', '%' . $kw . '%');
            });
        }

        // ✅ Deal Type (normalize Home page values -> DB values)
        if ($request->filled('deal_type')) {
            $raw = trim((string) $request->deal_type);
            // UI has mixed casing ("Sell Business") while DB uses ("Sell business")
            $normalized = match (strtolower($raw)) {
                'sell business' => 'Sell business',
                'raise capital' => 'Raise capital',
                'find partner' => 'Find partner',
                default => $raw, // already matches DB
            };
            $q->where('deal_type', $normalized);
        }

        // ✅ Country
        if ($request->filled('country')) {
            $country = trim((string) $request->country);
            $countryLower = mb_strtolower($country);
            $q->where(function ($qq) use ($countryLower) {
                // exact first, then partial fallback (typo-friendly search behavior)
                $qq->whereRaw('LOWER(country) = ?', [$countryLower])
                    ->orWhereRaw('LOWER(country) LIKE ?', ['%' . $countryLower . '%']);
            });
        }

        // ✅ Industry (support both ?industry_id= and Home page ?industry=)
        $industryId = $request->input('industry_id', $request->input('industry'));
        if (! empty($industryId)) {
            $q->where('industry_id', (int) $industryId);
        }

        // ✅ Sub Industry
        if ($request->filled('sub_industry_id')) {
            $q->where('sub_industry_id', (int) $request->sub_industry_id);
        }

        // ✅ Employee Range
        if ($request->filled('employee_range')) {
            $q->where('employee_range', $request->employee_range);
        }

        // ✅ Revenue / EBITDA sliders
        // TEMPORARY: your DB columns are STRING, so numeric filter is unsafe.
        // Uncomment ONLY if revenue_range/ebitda_range stored as PURE numeric values.
        /*
    if ($request->filled('revenue_range_ui')) {
        [$min, $max] = array_pad(explode(';', $request->revenue_range_ui), 2, null);
        if (is_numeric($min)) $q->whereRaw('CAST(revenue_range AS UNSIGNED) >= ?', [(int)$min]);
        if (is_numeric($max)) $q->whereRaw('CAST(revenue_range AS UNSIGNED) <= ?', [(int)$max]);
    }

    if ($request->filled('ebitda_range_ui')) {
        [$min, $max] = array_pad(explode(';', $request->ebitda_range_ui), 2, null);
        if (is_numeric($min)) $q->whereRaw('CAST(ebitda_range AS UNSIGNED) >= ?', [(int)$min]);
        if (is_numeric($max)) $q->whereRaw('CAST(ebitda_range AS UNSIGNED) <= ?', [(int)$max]);
    }
    */

        // ✅ Sorting
        switch ($request->input('sort_by')) {
            case 'revenue_desc':
                $q->orderBy('revenue_range', 'desc'); // string sorting (ok if numeric strings)
                break;
            case 'revenue_asc':
                $q->orderBy('revenue_range', 'asc');
                break;
            case 'ebitda_desc':
                $q->orderBy('ebitda_range', 'desc');
                break;
            case 'ebitda_asc':
                $q->orderBy('ebitda_range', 'asc');
                break;
            case 'latest':
                $q->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $q->orderBy('created_at', 'asc');
                break;
            default:
                $q->orderBy('created_at', 'desc');
                break;
        }

        $listings = $q->paginate(6)->appends($request->query());

        return view('website-business', compact('listings', 'industries', 'request'));
    }



    public function website_business_single($id)
    {
        $enquiries = Enquiry::where('listing_id', $id)
            ->latest()
            ->take(3)
            ->get();
        $business = Listing::with(['industry', 'subIndustry'])
            ->findOrFail($id);
        $inquiryCount = Enquiry::where('listing_id', $business->id)->count();
        $listing = Listing::findOrFail($id);

        // similar businesses (same industry, except current)
        $similar = Listing::where('industry_id', $business->industry_id)
            ->where('id', '!=', $business->id)
            ->latest()
            ->take(6)
            ->get();
        return view('website-business-single', compact('business', 'similar', 'inquiryCount', 'enquiries', 'listing'));
    }

    public function website_about()
    {
        $businessCount = Listing::where('status', 'Approved')->count();
        $buyerCount = User::where('role', 'Buyer')->count();
        $dealCount = Enquiry::where('status', 'approved')->count();

        // 4. Countries Covered (distinct countries in listings)
        $countryCount = Listing::whereNotNull('country')
            ->distinct()
            ->count('country');
        return view('website-about', compact('businessCount', 'buyerCount', 'dealCount', 'countryCount'));
    }

    public function website_contact()
    {
        return view('website-contact');
    }

    public function website_blog()
    {
        $blogs = Blog::latest()->paginate(2);
        // Keep selects compatible with current DB columns.
        $popularResources = Blog::select('id', 'details', 'image', 'created_at')
            ->latest()
            ->take(4)
            ->get();
        $latestListings = Listing::latest()->take(3)->get();
        $archives = Blog::selectRaw('
        YEAR(created_at)  as year,
        MONTH(created_at) as month,
        DAY(created_at)   as day,
        COUNT(*) as total
    ')
            ->groupBy('year', 'month', 'day')
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->orderByDesc('day')
            ->take(5)
            ->get();


        return view('website-blog', compact('blogs', 'popularResources', 'latestListings', 'archives'));
    }

    public function website_blog_single($id)
    {
        // Blog find by ID
        $blog = Blog::findOrFail($id);

        // Previous Blog
        $prevBlog = Blog::where('id', '<', $blog->id)
            ->orderByDesc('id')
            ->first();

        // Next Blog
        $nextBlog = Blog::where('id', '>', $blog->id)
            ->orderBy('id')
            ->first();

        // Related Blogs (simple: latest except current)
        $relatedBlogs = Blog::where('id', '!=', $blog->id)
            ->latest()
            ->take(3)
            ->get();

        // Popular Posts (most viewed)
        $popularPosts = Blog::select('id', 'details', 'image', 'created_at')
            ->latest()
            ->take(4)
            ->get();

        // Archive (month-wise)
        $archives = Blog::selectRaw('
        YEAR(created_at)  as year,
        MONTH(created_at) as month,
        DAY(created_at)   as day,
        COUNT(*) as total
    ')
            ->groupBy('year', 'month', 'day')
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->orderByDesc('day')
            ->take(5)
            ->get();
        return view('website-blog-single', compact(
            'blog',
            'prevBlog',
            'nextBlog',
            'relatedBlogs',
            'popularPosts',
            'archives'
        ));
    }

    // Pravicy Polocy
    public function website_privacy_policy()
    {
        return view('Acceptable-use-policy');
    }

    public function website_terms_conditions()
    {
        return view('Terms-of-use');
    }

    public function countriesAutocomplete(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        if (mb_strlen($q) < 2) {
            return response()->json(['items' => []]);
        }

        $apiKey = env('RAPIDAPI_KEY');
        $apiHost = env('RAPIDAPI_HOST', 'google-map-places.p.rapidapi.com');
        if (! $apiKey) {
            return response()->json(['items' => []]);
        }

        try {
            $response = Http::timeout(10)
                ->withHeaders([
                    'X-RapidAPI-Key' => $apiKey,
                    'X-RapidAPI-Host' => $apiHost,
                ])
                ->get('https://google-map-places.p.rapidapi.com/maps/api/place/autocomplete/json', [
                    'input' => $q,
                    'types' => '(regions)',
                    'language' => 'en',
                ]);

            $predictions = (array) data_get($response->json(), 'predictions', []);
            $countries = [];

            foreach ($predictions as $prediction) {
                $terms = (array) data_get($prediction, 'terms', []);
                $lastTerm = end($terms);
                $country = trim((string) data_get($lastTerm, 'value', ''));
                if ($country !== '') {
                    $countries[] = $country;
                }
            }

            $countries = array_values(array_unique($countries));

            // Rank by prefix match first, then contains.
            usort($countries, function ($a, $b) use ($q) {
                $qLower = mb_strtolower($q);
                $aLower = mb_strtolower($a);
                $bLower = mb_strtolower($b);
                $aScore = str_starts_with($aLower, $qLower) ? 0 : (str_contains($aLower, $qLower) ? 1 : 2);
                $bScore = str_starts_with($bLower, $qLower) ? 0 : (str_contains($bLower, $qLower) ? 1 : 2);
                if ($aScore !== $bScore) return $aScore <=> $bScore;
                return strcmp($a, $b);
            });

            return response()->json([
                'items' => array_map(fn($name) => ['name' => $name], $countries),
            ]);
        } catch (\Throwable $e) {
            return response()->json(['items' => []], 200);
        }
    }
}
