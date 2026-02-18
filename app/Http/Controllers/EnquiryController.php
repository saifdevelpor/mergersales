<?php

namespace App\Http\Controllers;

use App\Models\Enquiry;
use App\Models\Listing;
use App\Notifications\EnquiryStatusUpdated;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use setasign\Fpdi\Fpdi;


class EnquiryController extends Controller
{

    public function index(Request $request, Listing $listing)
    {
        $user = auth()->user();

        // ✅ Seller can only view own listing enquiries OR Admin
        if (!($user->role === 'Admin' || ($user->role === 'Seller' && (int)$listing->user_id === (int)$user->id))) {
            abort(403);
        }

        // ✅ eager load enquiries
        $listing->load(['enquiries' => function ($q) {
            $q->latest();
        }]);

        return view('enquire.sellershow', compact('listing'));
    }

    public function sellerAll(Request $request)
    {
        $user = auth()->user();

        // ✅ Only Seller or Admin
        if (!in_array($user->role, ['Admin', 'Seller'])) {
            abort(403);
        }

        $q = Enquiry::query()
            ->with([
                'listing' => function ($qq) {
                    $qq->select('id', 'user_id', 'deal_type', 'country', 'business_name');
                }
            ])
            ->latest();

        // ✅ If seller, only own listings enquiries
        if ($user->role === 'Seller') {
            $q->whereHas('listing', function ($qq) use ($user) {
                $qq->where('user_id', $user->id);
            });
        }

        // ✅ optional filters
        if ($request->filled('status')) {
            $q->where('status', $request->status);
        }

        if ($request->filled('nda_status')) {
            $q->where('nda_status', $request->nda_status);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $q->where(function ($qq) use ($s) {
                $qq->where('name', 'like', "%$s%")
                    ->orWhere('email', 'like', "%$s%")
                    ->orWhere('company', 'like', "%$s%")
                    ->orWhere('phone', 'like', "%$s%");
            });
        }

        $enquiries = $q->paginate(20)->withQueryString();

        return view('enquire.seller-all-inquire', compact('enquiries'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'listing_id'    => 'required|exists:listings,id',
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|max:255',
            'phone'         => 'nullable|string|max:50',
            'company'       => 'nullable|string|max:255',
            'position'      => 'nullable|string|max:255',
            'interest_type' => 'required|string|in:buy,partner',
            'budget'        => 'nullable|string|max:255',
            'timeline'      => 'nullable|string|max:255',
            'message'       => 'required|string',
            'attachments.*' => 'nullable|file|max:5120',
            'nda_required'  => 'nullable|boolean',
        ]);

        $listing = Listing::findOrFail($request->listing_id);

        $data = $request->only([
            'name',
            'email',
            'phone',
            'company',
            'position',
            'interest_type',
            'budget',
            'timeline',
            'message',
        ]);

        $data['listing_id']   = $listing->id;
        $data['user_id']      = auth()->id(); // buyer
        $data['status']       = 'pending';
        $data['nda_required'] = $request->boolean('nda_required');
        $data['nda_status']   = 'not_sent';

        // attachments
        if ($request->hasFile('attachments')) {
            $files = [];
            foreach ($request->file('attachments') as $file) {
                $files[] = $file->store('enquiries', 'public');
            }
            $data['attachments'] = json_encode($files);
        }

        $enquiry = Enquiry::create($data);
        // ✅ Notify Seller (listing owner) about new enquiry
        $seller = $listing->user; // listing owner

        if ($seller) {
            $seller->notify(new EnquiryStatusUpdated($enquiry, 'new_enquiry'));
        }

        return back()->with('success', 'Enquiry sent successfully.');
    }

