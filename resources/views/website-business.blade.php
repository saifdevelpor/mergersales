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

                                {{-- Keyword --}}
                                <div class="listsearch-input-item">
                                    <label>Keyword</label>
                                    <input type="text" name="keyword" placeholder="Search by keyword..."
                                        value="{{ request('keyword') }}" />
                                </div>

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
                                            <select name="industry_id" id="industrySelectBusiness"
                                                data-placeholder="Industry" class="chosen-select on-radius no-search-select"
                                                onchange="window.loadBusinessSubIndustries && window.loadBusinessSubIndustries(this.value)">
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

                                {{-- Sub Industry --}}
                                <div class="listsearch-input-item">
                                    <label>Sub-Industry</label>
                                    <select name="sub_industry_id" id="subIndustrySelectBusiness"
                                        data-placeholder="Sub-Industry" class="on-radius subindustry-native-select">
                                        <option value="" {{ !request('sub_industry_id') ? 'selected' : '' }}>All
                                            Sub-Industries</option>
                                        @php
                                            $selectedInd = null;
                                            $indId = request('industry_id') ?? request('industry');
                                            if ($indId) {
                                                $selectedInd = $industries->firstWhere('id', (int) $indId);
                                            }
                                        @endphp
                                        @if ($selectedInd)
                                            @foreach ($selectedInd->subIndustries as $sub)
                                                <option value="{{ $sub->id }}"
                                                    {{ (string) request('sub_industry_id') === (string) $sub->id ? 'selected' : '' }}>
                                                    {{ $sub->name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
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
                                            <input type="text" name="country" id="countryAutocomplete"
                                                placeholder="All countries (search any country)"
                                                value="{{ request('country') }}" class="on-radius"
                                                style="width:100%;height:48px;padding:10px 14px;border:1px solid #e5e7eb;border-radius:6px;">
                                            <div id="countrySuggestions" class="country-suggestions" style="display:none;">
                                            </div>
                                            {{-- <small style="display:block;margin-top:6px;color:#6b7280;">
                                                Leave empty for all countries. Type to search globally via Google Maps.
                                            </small> --}}
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

                        <a class="back-tofilters color-bg custom-scroll-link fl-wrap scroll-to-fixed-fixed"
                            href="#">
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

                    /* Sub-Industry native select: keep same visual style as other filters */
                    .subindustry-native-select {
                        display: block !important;
                        width: 100%;
                        height: 50px;
                        padding: 12px 42px 12px 14px;
                        border: 1px solid #e5e7eb;
                        border-radius: 6px;
                        background-color: #f6f7fb;
                        color: #6a7fa2;
                        font-size: 15px;
                        line-height: 24px;
                        appearance: none;
                        -webkit-appearance: none;
                        -moz-appearance: none;
                        background-image: linear-gradient(45deg, transparent 50%, #caa95a 50%), linear-gradient(135deg, #caa95a 50%, transparent 50%);
                        background-position: calc(100% - 22px) calc(50% - 3px), calc(100% - 15px) calc(50% - 3px);
                        background-size: 7px 7px, 7px 7px;
                        background-repeat: no-repeat;
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
                                    ? asset('storage/' . ltrim($listing->business_img, '/'))
                                    : asset('assets/images/1.jpg');

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
                                            <span
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
                                            </span>

                                            <!-- RIGHT SIDE -->
                                            <span
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
                                            </span>

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
                                            style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;margin-bottom:8px;color:#555;">
                                            {{ $listing->description ?? 'No Description Available' }}
                                        </p>

                                        <a href="{{ route('business.single', $listing->id) }}">Read More</a>

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

    {{-- Sub-industry AJAX populate --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ind = document.getElementById('industrySelectBusiness');
            const sub = document.getElementById('subIndustrySelectBusiness');
            if (!ind || !sub) return;
            const selectedSubId = "{{ request('sub_industry_id') }}";
            const localSubIndustryMap = @json(
                $industries->mapWithKeys(function ($industry) {
                    return [
                        (string) $industry->id => $industry->subIndustries->map(function ($sub) {
                                return ['id' => $sub->id, 'name' => $sub->name];
                            })->values(),
                    ];
                }));

            async function loadSubs(industryId) {
                // reset
                sub.innerHTML = '<option value="">All Sub-Industries</option>';
                if (!industryId) {
                    return;
                }
                const localItems = localSubIndustryMap[String(industryId)] || [];

                // Primary source: server-provided in-page map (fast + reliable)
                localItems.forEach(function(item) {
                    const opt = document.createElement('option');
                    opt.value = item.id;
                    opt.textContent = item.name;
                    if (selectedSubId && String(item.id) === String(selectedSubId)) {
                        opt.selected = true;
                    }
                    sub.appendChild(opt);
                });

                // Fallback source: API route (if local map is empty for any reason)
                if (!localItems.length) {
                    try {
                        const url = "{{ route('sub-industries.by-industry', ['industry' => '__ID__']) }}"
                            .replace('__ID__', encodeURIComponent(industryId));
                        const res = await fetch(url, {
                            headers: {
                                'Accept': 'application/json'
                            }
                        });
                        const data = await res.json();
                        (Array.isArray(data) ? data : []).forEach(function(item) {
                            const opt = document.createElement('option');
                            opt.value = item.id;
                            opt.textContent = item.name;
                            if (selectedSubId && String(item.id) === String(selectedSubId)) {
                                opt.selected = true;
                            }
                            sub.appendChild(opt);
                        });
                    } catch (e) {
                        // ignore fallback errors
                    }
                }
            }

            // Expose globally for inline onchange fallback.
            window.loadBusinessSubIndustries = loadSubs;

            ind.addEventListener('change', function() {
                loadSubs(this.value);
            });

            // Chosen plugin compatibility: bind explicitly on jQuery too.
            if (window.jQuery) {
                jQuery(ind).on('change', function() {
                    const val = jQuery(this).val();
                    loadSubs(val);
                });
            }

            // If page is loaded with industry in query params, populate sub-industries immediately.
            if (ind.value) {
                loadSubs(ind.value);
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('countryAutocomplete');
            const box = document.getElementById('countrySuggestions');
            if (!input || !box) return;

            let timer = null;
            let lastItems = [];

            function hideBox() {
                box.style.display = 'none';
                box.innerHTML = '';
            }

            function renderItems(items) {
                lastItems = items;
                if (!items.length) return hideBox();
                box.innerHTML = items.map(function(item) {
                    const name = (item.name || '').replace(/"/g, '&quot;');
                    return '<button type="button" class="country-suggestion-item" data-name="' + name +
                        '">' + name + '</button>';
                }).join('');
                box.style.display = 'block';
            }

            input.addEventListener('input', function() {
                clearTimeout(timer);
                const q = input.value.trim();
                if (q.length < 2) return hideBox();

                timer = setTimeout(async function() {
                    try {
                        const res = await fetch(
                            `{{ route('countries.autocomplete') }}?q=${encodeURIComponent(q)}`
                        );
                        const data = await res.json();
                        renderItems(Array.isArray(data.items) ? data.items : []);
                    } catch (e) {
                        hideBox();
                    }
                }, 250);
            });

            function applyBestMatchIfAny() {
                const typed = (input.value || '').trim().toLowerCase();
                if (!typed || !lastItems.length) return;
                const exact = lastItems.find(i => (i.name || '').toLowerCase() === typed);
                const starts = lastItems.find(i => (i.name || '').toLowerCase().startsWith(typed));
                const best = exact || starts || null;
                if (best && best.name) input.value = best.name;
            }

            box.addEventListener('click', function(e) {
                const btn = e.target.closest('.country-suggestion-item');
                if (!btn) return;
                input.value = btn.getAttribute('data-name') || '';
                hideBox();
            });

            input.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    applyBestMatchIfAny();
                    hideBox();
                }
            });

            input.addEventListener('blur', function() {
                applyBestMatchIfAny();
                setTimeout(hideBox, 100);
            });

            document.addEventListener('click', function(e) {
                if (!box.contains(e.target) && e.target !== input) hideBox();
            });
        });
    </script>

    <style>
        .country-suggestions {
            position: relative;
            width: 100%;
            border: 1px solid #e5e7eb;
            border-top: none;
            background: #fff;
            max-height: 220px;
            overflow-y: auto;
            z-index: 99;
        }

        .country-suggestion-item {
            display: block;
            width: 100%;
            text-align: left;
            border: 0;
            background: #fff;
            padding: 10px 12px;
            font-size: 14px;
            cursor: pointer;
        }

        .country-suggestion-item:hover {
            background: #f7f7f7;
        }
    </style>
@endsection
