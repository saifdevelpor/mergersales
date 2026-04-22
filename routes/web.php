<?php

use App\Http\Controllers\Admin\SeoController as AdminSeoController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\Buyer\NotificationController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\ManagedBlogController;
use App\Http\Controllers\SeoFrontendController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WebsiteController;
use App\Http\Controllers\EnquiryController;
use App\Http\Controllers\IndustryController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\SCOController;
use App\Http\Controllers\UserController;
use Chatify\Http\Controllers\MessagesController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TicketsController;
use App\Http\Controllers\TicketsController as ControllersTicketsController;
use App\Services\RapidFxService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

/*
|--------------------------------------------------------------------------
| PUBLIC (Frontend) Routes - No login required
|--------------------------------------------------------------------------
*/

Route::get('/', [WebsiteController::class, 'home'])->name('webite-home');
Route::get('/Business', [WebsiteController::class, 'website_business'])->name('webite-business');
Route::get('/business/{listing:slug}', [SeoFrontendController::class, 'showBusiness'])->name('seo.business.show');
Route::get('/blog/{blog:slug}', [SeoFrontendController::class, 'showBlog'])->name('seo.blog.show');
Route::get('/industry/{industry:slug}', [SeoFrontendController::class, 'showIndustry'])->name('seo.industry.show');
Route::get('/country/{slug}', [SeoFrontendController::class, 'showCountry'])->name('seo.country.show');
Route::get('/pages/{page:slug}', [AdminSeoController::class, 'publicPage'])->name('seo.pages.show');
Route::get('/sitemap.xml', [AdminSeoController::class, 'publicSitemap'])->name('seo.sitemap');
Route::get('/robots.txt', function () {
    return response(
        "User-agent: *\nAllow: /\nSitemap: " . url('/sitemap.xml') . "\n",
        200,
        ['Content-Type' => 'text/plain; charset=UTF-8']
    );
});
Route::get('Business-single/{id}', [WebsiteController::class, 'website_business_single'])->name('business.single');
Route::get('/About-Us', [WebsiteController::class, 'website_about'])->name('webite-about');
Route::get('/Contact-Us', [WebsiteController::class, 'website_contact'])->name('webite-contact');
Route::get('/Blog', [WebsiteController::class, 'website_blog'])->name('webite-blog');
Route::get('/Blog-single/{id}', [WebsiteController::class, 'website_blog_single'])->name('webite-blog-single');
Route::get('/Policy', [WebsiteController::class, 'website_privacy_policy'])->name('webite-privacy-policy');
Route::get('/Terms-Conditions', [WebsiteController::class, 'website_terms_conditions'])->name('webite-terms-conditions');
Route::get('/countries/autocomplete', [WebsiteController::class, 'countriesAutocomplete'])->name('countries.autocomplete');
// Public: Industry / Browse
Route::get('/sub-industries/{industry}', [IndustryController::class, 'getSubIndustries'])->name('sub-industries.by-industry');
Route::get('/businesses-for-sale/{industrySlug}/{subIndustrySlug?}', [ListingController::class, 'browseByIndustry'])
    ->name('listings.browseByIndustry');

// Enquiry send normally public hota hai (visitors enquiry bhej sakte)
Route::post('/enquiry/send', [EnquiryController::class, 'store'])->name('enquiry.store');

Auth::routes();


// Route::get('/test-fx', function () {

//     $response = Http::withoutVerifying()
//         ->withHeaders([
//             'X-RapidAPI-Key'  => env('RAPIDAPI_KEY'),
//             'X-RapidAPI-Host' => env('RAPIDAPI_HOST'),
//         ])
//         ->get(rtrim(env('RAPIDAPI_BASE_URL'), '/') . '/timeseries', [
//             'start_date' => '2019-01-01',
//             'end_date'   => '2019-01-02',
//             'base'       => 'USD',
//             'symbols'    => 'EUR,GBP',  // ✅ encoded (%2C) nahi likhna
//         ]);

//     return $response->json();
// });

// Route::get('/test-convert', function () {

//     $response = Http::withoutVerifying()
//         ->withHeaders([
//             'X-RapidAPI-Key'  => env('RAPIDAPI_KEY'),
//             'X-RapidAPI-Host' => env('RAPIDAPI_HOST'),
//         ])
//         ->get(rtrim(env('RAPIDAPI_BASE_URL'), '/') . '/convert', [
//             'from'   => 'USD',
//             'to'     => 'PKR',
//             'amount' => 100,
//         ]);

//     return $response->json();
// });
Route::post('/currency/set', [CurrencyController::class, 'set'])->name('currency.set');
/*
|--------------------------------------------------------------------------
| PROTECTED (Backend/Dashboard) Routes - Login required
|--------------------------------------------------------------------------
*/

