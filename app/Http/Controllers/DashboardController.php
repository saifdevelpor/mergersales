<?php

namespace App\Http\Controllers;

use App\Models\Enquiry;
use App\Models\Blog;
use App\Models\Listing;
use App\Models\Page;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        // Role can be: admin, seller, buyer (apne system ke mutabiq adjust)
        $role = $user->role;

        $data = [
            'role' => $role,
        ];

        // =========================
        // 6 MONTH LISTINGS GRAPH
        // =========================
        $start = Carbon::now()->startOfMonth()->subMonths(5);
        $end = Carbon::now()->endOfMonth();

        $listingsQ = Listing::query();
        // Seller ko sirf uski apni listings ka trend dikhana hai
        if ($role === 'Seller') {
            $listingsQ->where('user_id', $user->id);
        }

        $rows = $listingsQ
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as ym, COUNT(*) as total")
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('ym')
            ->orderBy('ym')
            ->get()
            ->keyBy('ym');

        $labels = [];
        $counts = [];
        for ($i = 0; $i < 6; $i++) {
            $m = $start->copy()->addMonths($i);
            $ym = $m->format('Y-m');
            $labels[] = $m->format('M Y');
            $counts[] = (int) (($rows[$ym]->total ?? 0));
        }

        $data['six_month_labels'] = $labels;
        $data['six_month_counts'] = $counts;

        // =========================
        // SEO MANAGER DASHBOARD DATA
        // =========================
        if ($role === 'seo_manager') {
            $data['seo_total_pages'] = Page::count();
            $data['seo_total_listings'] = Listing::count();
            $data['seo_total_blogs'] = Blog::count();
            $data['seo_listing_slugs'] = Listing::whereNotNull('slug')->count();
            $data['seo_blog_slugs'] = Blog::whereNotNull('slug')->count();
            $data['seo_custom_schema_count'] = Listing::whereNotNull('schema_json')->count();
            $data['seo_recent_pages'] = Page::latest()->take(5)->get()->map(function (Page $page) {
                $page->name = Str::limit($page->name, 80, '');
                $page->slug = Str::limit($page->slug, 80, '');
                $page->meta_title = $page->meta_title
                    ? Str::limit($page->meta_title, 120, '')
                    : '—';
                $page->public_url = $page->route_name
                    ? route($page->route_name)
                    : route('seo.pages.show', $page->slug);

                return $page;
            });
            $data['seo_recent_listings'] = Listing::latest()->take(8)->get();
            $data['seo_recent_blogs'] = Blog::latest()->take(8)->get()->map(function (Blog $blog) {
                $blog->title = Str::limit($blog->readable_title, 120, '');
                $blog->slug = Str::limit($blog->readable_slug, 80, '');
                $blog->seo_title = $blog->seo_title
                    ? Str::limit($blog->seo_title, 120, '')
                    : '—';

                return $blog;
            });
        }

        // =========================
        // ADMIN DASHBOARD DATA
        // =========================
        if ($role === 'Admin') {
            $data['total_users']   = User::count();
            $data['total_sellers'] = User::where('role', 'Seller')->count();
            $data['total_buyers']  = User::where('role', 'Buyer')->count();

            $data['total_businesses'] = Listing::count();
            $data['pending_businesses']  = Listing::where('status', 'Pending')->count();
            $data['approved_businesses'] = Listing::where('status', 'Approved')->count();
            $data['rejected_businesses'] = Listing::where('status', 'Rejected')->count();

            $data['recent_businesses'] = Listing::latest()->take(10)->get();

            // Extra: recent users + enquiries (for bigger dashboard)
            $data['recent_users'] = User::latest()->take(5)->get();
            $data['recent_enquiries'] = Enquiry::with(['listing', 'user'])
                ->latest()
                ->take(10)
                ->get();

            // Charts: users joined + enquiries (last 6 months)
            $uRows = User::query()
                ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as ym, COUNT(*) as total")
                ->whereBetween('created_at', [$start, $end])
                ->groupBy('ym')
                ->orderBy('ym')
                ->get()
                ->keyBy('ym');

            $eRows = Enquiry::query()
                ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as ym, COUNT(*) as total")
                ->whereBetween('created_at', [$start, $end])
                ->groupBy('ym')
                ->orderBy('ym')
                ->get()
                ->keyBy('ym');

            $uCounts = [];
            $eCounts = [];
            for ($i = 0; $i < 6; $i++) {
                $m = $start->copy()->addMonths($i);
                $ym = $m->format('Y-m');
                $uCounts[] = (int) (($uRows[$ym]->total ?? 0));
                $eCounts[] = (int) (($eRows[$ym]->total ?? 0));
            }
            $data['six_month_users_counts'] = $uCounts;
            $data['six_month_enquiries_counts'] = $eCounts;
        }

        // =========================
        // SELLER DASHBOARD DATA
        // =========================
        if ($role === 'Seller') {

            // Listings (tum already kar rahe ho)
            $data['my_total_businesses'] = Listing::where('user_id', $user->id)->count();
            $data['my_pending']  = Listing::where('user_id', $user->id)->where('status', 'pending')->count();
            $data['my_approved'] = Listing::where('user_id', $user->id)->where('status', 'approved')->count();
            $data['my_rejected'] = Listing::where('user_id', $user->id)->where('status', 'rejected')->count();

            // ✅ Enquiries for seller (seller = listing owner)
            $sellerEnquiries = Enquiry::whereHas('listing', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });

            $data['total_enquiries'] = (clone $sellerEnquiries)->count();
            $data['pending_enquiries'] = (clone $sellerEnquiries)->where('status', 'pending')->count();
            $data['approved_enquiries'] = (clone $sellerEnquiries)->where('status', 'approved')->count();
            $data['rejected_enquiries'] = (clone $sellerEnquiries)->where('status', 'rejected')->count();

            // Optional: recent enquiries list
            // $data['recent_enquiries'] = (clone $sellerEnquiries)
            //     ->with(['listing', 'buyer'])
            //     ->latest()
            //     ->take(8)
            //     ->get();

            $data['my_recent_businesses'] = Listing::where('user_id', $user->id)->latest()->take(8)->get();

            // Extra: recent enquiries list (seller)
            $data['seller_recent_enquiries'] = (clone $sellerEnquiries)
                ->with(['listing', 'user'])
                ->latest()
                ->take(10)
                ->get();

            // Chart: enquiries trend for seller (last 6 months)
            $seRows = (clone $sellerEnquiries)
                ->selectRaw("DATE_FORMAT(enquiries.created_at, '%Y-%m') as ym, COUNT(*) as total")
                ->whereBetween('enquiries.created_at', [$start, $end])
                ->groupBy('ym')
                ->orderBy('ym')
                ->get()
                ->keyBy('ym');

            $seCounts = [];
            for ($i = 0; $i < 6; $i++) {
                $m = $start->copy()->addMonths($i);
                $ym = $m->format('Y-m');
                $seCounts[] = (int) (($seRows[$ym]->total ?? 0));
            }
            $data['six_month_seller_enquiries_counts'] = $seCounts;
        }

        // =========================
        // BUYER DASHBOARD DATA
        // =========================
        if ($role === 'Buyer') {

            // Saved / existing data
            $data['saved_count'] = $saved_count ?? 0;

            // ✅ Buyer Enquiries (sent by this user)
            $buyerEnquiries = Enquiry::where('user_id', $user->id);

            $data['buyer_total_enquiries'] = (clone $buyerEnquiries)->count();
            $data['buyer_pending_enquiries'] = (clone $buyerEnquiries)->where('status', 'pending')->count();
            $data['buyer_approved_enquiries'] = (clone $buyerEnquiries)->where('status', 'approved')->count();
            $data['buyer_rejected_enquiries'] = (clone $buyerEnquiries)->where('status', 'rejected')->count();

            // already using this
            $data['inquiries_sent'] = $data['buyer_total_enquiries'];
            $userId = auth()->id();

            $data['buyer_enquired_businesses'] = Enquiry::with('listing')
                ->where('user_id', auth()->id())
                ->latest()
                ->take(5)
                ->get();
        }

        return view('dashboard.list', $data);
    }
}
