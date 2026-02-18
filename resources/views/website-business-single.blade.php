@extends('home')

@section('website-title')
    <title>Mergersales | Business Listing</title>
@endsection

@section('website-content')
    {{-- @php
        $heroBg = $business->business_img
            // ? ('http://localhost/Mergersales/storage/app/public/' . ltrim($business->business_img, '/'))
            : asset('images/22.jpg');
    @endphp --}}

    @php
        // ---------- Safe defaults (no design change) ----------

        $heroBg = $business->business_img
            ? 'http://localhost/Mergersales/storage/app/public/' . $business->business_img
            : asset('images/1.jpg');
        $dealType = $business->deal_type ?? 'For Sale';
        $industryName = $business->industry->name ?? 'Industry';
        $subIndustryName = $business->subIndustry->name ?? 'Business';

        $title = $business->business_name ?? ($business->title ?? 'Business Listing');
        $country = $business->country ?? 'Anonymous Location';

        $rating = $business->rating ?? 4;
        $ratingTitle = $business->rating_title ?? 'Premium Listing';

        $askingPrice = $business->asking_price ?? ($business->ebitda_range ?? 'Confidential');
        $listedDate = $business->created_at ? $business->created_at->format('d.m.Y') : '';

        $revenueRange = $business->revenue_range ?? 'N/A';
        $ebitdaMargin = $business->ebitda_margin ?? ($business->ebitda_range ?? 'N/A');
        $teamSize = $business->employee_range ?? 'N/A';
        $established = $business->established_year ?? ($business->business_age ?? 'N/A');

        $desc = $business->description ?? '';

        $sellerImg =
            $business->user && $business->user->profile_photo
                ? asset($business->user->profile_photo)
                : asset('images/16.jpg');

        $sellerName = $business->user->name ?? 'Anonymous Seller';
        $Role = $business->user->role ?? 'User';

        // Main image for gallery (if exists)
        $mainImg = $business->business_img ? 'storage/app/public/' . $business->business_img : asset('images/1.jpg');

        // Optional: If you have a JSON column like gallery_images (["path1","path2"...])
        $galleryImages = [];
        if (!empty($business->gallery_images)) {
            // if stored as JSON
            $galleryImages = is_array($business->gallery_images)
                ? $business->gallery_images
                : json_decode($business->gallery_images, true);
            $galleryImages = is_array($galleryImages) ? $galleryImages : [];
        }

        // fallback gallery (same design)
        if (count($galleryImages) == 0) {
            $galleryImages = [
                'images/2.jpg',
                'images/6.jpg',
                'images/1.jpg',
                'images/23.jpg',
                'images/5.jpg',
                'images/3.jpg',
            ];
        } else {
            // convert to full asset urls if they are stored in storage
            $galleryImages = array_map(function ($p) {
                // if already starts with http or asset, keep; else assume storage
                if (str_starts_with($p, 'http')) {
                    return $p;
                }
                if (str_starts_with($p, 'images/')) {
                    return asset($p);
                }
                return asset('storage/' . $p);
            }, $galleryImages);
        }

        $currency = $business->currency ?? 'USD';

        // symbol mapping
        $currencySymbol = match ($currency) {
            'USD' => '$',
            'EUR' => '€',
            'GBP' => '£',
            'PKR' => 'Rs',
            'AED' => 'د.إ',
            default => '$',
        };

        $price = $business->asking_price ?? ($business->ebitda_range ?? 'Confidential');

    @endphp

    <section class="hidden-section single-hero-section" data-scrollax-parent="true" id="sec1">
        <div class="bg-wrap bg-parallax-wrap-gradien">
            <div class="bg par-elem" data-bg="{{ $heroBg }}" data-scrollax="properties: { translateY: '30%' }">
            </div>
        </div>
        <div class="container">

            <div class="list-single-opt_header fl-wrap">
                <ul class="list-single-opt_header_cat">
                    <li><a href="#" class="cat-opt color-bg">{{ $dealType }}</a></li>
                    <li><a href="#" class="cat-opt blue-bg">{{ $industryName }}</a></li>
                    <li><a href="#" class="cat-opt color-bg">{{ $subIndustryName }}</a></li>
                </ul>
            </div>

            <div class="list-single-header-item no-bg-list_sh fl-wrap">
                <div class="row">
                    <div class="col-md-12">
                        <h1>{{ $title }}
                            {{-- <span class="verified-badge tolt" data-microtip-position="bottom"
                                data-tooltip="Verified Financials">
                                <i class="fas fa-check"></i>
                            </span> --}}
                        </h1>

                        <div class="geodir-category-location fl-wrap">
                            <a href="#"><i class="fas fa-globe-americas"></i> {{ $country }}</a>
                        </div>
                    </div>
                </div>

                <div class="list-single-header-footer fl-wrap">
                    <div class="list-single-header-price" data-propertyprise="50500">
                        <strong>Asking Price:</strong>
                        {!! $askingPrice !!}
                    </div>
                    <div class="list-single-header-date">
                        <span>Listed:</span> {{ $business->created_at->format('d M Y') }}
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="gray-bg small-padding fl-wrap">
        <div class="container">
            <div class="row">

                <div class="col-md-8">
                    <div class="list-single-main-wrapper fl-wrap">
                        <div class="scroll-nav-wrap">
                            <nav class="scroll-nav scroll-init fixed-column_menu-init">
                                <ul class="no-list-style">
                                    <li><a class="act-scrlink" href="#sec1"><i
                                                class="fal fa-chart-line"></i></a><span>Overview</span></li>
                                    <li><a href="#sec2"><i class="fal fa-image"></i></a><span>Gallery</span></li>
                                    <li><a href="#sec3"><i
                                                class="fal fa-file-invoice-dollar"></i></a><span>Financials</span></li>
                                    <li><a href="#sec5"><i class="fal fa-video"></i></a><span>Video</span></li>
                                    <li><a href="#sec7"><i class="fal fa-comment-alt-lines"></i></a><span>Inquiries</span>
                                    </li>
                                </ul>
                            </nav>
                        </div>

                        {{-- GALLERY (dynamic + fallback) --}}
                        <div class="list-single-main-media fl-wrap" id="sec2">
                            <div class="gallery-items grid-small-pad list-single-gallery three-coulms lightgallery">

                                @foreach ($galleryImages as $g)
                                    @php
                                        $imgUrl = str_contains($g, 'http') ? $g : $g;
                                        // if fallback array has 'images/...', convert to asset
                                        if (str_starts_with($g, 'images/')) {
                                            $imgUrl = asset($g);
                                        }
                                    @endphp

                                    <div class="gallery-item {{ $loop->index == 2 ? 'gallery-item-second' : '' }}">
                                        <div class="grid-item-holder">
                                            <div class="box-item">
                                                <img src="{{ $imgUrl }}" alt="">
                                                <a href="{{ $imgUrl }}" class="gal-link popup-image"><i
                                                        class="fa fa-search"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>

                        {{-- FACTS (dynamic) --}}
                        <div class="list-single-facts fl-wrap">
                            <div class="inline-facts-wrap">
                                <div class="inline-facts">
                                    <i class="fal fa-chart-bar"></i>
                                    <h6>Revenue</h6>
                                    <span>{{ $revenueRange }}</span>
                                </div>
                            </div>
                            <div class="inline-facts-wrap">
                                <div class="inline-facts">
                                    <i class="fal fa-percentage"></i>
                                    <h6>EBITDA Margin</h6>
                                    <span>{{ $ebitdaMargin }}</span>
                                </div>
                            </div>
                            <div class="inline-facts-wrap">
                                <div class="inline-facts">
                                    <i class="fal fa-users"></i>
                                    <h6>Team Size</h6>
                                    <span>{{ $teamSize }}</span>
                                </div>
                            </div>
                            <div class="inline-facts-wrap">
                                <div class="inline-facts">
                                    <i class="fal fa-calendar-alt"></i>
                                    <h6>Established</h6>
                                    <span>{{ $business->created_at->format('d M Y') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="list-single-main-container fl-wrap" id="sec3">

                            {{-- BUSINESS OVERVIEW (dynamic) --}}
                            <div class="list-single-main-item fl-wrap">
                                <div class="list-single-main-item-title">
                                    <h3>Business Overview</h3>
                                </div>
                                <div class="list-single-main-item_content fl-wrap">
                                    <p>{!! nl2br(e($desc)) !!}</p>
                                    {{-- <a href="#" class="btn float-btn color-bg">Request Teaser</a> --}}
                                </div>
                            </div>

                            {{-- FINANCIAL DETAILS (dynamic) --}}
                            <div class="list-single-main-item fl-wrap">
                                <div class="list-single-main-item-title">
                                    <h3>Financial Details</h3>
                                </div>
                                <div class="list-single-main-item_content fl-wrap">
                                    <div class="details-list">
                                        <ul>
                                            <li><span>Annual Revenue:</span>{{ $revenueRange }}</li>
                                            <li><span>EBITDA:</span>{{ $business->ebitda_range ?? 'N/A' }}</li>
                                            <li><span>Currency:</span>{{ $business->currency ?? 'N/A' }}</li>
                                            <li><span>Team Size:</span>{{ $teamSize }}</li>
                                            <li><span>Business Type:</span>{{ $industryName }}</li>
                                            <li><span>Sub Industry:</span>{{ $subIndustryName }}</li>
                                            <li><span>Country:</span>{{ $country }}</li>
                                            <li><span>Asking Price:</span>{!! $askingPrice !!}</li>
                                            <li><span>Sale Reason:</span>{!! $business->reason_for_sale ?? 'N/A' !!}</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            {{-- OPERATIONS (static blocks - keep design, can make dynamic later) --}}
                            {{-- <div class="list-single-main-item fl-wrap" id="sec4">
                                <div class="list-single-main-item-title fl-wrap">
                                    <h3>Operations & Team</h3>
                                </div>

                                <div class="rooms-container fl-wrap">
                                    <div class="rooms-item fl-wrap">
                                        <div class="rooms-media">
                                            <img src="{{ asset('images/24.jpg') }}" alt="">
                                            <div class="dynamic-gal more-photos-button color-bg">
                                                <i class="fas fa-user-tie"></i> <span>Leadership Team</span>
                                            </div>
                                        </div>
                                        <div class="rooms-details">
                                            <div class="rooms-details-header fl-wrap">
                                                <span class="rooms-area">Management<strong> Team</strong></span>
                                                <h3>Executive Leadership</h3>
                                                <h5>Key Roles: <span>{{ $business->team_roles ?? 'N/A' }}</span></h5>
                                            </div>
                                            <p>{{ $business->operations_note ?? 'N/A' }}</p>
                                            <div class="facilities-list fl-wrap">
                                                <ul>
                                                    <li class="tolt" data-tooltip="Technical Leadership"><i
                                                            class="fal fa-code"></i></li>
                                                    <li class="tolt" data-tooltip="Sales Management"><i
                                                            class="fal fa-chart-line"></i></li>
                                                    <li class="tolt" data-tooltip="Operations"><i
                                                            class="fal fa-cogs"></i></li>
                                                    <li class="tolt" data-tooltip="Customer Success"><i
                                                            class="fal fa-headset"></i></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="rooms-item fl-wrap">
                                        <div class="rooms-media">
                                            <img src="{{ asset('images/26.jpg') }}" alt="">
                                            <div class="dynamic-gal more-photos-button color-bg">
                                                <i class="fas fa-code-branch"></i> <span>Tech Stack</span>
                                            </div>
                                        </div>
                                        <div class="rooms-details">
                                            <div class="rooms-details-header fl-wrap">
                                                <span class="rooms-area">Technology<strong> Platform</strong></span>
                                                <h3>Modern Tech Infrastructure</h3>
                                                <h5>Primary Stack: <span>{{ $business->tech_stack ?? 'N/A' }}</span></h5>
                                            </div>
                                            <p>{{ $business->tech_note ?? 'N/A' }}</p>
                                            <div class="facilities-list fl-wrap">
                                                <ul>
                                                    <li class="tolt" data-tooltip="Cloud Native"><i
                                                            class="fal fa-cloud"></i></li>
                                                    <li class="tolt" data-tooltip="Scalable Architecture"><i
                                                            class="fal fa-server"></i></li>
                                                    <li class="tolt" data-tooltip="API First"><i
                                                            class="fal fa-plug"></i></li>
                                                    <li class="tolt" data-tooltip="Automated Testing"><i
                                                            class="fal fa-vial"></i></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="rooms-item fl-wrap">
                                        <div class="rooms-media">
                                            <img src="{{ asset('images/28.jpg') }}" alt="">
                                            <div class="dynamic-gal more-photos-button color-bg">
                                                <i class="fas fa-chart-pie"></i> <span>Revenue Streams</span>
                                            </div>
                                        </div>
                                        <div class="rooms-details">
                                            <div class="rooms-details-header fl-wrap">
                                                <span class="rooms-area">Revenue<strong> Model</strong></span>
                                                <h3>Multiple Revenue Streams</h3>
                                                <h5>Primary Model: <span>{{ $business->revenue_model ?? 'N/A' }}</span>
                                                </h5>
                                            </div>
                                            <p>{{ $business->revenue_note ?? 'N/A' }}</p>
                                            <div class="facilities-list fl-wrap">
                                                <ul>
                                                    <li class="tolt" data-tooltip="Subscription Revenue"><i
                                                            class="fal fa-calendar-alt"></i></li>
                                                    <li class="tolt" data-tooltip="Enterprise Contracts"><i
                                                            class="fal fa-file-contract"></i></li>
                                                    <li class="tolt" data-tooltip="Professional Services"><i
                                                            class="fal fa-tools"></i></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> --}}

                            {{-- VIDEO (optional dynamic youtube link) --}}
                            <div class="list-single-main-item fl-wrap" id="sec5">
                                <div class="list-single-main-item-title">
                                    <h3>Business Presentation</h3>
                                </div>
                                <div class="list-single-main-item_content fl-wrap">
                                    <div class="video-box fl-wrap">
                                        <img src="{{ $heroBg }}" class="respimg" alt="Business Video">

                                        <a class="video-box-btn image-popup color-bg"
                                            href="{{ $business->video_url ?? 'https://www.youtube.com/watch?v=9v5Hx1dJJig&pp=ygUhaW50ZXJuYXRpb25hbCBidXNpbmVzcyBpbiBlbmdsaXNo' }}">
                                            <i class="fas fa-play"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            {{-- KEY FEATURES (optional: from DB csv/json, else keep static) --}}
                            <div class="list-single-main-item fl-wrap">
                                <div class="list-single-main-item-title">
                                    <h3>Key Features</h3>
                                </div>
                                <div class="list-single-main-item_content fl-wrap">
                                    <div class="listing-features ">
                                        <ul>
                                            @php
                                                $features = [];
                                                if (!empty($business->features)) {
                                                    $features = is_array($business->features)
                                                        ? $business->features
                                                        : explode(',', $business->features);
                                                }
                                                $features = array_filter(array_map('trim', $features));
                                                if (count($features) == 0) {
                                                    $features = [
                                                        'Recurring Revenue',
                                                        'Proprietary Technology',
                                                        'Global Customer Base',
                                                        'Remote Team',
                                                        'High Growth Market',
                                                        'Strong IP Portfolio',
                                                        'Automated Operations',
                                                        'Strategic Partnerships',
                                                    ];
                                                }
                                            @endphp

                                            @foreach ($features as $f)
                                                <li><a href="#"><i class="fal fa-check"></i>
                                                        {{ $f }}</a></li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            {{-- Inquiries section keep static for now --}}
                            <div class="list-single-main-item fl-wrap" id="sec7">
                                <div class="list-single-main-item-title">
                                    <h3>Recent Inquiries</h3>
                                </div>

                                <div class="list-single-main-item_content fl-wrap">
                                    <div class="reviews-comments-wrap fl-wrap">
                                        @forelse($enquiries as $enq)
                                            <div class="reviews-comments-item">
                                                <div class="review-comments-avatar">
                                                    <img src="{{ asset($enq->user->profile_photo ?? 'images/default-avatar.jpg') }}"
                                                        alt="">
                                                </div>

                                                <div class="reviews-comments-item-text smpar">
                                                    <div class="box-widget-menu-btn smact">
                                                        <i class="far fa-ellipsis-h"></i>
                                                    </div>

                                                    {{-- ✅ Unique IDs per enquiry (important) --}}
                                                    <div class="show-more-snopt-tooltip bxwt">
                                                        <a href="#" class="enq-status-btn"
                                                            data-target="enquireStatus-{{ $enq->id }}">
                                                            <i class="fas fa-reply"></i> Enquire Status
                                                        </a>
                                                        {{-- <a href="#"><i class="fas fa-exclamation-triangle"></i> Report</a> --}}
                                                    </div>

                                                    <div id="enquireStatus-{{ $enq->id }}"
                                                        style="display:none; margin-top:8px; color:#28a745; font-weight:600;">
                                                        Enquire Status Is {{ $enq->status }}
                                                    </div>

                                                    <h4>{{ $enq->name }}</h4>

                                                    <div class="listing-rating card-popup-rainingvis"
                                                        data-starrating2="{{ (int) ($enq->stars ?? 0) }}">
                                                        <span
                                                            class="re_stars-title">{{ $enq->interest_type ?? 'Inquiry' }}</span>
                                                    </div>

                                                    <div class="clearfix"></div>

                                                    <h6 class="list-single-main-item-title">Message</h6>
                                                    <p>
                                                        {{ \Illuminate\Support\Str::words($enq->message, 20, '...') }}
                                                    </p>


                                                    {{-- ✅ Extra Enquiry Info: Company, Position, Budget, Timeline, NDA --}}
                                                    <div class="list-single-main-item-title"
                                                        style="margin-top:10px; line-height:1.8; display:flex; flex-wrap:wrap; gap:12px 18px;">

                                                        {{-- Company --}}
                                                        @if (!empty($enq->company))
                                                            <div class="mb-1"
                                                                style="flex:0 0 calc(50% - 9px); box-sizing:border-box;">
                                                                <strong>Company:</strong>
                                                                <p style="margin:0;">{{ $enq->company }}</p>
                                                            </div>
                                                        @endif

                                                        {{-- Position --}}
                                                        @if (!empty($enq->position))
                                                            <div class="mb-1"
                                                                style="flex:0 0 calc(50% - 9px); box-sizing:border-box;">
                                                                <strong>Position:</strong>
                                                                <p style="margin:0;">{{ $enq->position }}</p>
                                                            </div>
                                                        @endif

                                                        {{-- Budget --}}
                                                        @if (!empty($enq->budget))
                                                            <div class="mb-1"
                                                                style="flex:0 0 calc(50% - 9px); box-sizing:border-box;">
                                                                <strong>Budget:</strong>
                                                                <p style="margin:0;">{{ $enq->budget }}</p>
                                                            </div>
                                                        @endif

                                                        {{-- Timeline --}}
                                                        @if (!empty($enq->timeline))
                                                            <div class="mb-1"
                                                                style="flex:0 0 calc(50% - 9px); box-sizing:border-box;">
                                                                <strong>Timeline:</strong>
                                                                <p style="margin:0;">
                                                                    @if ($enq->timeline === '1_month')
                                                                        Within 1 Month
                                                                    @elseif($enq->timeline === '3_months')
                                                                        Within 3 Months
                                                                    @elseif($enq->timeline === '6_months')
                                                                        Within 6 Months
                                                                    @elseif($enq->timeline === 'flexible')
                                                                        Flexible
                                                                    @else
                                                                        {{ $enq->timeline }}
                                                                    @endif
                                                                </p>
                                                            </div>
                                                        @endif
                                                    </div>


                                                    <div class="reviews-comments-item-date">
                                                        <span class="reviews-comments-item-date-item">
                                                            <i class="far fa-calendar-check"></i>
                                                            {{ optional($enq->created_at)->format('d F Y') }}
                                                        </span>

                                                        @if (!empty($enq->status_text))
                                                            <a href="#" class="rate-review">
                                                                <i class="fal fa-file-signature"></i>
                                                                {{ $enq->status_text }}
                                                                <span>{{ (int) ($enq->status_count ?? 0) }}</span>
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="reviews-comments-item">
                                                <div class="reviews-comments-item-text smpar">
                                                    <p>No enquiries found.</p>
                                                </div>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>

                                {{-- ✅ One-time script for all items (no duplicate IDs issue) --}}
                                <script>
                                    document.addEventListener("DOMContentLoaded", function() {
                                        document.querySelectorAll(".enq-status-btn").forEach(function(btn) {
                                            btn.addEventListener("click", function(e) {
                                                e.preventDefault();
                                                const targetId = btn.getAttribute("data-target");
                                                const el = document.getElementById(targetId);
                                                if (!el) return;

                                                // Toggle show/hide
                                                el.style.display = (el.style.display === "none" || el.style.display === "") ?
                                                    "block" : "none";
                                            });
                                        });
                                    });
                                </script>


                            </div>

                            {{-- Submit inquiry keep static --}}

                            <div class="list-single-main-item fl-wrap" id="sec15">
                                <div class="list-single-main-item-title fl-wrap">
                                    <h3>Submit Inquiry</h3>
                                </div>
                                <div id="add-review" class="add-review-box">
                                    {{-- Success message (page top par 1 dafa rakh dein) --}}
                                    @if (session('enquiry_success'))
                                        <div class="alert alert-success">
                                            {{ session('enquiry_success') }}
                                        </div>
                                    @endif

                                    {{-- Validation errors (page top par 1 dafa rakh dein) --}}
                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul class="mb-0">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    <form method="POST" action="{{ route('enquiry.store') }}"
                                        class="add-comment custom-form" enctype="multipart/form-data"
                                        style="box-sizing:border-box;">

                                        @csrf

                                        <input type="hidden" name="listing_id" value="{{ $listing->id ?? '' }}">

                                        <fieldset>
                                            <div class="row" style="display:flex; flex-wrap:wrap;">

                                                <div class="col-md-6" style="margin-bottom:15px;">
                                                    <label style="display:block; margin-bottom:8px;">
                                                        Your Name*
                                                        <span class="dec-icon"><i class="fas fa-user"></i></span>
                                                    </label>
                                                    <input name="name" type="text" placeholder="Full Name"
                                                        value="{{ old('name') }}" required
                                                        style="width:100%; box-sizing:border-box;">
                                                </div>

                                                <div class="col-md-6" style="margin-bottom:15px;">
                                                    <label style="display:block; margin-bottom:8px;">
                                                        Your Email*
                                                        <span class="dec-icon"><i class="fas fa-envelope"></i></span>
                                                    </label>
                                                    <input name="email" type="email" placeholder="Email Address"
                                                        value="{{ old('email') }}" required
                                                        style="width:100%; box-sizing:border-box;">
                                                </div>

                                                <div class="col-md-6" style="margin-bottom:15px;">
                                                    <label style="display:block; margin-bottom:8px;">
                                                        Your Phone*
                                                        <span class="dec-icon"><i class="fas fa-phone"></i></span>
                                                    </label>
                                                    <input name="phone" type="text" placeholder="Phone Number"
                                                        value="{{ old('phone') }}" required
                                                        style="width:100%; box-sizing:border-box;">
                                                </div>

                                                <div class="col-md-6" style="margin-bottom:15px;">
                                                    <label style="display:block; margin-bottom:8px;">
                                                        Your Budget*
                                                        <span class="dec-icon"><i class="fas fa-dollar-sign"></i></span>
                                                    </label>
                                                    <input name="budget" type="text" placeholder="Budget Amount"
                                                        value="{{ old('budget') }}" required
                                                        style="width:100%; box-sizing:border-box;">
                                                </div>

                                                <div class="col-md-6" style="margin-bottom:15px;">
                                                    <label style="display:block; margin-bottom:8px;">
                                                        Company
                                                        <span class="dec-icon"><i class="fas fa-building"></i></span>
                                                    </label>
                                                    <input name="company" type="text" placeholder="Company Name"
                                                        value="{{ old('company') }}"
                                                        style="width:100%; box-sizing:border-box;">
                                                </div>

                                                <div class="col-md-6" style="margin-bottom:15px;">
                                                    <label style="display:block; margin-bottom:8px;">
                                                        Position
                                                        <span class="dec-icon"><i class="fas fa-building"></i></span>
                                                    </label>
                                                    <input name="position" type="text" placeholder="Position"
                                                        value="{{ old('position') }}"
                                                        style="width:100%; box-sizing:border-box;">
                                                </div>

                                                <div class="col-md-6" style="margin-bottom:15px;">
                                                    <label style="display:block; margin-bottom:8px;">
                                                        Timeline*
                                                        <span class="dec-icon"><i class="fas fa-clock"></i></span>
                                                    </label>

                                                    <select name="timeline" data-placeholder="Select Timeline"
                                                        class="chosen-select on-radius no-search-select" required
                                                        style="width:100%; box-sizing:border-box;">

                                                        <option value="">Select Timeline</option>
                                                        <option value="1_month"
                                                            {{ old('timeline') == '1_month' ? 'selected' : '' }}>
                                                            Within 1 Month
                                                        </option>
                                                        <option value="3_months"
                                                            {{ old('timeline') == '3_months' ? 'selected' : '' }}>
                                                            Within 3 Months
                                                        </option>
                                                        <option value="flexible"
                                                            {{ old('timeline') == 'flexible' ? 'selected' : '' }}>
                                                            Flexible
                                                        </option>
                                                    </select>
                                                </div>

                                                <div class="col-md-6" style="margin-bottom:15px;">
                                                    <label style="display:block; margin-bottom:8px;">
                                                        Type Of Interest*
                                                        <span class="dec-icon"><i class="fas fa-briefcase"></i></span>
                                                    </label>

                                                    <select name="interest_type" data-placeholder="Select Type"
                                                        class="chosen-select on-radius no-search-select" required
                                                        style="width:100%; box-sizing:border-box;">

                                                        <option value="">Select Type</option>
                                                        <option value="buy"
                                                            {{ old('interest_type') == 'buy' ? 'selected' : '' }}>
                                                            Buy Full Business
                                                        </option>
                                                        <option value="partner"
                                                            {{ old('interest_type') == 'partner' ? 'selected' : '' }}>
                                                            Partner / Joint Venture
                                                        </option>
                                                    </select>
                                                </div>

                                                <div class="col-md-6" style="margin-bottom:15px;">
                                                    <label style="display:block; margin-bottom:8px;">
                                                        Attachments Documents
                                                    </label>

                                                    <div class="file-input-wrapper"
                                                        style="width:100%; height:48px; box-sizing:border-box;">
                                                        <input type="file" name="attachments[]" id="attachments"
                                                            multiple
                                                            style="width:100%; height:48px; box-sizing:border-box;">
                                                    </div>
                                                </div>

                                                <div class="col-md-12" style="margin-bottom:20px;">
                                                    <label for="message" style="display:block; margin-bottom:8px;">
                                                        Enquire Message
                                                        <span class="dec-icon"><i class="fas fa-paperclip"></i></span>
                                                    </label>
                                                    <textarea name="message" cols="40" rows="3"
                                                        placeholder="Your inquiry message and any specific questions..." required
                                                        style="width:100%; box-sizing:border-box; min-height:110px;">{{ old('message') }}</textarea>
                                                </div>

                                                <!-- ✅ Checkbox + Label properly aligned (same design, just fixed structure) -->
                                                <div class="col-md-12" style="margin-bottom:15px;">
                                                    <div class="form-check"
                                                        style="display:flex; align-items:center; gap:10px;">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="nda_required" value="1"
                                                            id="ndaReq{{ $listing->id }}" required style="margin:0;"
                                                            required>
                                                        <label class="form-check-label" for="ndaReq{{ $listing->id }}"
                                                            style="margin:0;">
                                                            Please Check Box
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>

                                        <button type="submit" class="btn big-btn color-bg float-btn">
                                            Submit Inquiry
                                            <i class="fa fa-paper-plane-o" aria-hidden="true"></i>
                                        </button>
                                    </form>


                                </div>

                            </div>

                        </div>
                    </div>
                </div>

                {{-- RIGHT SIDEBAR --}}
                <div class="col-md-4">
                    <div class="box-widget fl-wrap">
                        <div class="profile-widget">
                            <div class="profile-widget-header color-bg smpar fl-wrap"
                                style="display:flex; justify-content:center; align-items:center;">
                                {{-- <div class="pwh_bg"></div>
                                <div class="call-btn"><a href="#" class="tolt color-bg"
                                        data-microtip-position="right" data-tooltip="Request NDA"><i
                                            class="fas fa-file-signature"></i></a></div>

                                <div class="box-widget-menu-btn smact"><i class="far fa-ellipsis-h"></i></div>

                                <div class="show-more-snopt-tooltip bxwt">
                                    <a href="#"> <i class="fas fa-download"></i> Download Teaser</a>
                                    <a href="#"> <i class="fas fa-exclamation-triangle"></i> Report Listing </a>
                                </div> --}}

                                <div class="profile-widget-card" style="text-align:center;">
                                    <div class="profile-widget-image">
                                        <img src="{{ $sellerImg }}" alt="">
                                    </div>
                                    <div class="profile-widget-header-title">
                                        <h4><a href="#">{{ $sellerName }}</a></h4>
                                        <h4><a href="#">{{ $Role }}</a></h4>
                                        <div class="listing-rating card-popup-rainingvis" data-starrating2="#"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="profile-widget-content fl-wrap">
                                <div class="contats-list fl-wrap">
                                    <ul class="no-list-style">
                                        <li><span><i class="fal fa-briefcase"></i> Industry :</span> {{ $industryName }}
                                        </li>
                                        <li><span><i class="fal fa-briefcase"></i> Sub Industry :</span>
                                            {{ $subIndustryName }}
                                        </li>
                                        <li><span><i class="fal fa-flag"></i> Country :</span> {{ $business->country }}
                                        </li>
                                        <li><span><i class="fal fa-dollar"></i> currency :</span>
                                            {{ $business->currency }}
                                        </li>
                                        <li><span><i class="fal fa-dollar"></i> Ebitda :</span>
                                            {{ $business->ebitda_range }}
                                        </li>
                                        <li><span><i class="fal fa-calendar-alt"></i> Established :</span>
                                            {{ $business->created_at->format('d M Y') }}</li>
                                        <li><span><i class="fal fa-users"></i> Team Size :</span> {{ $teamSize }}</li>
                                    </ul>
                                </div>
                                {{-- <div class="profile-widget-footer fl-wrap">
                                    <a href="#" class="btn float-btn color-bg small-btn">Send Secure Message</a>
                                    <a href="#" class="custom-scroll-link tolt" data-microtip-position="left"
                                        data-tooltip="Save Business"><i class="fal fa-heart"></i></a>
                                </div> --}}
                            </div>
                        </div>
                    </div>

                    {{-- Similar Businesses (dynamic) --}}
                    <div class="box-widget fl-wrap">
                        <div class="box-widget-title fl-wrap">Similar Businesses</div>
                        <div class="box-widget-content fl-wrap">
                            <div class="widget-posts fl-wrap">
                                <ul class="no-list-style">

                                    @foreach ($similar as $item)
                                        @php
                                            $itemImg = $item->business_img
                                                ? 'http://localhost/Mergersales/storage/app/public/' .
                                                    $item->business_img
                                                : asset('images/1.jpg');

                                            $itemTitle = $item->business_name ?? ($item->title ?? 'Business');
                                            $itemCountry = $item->country ?? 'N/A';
                                            $itemPrice = $item->ebitda_range ?? 'Confidential';
                                        @endphp

                                        <li>
                                            <div class="widget-posts-img">
                                                <a href="{{ route('business.single', $item->id) }}">
                                                    <img src="{{ $itemImg }}" alt="">
                                                </a>
                                            </div>
                                            <div class="widget-posts-descr">
                                                <h4>
                                                    <a
                                                        href="{{ route('business.single', $item->id) }}">{{ $itemTitle }}</a>
                                                </h4>
                                                <div class="geodir-category-location fl-wrap">
                                                    <a href="#"><i class="fas fa-globe"></i>
                                                        {{ $itemCountry }}</a>
                                                </div>
                                                <div class="widget-posts-descr-price">
                                                    <span>Price: </span> {{ $itemPrice }}
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach

                                </ul>
                            </div>
                            <a href="{{ url('website-business') }}" class="btn float-btn color-bg small-btn">Browse All
                                Businesses</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
