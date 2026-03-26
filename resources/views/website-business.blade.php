@extends('home')

@section('website-title')
    <title>Mergersales | Business</title>
@endsection

@section('website-content')
    <section class="parallax-section single-par color-bg">
        <div class="container">
            <div class="section-title center-align big-title">
                <h2><span>Browse Confidential Businesses</span></h2>
                <h4>Find acquisition targets, investment opportunities, or strategic partners. All listings are 100%
                    anonymous by default.</h4>
            </div>
        </div>
        <div class="pwh_bg"></div>
    </section>

    <section class="gray-bg small-padding">
        <div class="container">
            <div class="row">
                <div class="mob-nav-content-btn color-bg show-list-wrap-search ntm fl-wrap">Show Filters</div>

                {{-- ================= LEFT FILTERS ================= --}}
                <div class="col-md-4">
                    <div class="fl-wrap lws_mobile">

                        {{-- ✅ IMPORTANT: Action = current URL (no hardcoded) --}}
                        <form method="GET" action="{{ url()->current() }}" id="businessFilterForm">

                            <div class="list-searh-input-wrap-title fl-wrap">
                                <i class="far fa-sliders-h"></i><span>Search Filters</span>
                            </div>

                            <div class="block-box fl-wrap search-sb" id="filters-column">

                                {{-- ✅ Deal Type (VALUES MUST MATCH DB ENUM EXACTLY) --}}
                                <div class="listsearch-input-item">
                                    <label>Deal Type</label>
                                    <select name="deal_type" data-placeholder="Deal Type"
                                        class="chosen-select on-radius no-search-select">
                                        <option value="" {{ !request('deal_type') ? 'selected' : '' }}>Any Deal Type
                                        </option>

                                        {{-- ✅ DB enum values: Sell business, Raise capital, Find buyer, Find partner --}}
                                        <option value="Sell business"
                                            {{ request('deal_type') == 'Sell business' ? 'selected' : '' }}>Sell Business
                                        </option>
                                        <option value="Raise capital"
                                            {{ request('deal_type') == 'Raise capital' ? 'selected' : '' }}>Raise Capital
                                        </option>
                                        <option value="Find partner"
                                            {{ request('deal_type') == 'Find partner' ? 'selected' : '' }}>Find Partner
                                        </option>
                                    </select>
                                </div>
                                <div class="listsearch-input-item">
                                    <div class="row">

                                        {{-- Industry (ID) --}}
                                        <div class="col-sm-12">
                                            <label>Industry</label>
                                            <select name="industry_id" data-placeholder="Industry"
                                                class="chosen-select on-radius no-search-select">
                                                <option value="" {{ !request('industry_id') ? 'selected' : '' }}>All
                                                    Industries</option>
                                                @foreach ($industries as $ind)
                                                    <option value="{{ $ind->id }}"
                                                        {{ (string) request('industry_id') === (string) $ind->id ? 'selected' : '' }}>
                                                        {{ $ind->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                    </div>
                                </div>

                                {{-- Revenue Range --}}
                                <div class="listsearch-input-item">
                                    <div class="price-rage-item fl-wrap">
                                        <span class="pr_title">Revenue Range:</span>
                                        <input type="text" class="price-range-double" data-min="10000"
                                            data-max="100000000" data-step="10000" name="revenue_range_ui"
                                            value="{{ request('revenue_range_ui') }}" data-prefix="€" data-max-text="+">
                                    </div>
                                </div>

                                {{-- EBITDA Range --}}
                                <div class="listsearch-input-item">
                                    <div class="price-rage-item fl-wrap">
                                        <span class="pr_title">EBITDA Range:</span>
                                        <input type="text" class="price-range-double" data-min="0" data-max="100000000"
                                            data-step="10000" name="ebitda_range_ui"
                                            value="{{ request('ebitda_range_ui') }}" data-prefix="€" data-max-text="+">
                                    </div>
                                </div>
                                <script>
                                    $(".price-range-double").ionRangeSlider({
                                        type: "double",
                                        min: 0,
                                        max: 100000000,
                                        from: 10000,
                                        to: 50000000,
                                        step: 10000,
                                        prefix: "€",
                                        prettify_enabled: true,
                                        prettify: function(num) {
                                            if (num >= 100000000) return "100M+";
                                            if (num >= 1000000) return (num / 1000000) + "M";
                                            if (num >= 1000) return (num / 1000) + "K";
                                            return num;
                                        }
                                    });
                                </script>
                                {{-- Employee + Country --}}
                                <div class="listsearch-input-item">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label>Employee Range</label>
                                            <select name="employee_range" data-placeholder="Employees"
                                                class="chosen-select on-radius no-search-select">
                                                <option value="" {{ !request('employee_range') ? 'selected' : '' }}>
                                                    Any Size</option>
                                                <option value="1-5"
                                                    {{ request('employee_range') == '1-5' ? 'selected' : '' }}>1-5
                                                </option>
                                                <option value="6-10"
                                                    {{ request('employee_range') == '6-10' ? 'selected' : '' }}>6-10
                                                </option>
                                                <option value="11-20"
                                                    {{ request('employee_range') == '11-20' ? 'selected' : '' }}>11-20
                                                </option>
                                                <option value="21-50"
                                                    {{ request('employee_range') == '21-50' ? 'selected' : '' }}>21-50
                                                </option>
                                                <option value="51-100"
                                                    {{ request('employee_range') == '51-100' ? 'selected' : '' }}>51-100
                                                </option>
                                                <option value="101-250"
                                                    {{ request('employee_range') == '101-250' ? 'selected' : '' }}>101-250
                                                </option>
                                                <option value="250+"
                                                    {{ request('employee_range') == '250+' ? 'selected' : '' }}>250+
                                                </option>
                                            </select>
                                        </div>

                                        <div class="col-sm-6">
                                            <label>Country</label>
                                            <select name="country" data-placeholder="All Countries"
                                                class="chosen-select on-radius no-search-select">
                                                <option value="" {{ !request('country') ? 'selected' : '' }}>All
                                                    Countries</option>
                                                <option value="England"
                                                    {{ request('country') == 'England' ? 'selected' : '' }}>England
                                                </option>
                                                <option value="London"
                                                    {{ request('country') == 'London' ? 'selected' : '' }}>London</option>
                                                <option value="Japan"
                                                    {{ request('country') == 'Japan' ? 'selected' : '' }}>Japan
                                                </option>
                                                <option value="USA" {{ request('country') == 'USA' ? 'selected' : '' }}>
                                                    USA
                                                </option>
                                                <option value="Japan"
                                                    {{ request('country') == 'Japan' ? 'selected' : '' }}>Japan</option>
                                                <option value="UK" {{ request('country') == 'UK' ? 'selected' : '' }}>
                                                    UK
                                                </option>
                                                <option value="UAE" {{ request('country') == 'UAE' ? 'selected' : '' }}>
                                                    UAE</option>
                                                <option value="India"
                                                    {{ request('country') == 'India' ? 'selected' : '' }}>India
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="msotw_footer">
                                    <button type="submit" id="filterSubmitBtn" class="btn small-btn float-btn color-bg"
                                        style="border:none">
                                        Search Businesses
                                    </button>

                                    {{-- ✅ Reset current page (remove query) --}}
                                    <a href="{{ url()->current() }}" class="reset-form reset-btn"
                                        style="display:inline-block;">
                                        <i class="far fa-sync-alt"></i> Reset Filters
                                    </a>
                                </div>

                            </div>
                        </form>

                        <a class="back-tofilters color-bg custom-scroll-link fl-wrap scroll-to-fixed-fixed" href="#">
                            Back to filters <i class="fas fa-caret-up"></i>
                        </a>

                    </div>
                </div>
                <style>
                    /* Chosen dropdown ko upar lao */
                    .chosen-container {
                        z-index: 9999 !important;
                        position: relative;
                    }

                    /* Back button ko neeche rakho */
                    .back-tofilters {
                        z-index: 1 !important;
                        position: relative;
                    }
                </style>

                {{-- ================= RIGHT LISTINGS ================= --}}
                <div class="col-md-8">

                    @php
                        $selectedIndustry = null;
                        if (request('industry_id')) {
                            $selectedIndustry = $industries->firstWhere('id', (int) request('industry_id'));
                        }
                    @endphp

                    <div class="list-main-wrap-header box-list-header fl-wrap">
                        <div class="list-main-wrap-title">
                            <h2>
                                Results :
                                <span>{{ $selectedIndustry ? $selectedIndustry->name : 'All Sectors' }}</span>
                                <strong>{{ $listings->total() }}</strong>
                            </h2>
                        </div>
                    </div>

                    {{-- LISTINGS --}}
                    <div class="listing-item-container box-list_ic fl-wrap"
                        style="display:flex; flex-wrap:wrap; gap:20px;">

                        @forelse ($listings as $listing)
                            @php
                                $img = $listing->business_img
                                    ? 'https://mergersales.com/storage/app/public/' . ltrim($listing->business_img, '/')
                                    : asset('images/1.jpg');

                                $singleUrl = route('business.single', $listing->id);
                            @endphp

                            <div class="listing-item" data-responsive-card
                                style="flex:1 1 calc(50% - 20px); max-width:calc(50% - 20px); display:flex; flex-direction:column;">
                                <article class="geodir-category-listing fl-wrap"
                                    style="height:100%;display:flex;flex-direction:column;background:#fff;border-radius:10px;overflow:hidden;box-shadow:0 8px 20px rgba(0,0,0,0.08);">

                                    <div class="geodir-category-img fl-wrap"
                                        style="height:230px;overflow:hidden;position:relative;flex-shrink:0;">

                                        <a href="{{ $singleUrl }}" style="display:block;width:100%;height:100%;">
                                            <img src="{{ $img }}" alt=""
                                                style="width:100%;height:100%;object-fit:cover;">
                                            <div class="overlay"></div>
                                        </a>

                                        <!-- BADGES CONTAINER -->
                                        <div
                                            style="
        position:absolute;
        top:15px;
        left:0;
        right:0;
        padding:0 15px;
        display:flex;
        justify-content:space-between;
        align-items:center;
        z-index:5;
    ">

                                            <!-- LEFT SIDE -->
                                            {{-- <span
                                                style="
            display:inline-flex;
            align-items:center;
            gap:6px;
            padding:6px 12px;
            font-size:12px;
            font-weight:600;
            border-radius:4px;
            background:#CCAA57;
            color:#fff;
        ">
                                                <i class="fas fa-briefcase" style="font-size:12px;"></i>
                                                {{ $listing->deal_type ?? 'Latest' }}
                                            </span> --}}

                                            <!-- RIGHT SIDE -->
                                            {{-- <span
                                                style="
            display:inline-flex;
            align-items:center;
            gap:6px;
            padding:6px 12px;
            font-size:12px;
            font-weight:600;
            border-radius:4px;
            background:#1a202c;
            color:#fff;
        ">
                                                <i class="fas fa-map-marker-alt" style="font-size:12px;"></i>
                                                {{ $listing->country ?? 'N/A' }}
                                            </span> --}}

                                        </div>

                                    </div>


                                    <div class="geodir-category-content fl-wrap"
                                        style="flex:1;display:flex;flex-direction:column;padding:18px;">
                                        <h3 style="min-height:48px; font-size:18px; line-height:1.4;">
                                            <a href="{{ $singleUrl }}">{{ $listing->business_name }}</a>
                                        </h3>

                                        {{-- <div class="geodir-category-content_price" style="font-weight:600;margin:8px 0;">
                                            {{ $listing->ebitda_range ?? 'Confidential' }}
                                        </div> --}}

                                        <p
                                            style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;min-height:44px;margin-bottom:12px;color:#555;">
                                            {{ $listing->description ?? 'No Description Available' }}
                                        </p>

                                        {{-- <div class="geodir-category-content-details">
                                            <ul>
                                                <li><i class="fal fa-dollar-sign"></i><span>Revenue:
                                                        {{ $listing->revenue_range ?? 'N/A' }}</span></li>
                                                <li><i class="fal fa-users"></i><span>Team:
                                                        {{ $listing->employee_range ?? 'N/A' }}</span></li>
                                            </ul>
                                        </div> --}}

                                        {{-- <div class="geodir-category-footer fl-wrap"
                                            style="margin-top:auto; padding-top:12px;">
                                            <a href="{{ $singleUrl }}" class="gcf-company">
                                                <img src="{{ asset($listing->user->profile_photo ?? 'images/default-user.png') }}"
                                                    alt=""
                                                    style="width:36px;height:36px;object-fit:cover;border-radius:50%;display:block;">
                                                <span>{{ $listing->user->name ?? 'Anonymous Seller' }}</span>
                                            </a>
                                        </div> --}}

                                    </div>
                                </article>
                            </div>

                        @empty
                            <div style="width:100%;">
                                <div class="alert alert-warning"
                                    style="background:#fff3cd;border:1px solid #ffeeba;padding:15px;border-radius:8px;">
                                    <strong>No businesses available</strong> for the selected filters.
                                    <div style="margin-top:8px;">
                                        <a href="{{ url()->current() }}" class="btn btn-sm"
                                            style="background:#CCAA57;color:#fff;border:none;">
                                            Clear Filters
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-4 d-flex justify-content-center">
                        {{ $listings->appends(request()->query())->links() }}
                    </div>

                </div>
            </div>
        </div>
    </section>

    <div class="limit-box fl-wrap"></div>

    {{-- ✅ Force submit (bypass theme JS) --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('businessFilterForm');
            const btn = document.getElementById('filterSubmitBtn');

            if (btn && form) {
                btn.addEventListener('click', function(e) {
                    // theme agar preventDefault kar rahi ho to bypass
                    e.preventDefault();
                    form.submit();
                });
            }
        });
    </script>
@endsection
