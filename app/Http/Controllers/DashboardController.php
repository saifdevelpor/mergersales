<?php

namespace App\Http\Controllers;

use App\Models\Enquiry;
use App\Models\Listing;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Role can be: admin, seller, buyer (apne system ke mutabiq adjust)
        $role = $user->role;

        $data = [
            'role' => $role,
        ];

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