    public function updateStatus(Request $request, Enquiry $enquiry)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected'
        ]);

        $user = auth()->user();

        if (!($user->role === 'Admin' || ($user->role === 'Seller' && $enquiry->listing->user_id === $user->id))) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $enquiry->status = $request->status;
        $enquiry->save();

        // ✅ buyer is enquiry->user_id
        $buyer = $enquiry->buyer;  // now it will work

        if ($buyer) {
            $buyer->notify(new EnquiryStatusUpdated($enquiry));
        }

        return response()->json(['success' => true, 'message' => 'Status updated & buyer notified']);
    }

    public function pending()
    {
        $query = Enquiry::where('status', 'pending');

        if (Auth::user()->role == 'Seller') {

            $sellerListingIds = Listing::where('user_id', Auth::id())
                ->pluck('id');

            $query->whereIn('listing_id', $sellerListingIds);
        }

        $enquiries = $query->latest()->get();
        return view('enquire.pending', compact('enquiries'));
    }


    public function approved()
    {
        $query = Enquiry::where('status', 'approved');

        if (Auth::user()->role == 'Seller') {

            $sellerListingIds = Listing::where('user_id', Auth::id())
                ->pluck('id');

            $query->whereIn('listing_id', $sellerListingIds);
        }

        $enquiries = $query->latest()->get();
        return view('enquire.approved', compact('enquiries'));
    }


    public function rejected()
    {
        $query = Enquiry::where('status', 'rejected');

        if (Auth::user()->role == 'Seller') {

            $sellerListingIds = Listing::where('user_id', Auth::id())
                ->pluck('id');

            $query->whereIn('listing_id', $sellerListingIds);
        }

        $enquiries = $query->latest()->get();
        return view('enquire.reject', compact('enquiries'));
    }


    public function destroy(Enquiry $enquiry)
    {
        $user = auth()->user();

        // Admin OR Seller(owner) OR Buyer(owner)
        $isSellerOwner = ($user->role === 'Seller' && $enquiry->listing->user_id === $user->id);
        $isBuyerOwner  = ($user->role === 'Buyer' && $enquiry->user_id === $user->id);

        if (!($user->role === 'Admin' || $isSellerOwner || $isBuyerOwner)) {
            return back()->with('error', 'Unauthorized');
        }

        // delete enquiry attachments
        if ($enquiry->attachments) {
            $files = is_array($enquiry->attachments) ? $enquiry->attachments : json_decode($enquiry->attachments, true);
            $files = is_array($files) ? $files : [];

            foreach ($files as $file) {
                if (Storage::disk('public')->exists($file)) {
                    Storage::disk('public')->delete($file);
                }
            }
        }

        // delete NDA files (generated + signed)
        if ($enquiry->nda_file_path && Storage::disk('public')->exists($enquiry->nda_file_path)) {
            Storage::disk('public')->delete($enquiry->nda_file_path);
        }
        if ($enquiry->signed_nda_file_path && Storage::disk('public')->exists($enquiry->signed_nda_file_path)) {
            Storage::disk('public')->delete($enquiry->signed_nda_file_path);
        }

        $enquiry->delete();

        return redirect()->back()->with('success', 'Enquiry deleted successfully.');
    }

    public function sendNda(Enquiry $enquiry)
    {
        $user = auth()->user();

        // Admin OR Seller(owner of listing)
        if (!($user->role === 'Admin' || ($user->role === 'Seller' && $enquiry->listing->user_id === $user->id))) {
            return response()->json(['ok' => false, 'message' => 'Unauthorized'], 403);
        }

        if (!$enquiry->nda_required) {
            return response()->json(['ok' => false, 'message' => 'This enquiry does not require NDA.'], 422);
        }

        if ($enquiry->nda_status === 'sent' || $enquiry->nda_status === 'signed') {
            return response()->json(['ok' => true, 'message' => 'NDA already sent.']);
        }

        // ✅ Generate designed PDF
        $relativePath = $this->generateNdaPdf($enquiry);

        // ✅ delete any old NDA file if exists
        if ($enquiry->nda_file_path && Storage::disk('public')->exists($enquiry->nda_file_path)) {
            Storage::disk('public')->delete($enquiry->nda_file_path);
        }

        $enquiry->nda_file_path = $relativePath;
        $enquiry->nda_status    = 'sent';
        $enquiry->nda_sent_at   = now();
        $enquiry->save();

        // ✅ Notify buyer (NDA sent)
        $buyer = $enquiry->buyer;
        if ($buyer) {
            $buyer->notify(new EnquiryStatusUpdated($enquiry, 'nda_sent'));
        }
        return response()->json(['ok' => true, 'message' => 'NDA PDF generated & sent successfully.']);
    }

    public function uploadSignedNda(Request $request, Enquiry $enquiry)
    {
        $user = auth()->user();

        if (!($user->role === 'Buyer' && $enquiry->user_id === $user->id)) {
            abort(403);
        }

        if ($enquiry->nda_status !== 'sent') {
            return back()->with('error', 'NDA is not available for signing yet.');
        }

        $request->validate([
            'signed_nda' => 'required|file|max:5120|mimes:pdf,jpg,jpeg,png',
        ]);

        $file = $request->file('signed_nda');
        $fileName = 'signed_nda_' . $enquiry->id . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('ndas/signed', $fileName, 'public');

        // delete old signed file if re-upload
        if ($enquiry->signed_nda_file_path && Storage::disk('public')->exists($enquiry->signed_nda_file_path)) {
            Storage::disk('public')->delete($enquiry->signed_nda_file_path);
        }

        $enquiry->signed_nda_file_path = $path;
        $enquiry->nda_status           = 'signed';
        $enquiry->nda_signed_at        = now();
        $enquiry->save();

        return back()->with('success', 'Signed NDA uploaded successfully.');
    }

    public function downloadNda(Enquiry $enquiry)
    {
        $user = auth()->user();

        $isSellerOrAdmin = $user->role === 'Admin' || ($user->role === 'Seller' && $enquiry->listing->user_id === $user->id);
        $isBuyerOwner    = $user->role === 'Buyer' && $enquiry->user_id === $user->id;

        if (!($isSellerOrAdmin || $isBuyerOwner)) abort(403);

        if (!$enquiry->nda_file_path || !Storage::disk('public')->exists($enquiry->nda_file_path)) {
            abort(404);
        }

        return Storage::disk('public')->download($enquiry->nda_file_path);
    }

    public function downloadSignedNda(Enquiry $enquiry)
    {
        $user = auth()->user();

        $isSellerOrAdmin = $user->role === 'Admin' || ($user->role === 'Seller' && $enquiry->listing->user_id === $user->id);
        $isBuyerOwner    = $user->role === 'Buyer' && $enquiry->user_id === $user->id;

        if (!($isSellerOrAdmin || $isBuyerOwner)) abort(403);

        if (!$enquiry->signed_nda_file_path || !Storage::disk('public')->exists($enquiry->signed_nda_file_path)) {
            abort(404);
        }

        return Storage::disk('public')->download($enquiry->signed_nda_file_path);
    }

    private function generateNdaPdf(Enquiry $enquiry): string
    {
        $sellerName = $enquiry->listing->user->name ?? 'Seller';
        $buyerName  = $enquiry->name ?? 'Buyer';
        $budget     = $enquiry->budget ? ' with a budget of ' . $enquiry->budget : '';
        $dealInfo   = ($enquiry->listing->deal_type ?? 'Business Deal') . ' - ' . ($enquiry->listing->country ?? '');

        $pdf = Pdf::loadView('pdfs.nda', [
            'enquiry'    => $enquiry,
            'sellerName' => $sellerName,
            'buyerName'  => $buyerName,
            'dealInfo'   => $dealInfo,
            'budget'     => $budget,
            'date'       => now()->format('d M Y'),
        ])->setPaper('a4');

        $filename = 'NDA-ENQ-' . $enquiry->id . '-' . Str::random(8) . '.pdf';
        $relativePath = 'nda/' . $filename;

        Storage::disk('public')->put($relativePath, $pdf->output());

        return $relativePath;
    }

    public function signNda(Request $request, Enquiry $enquiry)
    {
        $user = auth()->user();

        // ✅ Only Buyer (owner)
        if (!($user->role === 'Buyer' && $enquiry->user_id === $user->id)) {
            abort(403);
        }

        if ($enquiry->nda_status !== 'sent') {
            return back()->with('error', 'NDA is not available for signing yet.');
        }

        $request->validate([
            'signature_data' => 'required|string',
        ]);

        // ✅ Make sure original NDA exists
        if (!$enquiry->nda_file_path || !Storage::disk('public')->exists($enquiry->nda_file_path)) {
            return back()->with('error', 'Original NDA file not found.');
        }

        // ✅ Decode signature base64 PNG
        $data = $request->signature_data;

        if (!str_starts_with($data, 'data:image/png;base64,')) {
            return back()->with('error', 'Invalid signature format.');
        }

        $png = base64_decode(str_replace('data:image/png;base64,', '', $data));
        if (!$png) {
            return back()->with('error', 'Could not decode signature.');
        }

        /**
         * ✅ 1) STORE BUYER SIGNATURE IMAGE (DB + storage)
         * Make sure you have a column in enquiries table:
         * buyer_signature_path (nullable)
         */
        $sigName = 'buyer_sig_enq_' . $enquiry->id . '_' . Str::random(8) . '.png';
        $sigPath = 'signatures/buyers/' . $sigName;

        // delete old signature if re-sign
        if (!empty($enquiry->buyer_signature_path) && Storage::disk('public')->exists($enquiry->buyer_signature_path)) {
            Storage::disk('public')->delete($enquiry->buyer_signature_path);
        }

        // save signature to public disk
        Storage::disk('public')->put($sigPath, $png);

        // temp signature file (local filesystem) for FPDI stamping
        $tmpSig = storage_path('app/tmp_sig_' . $enquiry->id . '_' . Str::random(6) . '.png');
        file_put_contents($tmpSig, $png);

        // paths
        $sourcePdf = storage_path('app/public/' . $enquiry->nda_file_path);

        // ✅ 2) Create signed pdf using FPDI
        $pdf = new Fpdi();
        $pageCount = $pdf->setSourceFile($sourcePdf);

        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $tplId = $pdf->importPage($pageNo);
            $size = $pdf->getTemplateSize($tplId);

            $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $pdf->useTemplate($tplId);

            // Place signature only on last page
            if ($pageNo === $pageCount) {
                // Adjust X/Y/Width as per your template
                $sigW = 55; // mm
                $x = $size['width'] - 80;  // right side
                $y = $size['height'] - 45; // near bottom

                $pdf->Image($tmpSig, $x, $y, $sigW, 0, 'PNG');

                // Optional: print name/date under signature
                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetTextColor(60, 60, 60);
                $pdf->SetXY($x, $y + 18);
                $pdf->Cell(0, 6, 'Signed by: ' . ($enquiry->name ?? 'Buyer'), 0, 1);
                $pdf->SetX($x);
                $pdf->Cell(0, 6, 'Date: ' . now()->format('d M Y'), 0, 1);
            }
        }

        // ✅ 3) Save signed pdf to storage/public
        $signedName = 'SIGNED-NDA-ENQ-' . $enquiry->id . '-' . Str::random(8) . '.pdf';
        $signedPath = 'ndas/signed/' . $signedName;

        $output = $pdf->Output('S'); // string
        Storage::disk('public')->put($signedPath, $output);

        // cleanup temp
        @unlink($tmpSig);

        // delete old signed pdf if re-sign
        if (!empty($enquiry->signed_nda_file_path) && Storage::disk('public')->exists($enquiry->signed_nda_file_path)) {
            Storage::disk('public')->delete($enquiry->signed_nda_file_path);
        }

        // ✅ 4) Update enquiry (store signature path in DB too)
        $enquiry->buyer_signature_path  = $sigPath;     // ✅ saved signature image path
        $enquiry->signed_nda_file_path  = $signedPath;  // ✅ saved signed pdf path
        $enquiry->nda_status            = 'signed';
        $enquiry->nda_signed_at         = now();
        $enquiry->save();

        $seller = $enquiry->listing?->user;

        if ($seller) {
            $seller->notify(new EnquiryStatusUpdated($enquiry, 'nda_signed'));
        }

        return back()->with('success', 'NDA signed successfully. Signed PDF generated.');
    }


    public function previewNda(Enquiry $enquiry)
    {
        $user = auth()->user();

        $isSellerOrAdmin = $user->role === 'Admin' || ($user->role === 'Seller' && $enquiry->listing->user_id === $user->id);
        $isBuyerOwner    = $user->role === 'Buyer' && $enquiry->user_id === $user->id;

        if (!($isSellerOrAdmin || $isBuyerOwner)) abort(403);

        if (!$enquiry->nda_file_path || !Storage::disk('public')->exists($enquiry->nda_file_path)) {
            abort(404);
        }

        $path = storage_path('app/public/' . $enquiry->nda_file_path);

        // ✅ Inline show in iframe
        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="NDA-' . $enquiry->id . '.pdf"',
        ]);
    }

    public function sellerByStatus(Request $request)
    {
        $user = auth()->user();

        if (!in_array($user->role, ['Admin', 'Seller'])) abort(403);

        // ✅ status detect from route name
        $routeName = $request->route()->getName();
        $status = match ($routeName) {
            'seller.enquiries.pending'  => 'pending',
            'seller.enquiries.approved' => 'approved',
            'seller.enquiries.rejected' => 'rejected',
            default => 'pending',
        };

        $q = Enquiry::query()
            ->with(['listing:id,user_id,deal_type,country,business_name'])
            ->where('status', $status)
            ->latest();

        // ✅ Seller only own listings enquiries
        if ($user->role === 'Seller') {
            $q->whereHas('listing', function ($qq) use ($user) {
                $qq->where('user_id', $user->id);
            });
        }

        // optional search
        if ($request->filled('search')) {
            $s = $request->search;
            $q->where(function ($qq) use ($s) {
                $qq->where('name', 'like', "%$s%")
                    ->orWhere('email', 'like', "%$s%")
                    ->orWhere('company', 'like', "%$s%")
                    ->orWhere('phone', 'like', "%$s%");
            });
        }

        // optional nda filter
        if ($request->filled('nda_status')) {
            $q->where('nda_status', $request->nda_status);
        }

        $enquiries = $q->paginate(20)->withQueryString();

        // ✅ title for page
        $pageTitle = ucfirst($status) . " Enquiries";

        return view('enquire.seller_status_enquiries', compact('enquiries', 'status', 'pageTitle'));
    }
}