/* ===============================
 | PUBLIC SEO ROUTES
 =============================== */
Route::get(
    '/businesses-for-sale/{industry_slug}/{dealtype}/{country}/{industry_id}/{subIndustry}',
    [ListingController::class, 'browseByIndustry']
);
// SCO route (public facing, but dynamic based on parameters)
Route::get('/businesses-for-sale/{slug1?}/{slug2?}/{slug3?}/{slug4?}', [SCOController::class, 'index']);

Route::get('/admin/seo/{path?}', function (?string $path = null) {
    $target = '/seo-manager';

    if ($path) {
        $target .= '/' . ltrim($path, '/');
    }

    return redirect($target, 301);
})->where('path', '.*');

Route::middleware('auth')->group(function () {

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

// User management
Route::get('/user', [UserController::class, 'index'])->name('user-management');
Route::post('/user-save', [UserController::class, 'save'])->name('user-save');
Route::post('/user-update/{id}', [UserController::class, 'update'])->name('user-update');
Route::delete('/user-delete/{id}', [UserController::class, 'delete'])->name('user-delete');

// Profile
Route::get('/profile', [UserController::class, 'profile'])->name('profile');
Route::put('/profile/update', [UserController::class, 'profileUpdate'])->name('profile.update');
Route::get('/profile/{id}', [UserController::class, 'showProfile'])->name('profile.show');

// Listing (admin/seller side)
// All listings (Admin: all | Seller: approved own | Buyer: approved)
Route::get('/listings', [ListingController::class, 'index'])
    ->name('listings.index');

// Create listing
Route::post('/listings/store', [ListingController::class, 'store'])
    ->name('listings.store');


/* ===============================
     | STATUS BASED PAGES
     =============================== */

// Approved listings
Route::get('/listings/approved', [ListingController::class, 'approved'])
    ->name('listings.approved');

// Pending listings (Admin + Seller only, Buyer blocked in controller)
Route::get('/listings/pending', [ListingController::class, 'pending'])
    ->name('listings.pending');

// Rejected listings (Admin + Seller only, Buyer blocked in controller)
Route::get('/listings/rejected', [ListingController::class, 'rejected'])
    ->name('listings.rejected');


/* ===============================
     | ADMIN ACTIONS
     =============================== */

Route::patch('/listings/{listing}/approve', [ListingController::class, 'approve'])
    ->name('listings.approve');

Route::patch('/listings/{listing}/reject', [ListingController::class, 'reject'])
    ->name('listings.reject');


/* ===============================
     | UPDATE / DELETE
     =============================== */

Route::post('/listings/{listing}/update', [ListingController::class, 'update'])
    ->name('listings.update');

Route::delete('/listings/{listing}/delete', [ListingController::class, 'destroy'])
    ->name('listings.destroy');


/* ===============================
     | SHOW SINGLE LISTING
     | (KEEP THIS AT THE END)
     =============================== */

Route::get('/listings/{listing}', [ListingController::class, 'show'])
    ->name('listings.show');

Route::get('/notification/read/{from_id}', [ListingController::class, 'markAsRead'])->name('notification.read');

// Enquiry status / NDA actions (should be protected)
Route::post('/enquiries/{enquiry}/status', [EnquiryController::class, 'updateStatus'])->name('enquiries.status');

Route::post('/enquiries/{enquiry}/send-nda', [EnquiryController::class, 'sendNda'])->name('enquiries.sendNda');
Route::post('/enquiries/{enquiry}/upload-signed-nda', [EnquiryController::class, 'uploadSignedNda'])->name('enquiries.uploadSignedNda');

Route::get('/enquiries/{enquiry}/download-nda', [EnquiryController::class, 'downloadNda'])->name('enquiries.downloadNda');
Route::get('/enquiries/{enquiry}/download-signed-nda', [EnquiryController::class, 'downloadSignedNda'])->name('enquiries.downloadSignedNda');

Route::get('/seller/listings/{listing}/enquiries', [EnquiryController::class, 'index'])->name('seller.listing.enquiries');

Route::get('/buyer/enquiries/pending',  [EnquiryController::class, 'pending'])->name('buyer.enquiries.pending');
Route::get('/buyer/enquiries/rejected', [EnquiryController::class, 'rejected'])->name('buyer.enquiries.rejected');
Route::get('/buyer/enquiries/approved', [EnquiryController::class, 'approved'])->name('buyer.enquiries.approved');

// Show all inquire to seller
Route::get('/seller/enquiries', [EnquiryController::class, 'sellerAll'])->name('seller.enquiries.all');
Route::get('/seller/enquiries/pending',  [EnquiryController::class, 'sellerByStatus'])->name('seller.enquiries.pending');
Route::get('/seller/enquiries/approved', [EnquiryController::class, 'sellerByStatus'])->name('seller.enquiries.approved');
Route::get('/seller/enquiries/rejected', [EnquiryController::class, 'sellerByStatus'])->name('seller.enquiries.rejected');

// Notification route for buyers to mark as read and redirect
Route::get('/buyer/notifications/{id}', [NotificationController::class, 'open'])
    ->name('buyer.notifications.open');

Route::delete('/enquiries/{enquiry}', [EnquiryController::class, 'destroy'])->name('enquiries.destroy');

// Dashboard actions
Route::post('/seller/listing/{listing}/toggle', [DashboardController::class, 'toggleStatus'])->name('seller.listing.toggle');
Route::delete('/seller/listing/{listing}', [DashboardController::class, 'destroy'])->name('seller.listing.delete');

Route::post('/enquiries/{enquiry}/sign-nda', [EnquiryController::class, 'signNda'])->name('enquiries.signNda');
Route::get('/enquiries/{enquiry}/nda/preview', [EnquiryController::class, 'previewNda'])->name('enquiries.previewNda');

Route::post('/seller/enquiry/{enquiry}/send-nda', [DashboardController::class, 'sendNda'])->name('seller.enquiry.sendNda');

// Chat notifications (usually protected)
Route::get('/chat/notifications', [MessagesController::class, 'notifications']);
Route::post('/chat/notification/seen', [MessagesController::class, 'notificationSeen'])->name('notification.seen');

// Blogs
Route::get('/blogs', [ManagedBlogController::class, 'index'])->name('blogs.index');
Route::post('/blogs/store', [ManagedBlogController::class, 'save'])->name('blogs.store');
Route::post('/blogs/{blog}/update', [ManagedBlogController::class, 'update'])->name('blogs.update');
Route::delete('/blogs/{blog}/delete', [ManagedBlogController::class, 'destroy'])->name('blogs.destroy');

Route::prefix('/seo-manager')
    ->middleware('seo.manager')
    ->name('admin.seo.')
    ->group(function () {
        Route::get('/', [AdminSeoController::class, 'index'])->name('index');
        Route::get('/pages', [AdminSeoController::class, 'pages'])->name('pages');
        Route::post('/pages/create', [AdminSeoController::class, 'storePage'])->name('pages.store');
        Route::post('/pages/bulk-store', [AdminSeoController::class, 'bulkStorePages'])->name('pages.bulk-store');
        Route::delete('/pages/bulk-delete', [AdminSeoController::class, 'bulkDestroyPages'])->name('pages.bulk-destroy');
        Route::post('/pages/{page}', [AdminSeoController::class, 'updatePage'])->name('pages.update');
        Route::delete('/pages/{page}', [AdminSeoController::class, 'destroyPage'])->name('pages.destroy');
        Route::get('/listings', [AdminSeoController::class, 'listings'])->name('listings');
        Route::post('/listings/{listing}', [AdminSeoController::class, 'updateListing'])->name('listings.update');
        Route::get('/blogs', [AdminSeoController::class, 'blogs'])->name('blogs');
        Route::post('/blogs/{blog}', [AdminSeoController::class, 'updateBlog'])->name('blogs.update');
        Route::get('/sitemap', [AdminSeoController::class, 'sitemap'])->name('sitemap');
        Route::post('/sitemap/generate', [AdminSeoController::class, 'generateSitemap'])->name('sitemap.generate');
        Route::get('/schema', [AdminSeoController::class, 'schema'])->name('schema');
        Route::get('/{slug}', [AdminSeoController::class, 'showSeoPage'])->name('showSeoPage');
    });


// View Tickets According Status
Route::get('/tickets/open', [TicketController::class, 'openTickets'])->name('tickets.open');
Route::get('/tickets/under-review', [TicketController::class, 'underReviewTickets'])->name('tickets.under_review');
Route::get('/tickets/completed', [TicketController::class, 'completedTickets'])->name('tickets.completed');
Route::get('/tickets/closed', [TicketController::class, 'closedTickets'])->name('tickets.closed');
Route::get('/tickets/rejected', [TicketController::class, 'rejectedTickets'])->name('tickets.rejected');

// USER
Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');

// OPTIONAL: ticket detail page (recommended for attachments viewing)
Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');

// ADMIN
Route::get('/admin/tickets', [TicketController::class, 'adminIndex'])->name('admin.tickets');
Route::post('/admin/tickets/{id}', [TicketController::class, 'updateStatus'])->name('ticket.updateStatus');

});
