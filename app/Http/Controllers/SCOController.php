<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\DealType;
use App\Models\Country;
use App\Models\Industry;
use App\Models\SubIndustry;
use Illuminate\Http\Request;

class SCOController extends Controller
{
    public function index($slug1 = null, $slug2 = null, $slug3 = null, $slug4 = null)
    {
        // Initialize empty variables for matched data
        $dealtype = null;
        $country = null;
        $industry = null;
        $subIndustry = null;

        // Check if slug1 corresponds to a dealtype, country, industry, or sub-industry
        if ($slug1) {
            // First, check if it's a valid dealtype slug
            $dealtype = Listing::where('slug', $slug1)->first();
            if (!$dealtype) {
                // If it's not a dealtype, check if it's a valid country slug
                $country = Listing::where('slug', $slug1)->first();
            }
            if (!$country) {
                // If it's not a country, check if it's a valid industry slug
                $industry = Industry::where('slug', $slug1)->first();
            }
            if (!$industry) {
                // If it's not an industry, check if it's a valid sub-industry slug
                $subIndustry = SubIndustry::where('slug', $slug1)->first();
            }
        }

        // Optionally, handle additional slugs (slug2, slug3, slug4) for industry and sub-industry
        if ($slug2 && !$industry) {
            $industry = Industry::where('slug', $slug2)->first();
        }

        if ($slug3 && !$subIndustry) {
            $subIndustry = SubIndustry::where('slug', $slug3)->first();
        }

        // Handle optional subIndustry slug
        if ($slug4 && !$subIndustry) {
            $subIndustry = SubIndustry::where('slug', $slug4)->first();
        }

        // Query businesses based on resolved dealtype, country, industry, and sub-industry
        $query = Listing::query();

        if ($dealtype) {
            $query->where('deal_type', $dealtype->name); // Use dealtype name or ID based on your database
        }

        if ($country) {
            $query->where('country', $country->name); // Use country name or ID
        }

        if ($industry) {
            $query->where('industry_id', $industry->id); // Use industry ID
        }

        if ($subIndustry) {
            $query->where('sub_industry_id', $subIndustry->id); // Use sub-industry ID
        }

        // Get the businesses
        $businesses = $query->get();

        // Dynamic SEO title and meta description
        $title = ucfirst($dealtype->name ?? 'Business') . ' Businesses for Sale in ' . ucfirst($country->name ?? 'Country') . ' | ' . ucfirst($industry->name ?? 'Industry') . ' | ' . ucfirst($subIndustry->name ?? 'Sub-Industry');
        $metaDescription = 'Explore businesses for sale in ' . ucfirst($country->name ?? 'Country') . '. Find profitable businesses in the ' . ucfirst($industry->name ?? 'Industry') . ' sector and more.';

        // Pass data to the view
        return view('SCO.index', compact('businesses', 'title', 'metaDescription', 'dealtype', 'country', 'industry', 'subIndustry'));
    }
}
