<?php

namespace App\Http\Controllers;

use App\Models\ChatNotification;
use App\Models\ChMessage;
use App\Models\Listing;
use App\Models\Industry;
use App\Models\SubIndustry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ListingController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = Listing::with(['industry', 'subIndustry', 'user'])
            ->withCount([
                'enquiries as pending_enquiries_count' => function ($q) {
                    $q->where('status', 'pending'); // (agar enquiries me lowercase hai to ok)
                }
            ]);

        if ($user->role === 'Admin') {
            // ✅ Admin sees ALL (no filter)
        } elseif ($user->role === 'Seller') {
            // ✅ Seller sees only own Approved
            $query->where('user_id', $user->id)
                ->where('status', 'Approved');
        } else {
            // ✅ Buyer/others sees Approved only (all approved)
            $query->where('status', 'Approved');
        }

        $listings = $query->latest()->get();
        $industries = Industry::with('subIndustries')->get();

        // notifications (same as your code)
        $unreadByListing = collect();
        $lastSenderByListing = collect();
        $unreadEnquiryByListing = collect();

        if ($user->role === 'Seller') {
            $unreadByListing = ChatNotification::select('listing_id', DB::raw('COUNT(*) as unread_count'))
                ->where('to_id', $user->id)
                ->where('seen', 0)
                ->groupBy('listing_id')
                ->pluck('unread_count', 'listing_id');

            // Unread enquiry-notification count per listing (for Enquire button)
            $unreadNotifIds = $user->unreadNotifications()
                ->whereIn('data->type', ['new_enquiry', 'nda_signed'])
                ->pluck('id');
            if ($unreadNotifIds->isNotEmpty()) {
                $notifRows = $user->unreadNotifications()
                    ->whereIn('id', $unreadNotifIds)
                    ->get(['id', 'data']);
                $enquiryIds = $notifRows
                    ->pluck('data.enquiry_id')
                    ->filter()
                    ->map(fn($v) => (int) $v)
                    ->unique()
                    ->values();
                if ($enquiryIds->isNotEmpty()) {
                    $enquiryListingMap = \App\Models\Enquiry::whereIn('id', $enquiryIds)
                        ->pluck('listing_id', 'id');
                    $unreadEnquiryByListing = $notifRows
                        ->map(function ($n) use ($enquiryListingMap) {
                            $eid = (int) data_get($n->data, 'enquiry_id');
                            return (int) ($enquiryListingMap[$eid] ?? 0);
                        })
                        ->filter()
                        ->countBy();
                }
            }

            // Determine latest buyer per listing from notifications (listing-aware source).
            $lastSenderByListing = ChatNotification::select('listing_id', DB::raw('MAX(id) as last_id'))
                ->where('to_id', $user->id)
                ->whereNotNull('listing_id')
                ->groupBy('listing_id')
                ->get()
                ->mapWithKeys(function ($row) {
                    $fromId = ChatNotification::where('id', $row->last_id)
                        ->value('from_id');
                    return [$row->listing_id => (int) $fromId];
                });
        } else {
            $unreadByListing = ChatNotification::select('listing_id', DB::raw('COUNT(*) as unread_count'))
                ->where('to_id', $user->id)
                ->where('seen', 0)
                ->groupBy('listing_id')
                ->pluck('unread_count', 'listing_id');
        }

        return view('listing.list', compact('listings', 'industries', 'unreadByListing', 'lastSenderByListing', 'unreadEnquiryByListing'));
    }


    public function markAsRead($from_id, Request $request)
    {
        $listingId = $request->get('listing_id');

        ChatNotification::where('from_id', $from_id)
            ->where('to_id', Auth::id())
            ->when($listingId, fn($q) => $q->where('listing_id', $listingId))
            ->update(['seen' => 1]);

        ChMessage::where('from_id', $from_id)
            ->where('to_id', Auth::id())
            ->when($listingId, fn($q) => $q->where('listing_id', $listingId))
            ->update(['seen' => 1]);

        return redirect('chatify/' . $from_id . '?listing_id=' . $listingId);
    }

    public function store(Request $request)
    {
        try {
            // ✅ VALIDATION
            $validated = $request->validate([
                'deal_type'         => 'required|string',
                'business_name'     => 'required|string',
                'industry_id'       => 'required|exists:industries,id',
                'sub_industry_id'   => 'required|exists:sub_industries,id',
                'country'           => 'required|string',
                'currency'          => 'required|string',
                'revenue_range'     => 'required|string',
                'ebitda_range'      => 'required|string',
                'employee_range'    => 'required|string',
                'description'       => 'required|string',

                // 🔥 FIXED FILE VALIDATION
                'teaser_path' => 'nullable|mimes:pdf,doc,docx|max:5120',
                'im_path'     => 'nullable|mimes:pdf,doc,docx|max:5120',
                'business_img' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

                'reson_for_sale'    => 'nullable|string',
                'nda_required'      => 'nullable|boolean',
            ]);

            DB::beginTransaction();

            // ✅ DATA PREP
            $validated['user_id'] = Auth::id();
            $validated['nda_required'] = $request->has('nda_required') ? 1 : 0;

            // ✅ IMPORTANT: default status Pending
            $validated['status'] = 'Pending';

            // ✅ FILE UPLOADS
            if ($request->hasFile('teaser_path')) {
                $validated['teaser_path'] = $request->file('teaser_path')->store('listings', 'public');
            }

            if ($request->hasFile('im_path')) {
                $validated['im_path'] = $request->file('im_path')->store('listings', 'public');
            }

            if ($request->hasFile('business_img')) {
                $validated['business_img'] = $request->file('business_img')->store('business', 'public');
            }

            // ✅ CREATE LISTING
            Listing::create($validated);

            DB::commit();

            return redirect()
                ->route('listings.approved') // ya listings.index (jo aap use kar rahe ho)
                ->with('success', 'Listing submitted! Admin approval pending.');
        } catch (\Illuminate\Validation\ValidationException $e) {

            return back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {

            DB::rollBack();

            Log::error('Listing Store Failed', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);

            return back()
                ->with('error', 'Listing could not be created.')
                ->withInput();
        }
    }

    public function update(Request $request, Listing $listing)
    {
        $request->validate([
            'deal_type'         => 'required|string',
            'business_name'     => 'required|string',
            'industry_id'       => 'required|exists:industries,id',
            'sub_industry_id'   => 'required|exists:sub_industries,id',
            'country'           => 'required|string',
            'currency'          => 'required|string',
            'revenue_range'     => 'required|string',
            'ebitda_range'      => 'required|string',
            'employee_range'    => 'required|string',
            'description'       => 'required|string',

            // 🔥 FIXED FILE VALIDATION
            'teaser_path' => 'nullable|mimes:pdf,doc,docx|max:5120',
            'im_path'     => 'nullable|mimes:pdf,doc,docx|max:5120',
            'business_img' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'reson_for_sale'    => 'nullable|string',
            'nda_required'      => 'nullable|boolean',
        ]);

        $data = $request->all();
        $data['nda_required'] = $request->has('nda_required') ? 1 : 0;

        // ✅ IMPORTANT: status ko update request se remove kar do
        unset($data['status']);

        if ($request->hasFile('teaser_path')) {
            $data['teaser_path'] = $request->file('teaser_path')->store('listings', 'public');
        }

        if ($request->hasFile('im_path')) {
            $data['im_path'] = $request->file('im_path')->store('listings', 'public');
        }

        if ($request->hasFile('business_img')) {
            if ($listing->business_img && Storage::disk('public')->exists($listing->business_img)) {
                Storage::disk('public')->delete($listing->business_img);
            }
            $data['business_img'] = $request->file('business_img')->store('business', 'public');
        }

        $listing->update($data);

        return redirect()
            ->route('listings.approved')
            ->with('success', 'Listing updated successfully!');
    }

    public function destroy(Listing $listing)
    {
        $listing->delete();
        return redirect()->route('listings.index')->with('success', 'Listing deleted successfully!');
    }

    public function show(Listing $listing)
    {
        return view('listing.detail', compact('listing'));
    }

    // SCO
    public function browseByIndustry($industry_slug, $dealtype = null, $country = null, $industry_id = null, $subIndustry = null)
    {
        // Find industry by slug
        $industry = Industry::where('slug', $industry_slug)->first();

        // If no industry is found, return a 404 error
        if (!$industry) {
            abort(404, 'Industry not found');
        }

        // Initialize the query to fetch businesses
        $query = Listing::query();

        // If a dealtype is provided, filter businesses based on dealtype
        if ($dealtype) {
            $query->where('deal_type', $dealtype); // Filter by deal type
        }

        // If a country is provided, filter businesses based on country
        if ($country) {
            $query->where('country', $country); // Filter by country
        }

        // If industry_id is provided, filter businesses based on industry_id
        if ($industry_id) {
            $query->where('industry_id', $industry_id); // Filter by industry_id
        }

        // If subIndustry is provided, filter businesses based on sub_industry_id
        if ($subIndustry) {
            $query->where('sub_industry_id', $subIndustry); // Filter by sub-industry
        }

        // Apply filter for industry if resolved (industry_slug was provided)
        $query->where('industry_id', $industry->id); // Always filter by industry if found

        // Get the businesses based on the filters
        $businesses = $query->get();

        // Dynamic SEO title and meta description
        $title = ucfirst($dealtype ?? 'Business') . ' Businesses for Sale in ' . ucfirst($country ?? 'Country') . ' | ' . ucfirst($industry->name ?? 'Industry') . ' | ' . ucfirst($subIndustry ?? 'Sub-Industry');
        $metaDescription = 'Explore businesses for sale in ' . ucfirst($country ?? 'Country') . '. Find profitable businesses in the ' . ucfirst($industry->name ?? 'Industry') . ' sector and more.';

        // Return the view with listings and the industry
        return view('SCO.index', compact('businesses', 'title', 'metaDescription', 'dealtype', 'country', 'industry', 'subIndustry'));
    }

    // Admin approval
    public function approve(Listing $listing)
    {
        $listing->update(['status' => 'Approved']);
        return back()->with('success', 'Listing approved successfully.');
    }

    public function reject(Listing $listing)
    {
        $listing->update(['status' => 'Rejected']);
        return back()->with('success', 'Listing rejected successfully.');
    }

    // Pending Approved and Rejected views
    public function pending()
    {

        // ❌ Buyer not allowed
        if (Auth::user()->role === 'Buyer') {
            abort(403);
        }

        $query = Listing::where('status', 'Pending');

        // ✅ Seller sirf apna dekhe
        if (Auth::user()->role !== 'Admin') {
            $query->where('user_id', Auth::id());
        }

        $listings = $query->latest()->get();

        return view('listing.pending', compact('listings'));
    }

    public function rejected()
    {
        // ❌ Buyer not allowed
        if (Auth::user()->role === 'Buyer') {
            abort(403);
        }

        $query = Listing::where('status', 'Rejected');

        // ✅ Seller sirf apna dekhe
        if (Auth::user()->role !== 'Admin') {
            $query->where('user_id', Auth::id());
        }

        $listings = $query->latest()->get();

        return view('listing.rejected', compact('listings'));
    }



    private function roleWiseFilter($query)
    {
        $user = auth()->user();

        // Seller: only own listings
        if ($user->role === 'Seller') {
            $query->where('user_id', $user->id);
        }

        return $query;
    }

    public function approved()
    {
        $user = auth()->user();

        $query = Listing::with(['industry', 'subIndustry', 'user'])
            ->where('status', 'Approved');

        // Admin: all approved
        // Seller: own approved
        // Buyer: all approved
        $query = $this->roleWiseFilter($query);

        $listings = $query->latest()->get();
        $industries = Industry::with('subIndustries')->get();

        return view('listing.approved', compact('listings', 'industries'));
    }
}
