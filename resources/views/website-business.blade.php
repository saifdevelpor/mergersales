@extends('home')

@section('website-title')
<title>Mergersales | Business</title>
@endsection

@section('website-content')
@php
use Illuminate\Support\HtmlString;

$tablerIcons = [
'adjustments-horizontal' => '
<path stroke="none" d="M0 0h24v24H0z" fill="none" />
<path d="M4 6l14 0" />
<path d="M4 12l10 0" />
<path d="M4 18l18 0" />
<path d="M18 6l2 0" />
<path d="M14 12l8 0" />
<path d="M18 18l4 0" />
<path d="M8 6l0 .01" />
<path d="M12 12l0 .01" />
<path d="M14 18l0 .01" />',
'briefcase' => '
<path stroke="none" d="M0 0h24v24H0z" fill="none" />
<path d="M9 7l0 -2a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v2" />
<path d="M3 7m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" />
<path d="M8 12l8 0" />
<path d="M12 12l0 3" />',
'building-factory-2' => '
<path stroke="none" d="M0 0h24v24H0z" fill="none" />
<path d="M3 21h18" />
<path d="M5 21v-12l5 4v-4l5 4h4v8" />
<path d="M4 9l0 -4h4l0 4" />
<path d="M9 17l0 .01" />
<path d="M14 17l0 .01" />
<path d="M19 17l0 .01" />',
'building-store' => '
<path stroke="none" d="M0 0h24v24H0z" fill="none" />
<path d="M3 21l18 0" />
<path d="M4 21l0 -10" />
<path d="M20 21l0 -10" />
<path d="M4 7l16 0" />
<path d="M6 7l1 -4l10 0l1 4" />
<path d="M9 21l0 -6h6l0 6" />',
'chart-arrows-vertical' => '
<path stroke="none" d="M0 0h24v24H0z" fill="none" />
<path d="M8 3l-4 4l4 4" />
<path d="M4 7h9" />
<path d="M16 21l4 -4l-4 -4" />
<path d="M20 17h-9" />
<path d="M7 20l10 -16" />',
'chef-hat' => '
<path stroke="none" d="M0 0h24v24H0z" fill="none" />
<path d="M6.5 17h11" />
<path d="M6 21h12" />
<path d="M15 17v-3.5a3.5 3.5 0 1 0 -7 0v3.5" />
<path d="M5 17a3 3 0 0 1 -2 -5.2a3.5 3.5 0 0 1 5.7 -3.3a4 4 0 0 1 6.6 0a3.5 3.5 0 0 1 5.7 3.3a3 3 0 0 1 -2 5.2" />',
'code' => '
<path stroke="none" d="M0 0h24v24H0z" fill="none" />
<path d="M7 8l-4 4l4 4" />
<path d="M17 8l4 4l-4 4" />
<path d="M14 4l-4 16" />',
'device-gamepad-2' => '
<path stroke="none" d="M0 0h24v24H0z" fill="none" />
<path
    d="M12 5c3.866 0 7 2.239 7 5a6.98 6.98 0 0 1 -.633 2.942l-1.72 3.441a2 2 0 0 1 -2.47 .997l-.843 -.281a2 2 0 0 0 -1.334 0l-.843 .281a2 2 0 0 1 -2.47 -.997l-1.72 -3.441a6.98 6.98 0 0 1 -.633 -2.942c0 -2.761 3.134 -5 7 -5z" />
<path d="M8 11h2" />
<path d="M9 10v2" />
<path d="M15 11h.01" />
<path d="M18 10h.01" />',
'device-laptop' => '
<path stroke="none" d="M0 0h24v24H0z" fill="none" />
<path d="M3 19l18 0" />
<path d="M5 6a1 1 0 0 1 1 -1h12a1 1 0 0 1 1 1v9h-14z" />
<path d="M9 15l6 0" />',
'heart-handshake' => '
<path stroke="none" d="M0 0h24v24H0z" fill="none" />
<path
    d="M12 21l-1.45 -1.317c-4.12 -3.743 -6.55 -6.05 -6.55 -8.683a4 4 0 0 1 7.364 -2.147a4 4 0 0 1 6.636 2.147c0 2.633 -2.43 4.94 -6.55 8.683l-.45 .317z" />
<path d="M12 13l2 -2l3 3" />
<path d="M12 16l-3 -3l-2 2" />',
'leaf' => '
<path stroke="none" d="M0 0h24v24H0z" fill="none" />
<path d="M5 21c10 0 14 -4 14 -14c-10 0 -14 4 -14 14" />
<path d="M5 21c6 0 10 -4 10 -10" />',
];

$tablerIcons += [
'map-pin' => '
<path stroke="none" d="M0 0h24v24H0z" fill="none" />
<path d="M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
<path d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z" />',
'megaphone' => '
<path stroke="none" d="M0 0h24v24H0z" fill="none" />
<path d="M3 11v2" />
<path d="M7 9l10 -4v14l-10 -4v-6" />
<path d="M7 9h-2a2 2 0 0 0 -2 2" />
<path d="M7 15h-2a2 2 0 0 1 -2 -2" />
<path d="M11 15l0 4a1 1 0 0 0 1 1h2" />',
'plane' => '
<path stroke="none" d="M0 0h24v24H0z" fill="none" />
<path d="M16 10h-5l-4 -4l-2 1l2 5h-4l-2 1l2 1h4l-2 5l2 1l4 -4h5a2 2 0 0 0 0 -4" />',
'plant-2' => '
<path stroke="none" d="M0 0h24v24H0z" fill="none" />
<path d="M7 21h10" />
<path d="M10 21c0 -6 1 -9 6 -11" />
<path d="M9 9c0 -2.21 .895 -4.21 2.343 -5.657a8 8 0 0 1 1.414 11.314" />
<path d="M17 13a6 6 0 0 0 -6 -6" />
<path d="M7 12a5 5 0 0 1 5 5" />',
'rocket' => '
<path stroke="none" d="M0 0h24v24H0z" fill="none" />
<path d="M4 13a8 8 0 0 1 7 -7a6 6 0 0 1 7 7a8 8 0 0 1 -7 7a6 6 0 0 1 -7 -7" />
<path d="M15 9l0 .01" />
<path d="M8 16l-3 3" />
<path d="M5 21l3 -3" />
<path d="M9 18l-4 0" />
<path d="M18 9l0 -4" />
<path d="M15 6l3 0" />',
'school' => '
<path stroke="none" d="M0 0h24v24H0z" fill="none" />
<path d="M3 10l9 -6l9 6" />
<path d="M4 10v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-8" />
<path d="M8 14h8" />
<path d="M8 18h8" />',
'stethoscope' => '
<path stroke="none" d="M0 0h24v24H0z" fill="none" />
<path d="M6 3l0 6a4 4 0 0 0 8 0v-6" />
<path d="M6 6h8" />
<path d="M19 16a3 3 0 1 0 -3 -3" />
<path d="M16 13v-2a2 2 0 0 0 -2 -2h-1" />
<path d="M8 15a6 6 0 0 0 12 0" />',
];

$tablerIcons += [
'tool' => '
<path stroke="none" d="M0 0h24v24H0z" fill="none" />
<path
    d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.3 -3.3a6 6 0 0 1 -7.94 7.94l-6.76 6.76a2 2 0 1 1 -2.83 -2.83l6.76 -6.76a6 6 0 0 1 7.94 -7.94l-3.3 3.3z" />
',
'trending-up' => '
<path stroke="none" d="M0 0h24v24H0z" fill="none" />
<path d="M3 17l6 -6l4 4l8 -8" />
<path d="M14 7l7 0l0 7" />',
'users' => '
<path stroke="none" d="M0 0h24v24H0z" fill="none" />
<path d="M9 7a4 4 0 1 0 0 8a4 4 0 0 0 0 -8" />
<path d="M17 11a4 4 0 1 0 0 .01" />
<path d="M3 21v-2a4 4 0 0 1 4 -4h4" />
<path d="M17 17h1a4 4 0 0 1 4 4v0" />',
];

$renderTablerIcon = static function (string $name, string $style = '', string $class = '') use ($tablerIcons):
HtmlString {
$paths = $tablerIcons[$name] ?? $tablerIcons['briefcase'];
$styleAttr = $style !== '' ? ' style="' . e($style) . '"' : '';
$classAttr = trim('icon icon-tabler ' . $class);
return new HtmlString(
'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
    stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="' . e($classAttr) . '"' . $styleAttr . '>'
    . $paths . '</svg>'
);
};
@endphp

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
                            {!! $renderTablerIcon('adjustments-horizontal',
                            'width:18px;height:18px;vertical-align:middle;margin-right:8px;') !!}<span>Search
                                Filters</span>
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
                                    <input type="text" class="price-range-double" data-min="10000" data-max="100000000"
                                        data-step="10000" name="revenue_range_ui"
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
                                    {!! $renderTablerIcon('adjustments-horizontal',
                                    'width:16px;height:16px;vertical-align:text-bottom;margin-right:6px;') !!} Reset
                                    Filters
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
                <div class="listing-item-container box-list_ic fl-wrap" style="display:flex; flex-wrap:wrap; gap:20px;">

                    @forelse ($listings as $listing)
                    @php
                    $singleUrl = route('seo.business.show', $listing->slug);
                    $industryName = $listing->industry->name ?? 'General Business';
                    $subIndustryName = $listing->subIndustry->name ?? null;
                    $dealType = $listing->deal_type ?? 'Opportunity';
                    $iconMap = [
                    'technology' => 'device-laptop',
                    'software' => 'code',
                    'health' => 'heart-handshake',
                    'medical' => 'stethoscope',
                    'education' => 'school',
                    'retail' => 'building-store',
                    'ecommerce' => 'building-store',
                    'restaurant' => 'chef-hat',
                    'food' => 'chef-hat',
                    'manufacturing' => 'building-factory-2',
                    'industrial' => 'building-factory-2',
                    'construction' => 'tool',
                    'real estate' => 'building-store',
                    'property' => 'building-store',
                    'transport' => 'map-pin',
                    'logistics' => 'map-pin',
                    'finance' => 'trending-up',
                    'investment' => 'chart-arrows-vertical',
                    'energy' => 'rocket',
                    'agriculture' => 'leaf',
                    'fashion' => 'device-gamepad-2',
                    'beauty' => 'plant-2',
                    'hospitality' => 'building-store',
                    'travel' => 'plane',
                    'media' => 'device-laptop',
                    'marketing' => 'megaphone',
                    'automotive' => 'tool',
                    'cleaning' => 'tool',
                    ];
                    $paletteMap = [
                    'technology' => ['#122033', '#ccaa57'],
                    'software' => ['#17263d', '#d7b96e'],
                    'health' => ['#143348', '#c9a24b'],
                    'medical' => ['#183a4d', '#d4b068'],
                    'education' => ['#1f2d45', '#ccaa57'],
                    'retail' => ['#2f2a23', '#cda559'],
                    'ecommerce' => ['#3a3025', '#d3ae66'],
                    'restaurant' => ['#3c2c23', '#c89b4c'],
                    'food' => ['#48311e', '#d9b563'],
                    'manufacturing' => ['#243245', '#c6a15a'],
                    'industrial' => ['#293548', '#b99249'],
                    'construction' => ['#322b22', '#caa24f'],
                    'real estate' => ['#1e2d44', '#c9a45a'],
                    'property' => ['#22324a', '#d7b76b'],
                    'transport' => ['#1b2940', '#be9a53'],
                    'logistics' => ['#203146', '#cfab5f'],
                    'finance' => ['#15283d', '#c5a04f'],
                    'investment' => ['#16253a', '#d0ac62'],
                    'energy' => ['#2d2b24', '#cfa54f'],
                    'agriculture' => ['#25392f', '#c4a35b'],
                    'fashion' => ['#33273a', '#d6b16f'],
                    'beauty' => ['#3b2d39', '#d9b978'],
                    'hospitality' => ['#352b24', '#ca9f56'],
                    'travel' => ['#1d3248', '#ceb16d'],
                    'media' => ['#282842', '#cda75d'],
                    'marketing' => ['#2d2942', '#d1ae68'],
                    'automotive' => ['#2a313f', '#bc9a58'],
                    'cleaning' => ['#223844', '#cfb06e'],
                    ];
                    $lookupText = strtolower(trim(($subIndustryName ? $subIndustryName . ' ' : '') . $industryName . ' '
                    . $dealType));
                    $categoryIcon = 'briefcase';
                    $categoryColors = ['#162235', '#ccaa57'];
                    foreach ($iconMap as $keyword => $icon) {
                    if (str_contains($lookupText, $keyword)) {
                    $categoryIcon = $icon;
                    $categoryColors = $paletteMap[$keyword] ?? $categoryColors;
                    break;
                    }
                    }
                    $categoryLabel = $subIndustryName ?: $industryName;
                    @endphp

                    <div class="listing-item" data-responsive-card
                        style="flex:1 1 calc(50% - 20px); max-width:calc(50% - 20px); display:flex; flex-direction:column;">
                        <article class="geodir-category-listing fl-wrap"
                            style="height:100%;display:flex;flex-direction:column;background:#fffdf8;border-radius:18px;overflow:hidden;box-shadow:0 16px 34px rgba(18,34,53,0.08);border:1px solid rgba(204,170,87,0.22);">

                            <div class="geodir-category-img fl-wrap"
                                style="height:230px;overflow:hidden;position:relative;flex-shrink:0;">

                                <a href="{{ $singleUrl }}" style="display:block;width:100%;height:100%;">
                                    <div
                                        style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;padding:24px;background:linear-gradient(180deg, #fffdf9 0%, #f8f4ea 100%);position:relative;">
                                        <div
                                            style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;pointer-events:none;color:{{ $categoryColors[0] }};opacity:.12;">
                                            {!! $renderTablerIcon($categoryIcon, 'width:150px;height:150px;stroke-width:1.45;filter:drop-shadow(0 8px 14px rgba(18,34,53,0.08));') !!}
                                        </div>
                                        <div
                                            style="position:absolute;right:-28px;bottom:-34px;width:180px;height:180px;border-radius:50%;background:radial-gradient(circle, rgba(204,170,87,.22) 0%, rgba(204,170,87,.06) 48%, rgba(204,170,87,0) 72%);pointer-events:none;">
                                        </div>
                                        <div
                                            style="position:relative;z-index:1;width:124px;height:124px;border-radius:32px;background:#ffffff;border:1px solid rgba(22,34,53,0.10);display:flex;align-items:center;justify-content:center;color:{{ $categoryColors[0] }};box-shadow:0 18px 34px rgba(18,34,53,0.10);">
                                            {!! $renderTablerIcon($categoryIcon, 'width:68px;height:68px;stroke-width:1.7;') !!}
                                        </div>
                                    </div>
                                </a>

                                <!-- BADGES CONTAINER -->
                                <div style="
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
                                    <span style="
            display:inline-flex;
            align-items:center;
            gap:6px;
            padding:6px 12px;
            font-size:12px;
            font-weight:600;
            border-radius:4px;
            background:#ccaa57;
            color:#fff;
        ">
                                        {!! $renderTablerIcon('briefcase', 'width:14px;height:14px;') !!}
                                        {{ $listing->deal_type ?? 'Latest' }}
                                    </span>

                                    <!-- RIGHT SIDE -->
                                    <span style="
            display:inline-flex;
            align-items:center;
            gap:6px;
            padding:6px 12px;
            font-size:12px;
            font-weight:600;
            border-radius:4px;
            background:rgba(18,34,53,0.88);
            color:#fff7e7;
        ">
                                        {!! $renderTablerIcon('map-pin', 'width:14px;height:14px;') !!}
                                        {{ $listing->country ?? 'N/A' }}
                                    </span>

                                </div>

                            </div>


                            <div class="geodir-category-content fl-wrap"
                                style="flex:1;display:flex;flex-direction:column;padding:20px 20px 22px;">
                                <div style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:14px;">
                                    <span
                                        style="display:inline-flex;align-items:center;gap:6px;padding:7px 12px;border-radius:999px;background:#f7f8fa;border:1px solid rgba(22,34,53,0.08);color:#162235;font-size:11px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;">
                                        {!! $renderTablerIcon($categoryIcon, 'width:14px;height:14px;') !!}
                                        {{ $categoryLabel }}
                                    </span>
                                    <span
                                        style="display:inline-flex;align-items:center;gap:6px;padding:7px 12px;border-radius:999px;background:#fff8ea;border:1px solid rgba(204,170,87,0.28);color:#8a6a2f;font-size:11px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;">
                                        {!! $renderTablerIcon('map-pin', 'width:14px;height:14px;') !!}
                                        {{ $listing->country ?? 'Global Market' }}
                                    </span>
                                </div>

                                <div style="margin-bottom:10px;color:#8a6a2f;font-size:12px;font-weight:800;letter-spacing:.08em;text-transform:uppercase;">
                                    {{ $industryName }}
                                </div>

                                <h3 style="min-height:48px; font-size:18px; line-height:1.4;color:#162235;">
                                    <a href="{{ $singleUrl }}" style="color:#162235;">{{ $listing->business_name }}</a>
                                </h3>

                                @if ($subIndustryName)
                                <div style="margin:-2px 0 12px;color:#5f6674;font-size:14px;line-height:1.6;">
                                    {{ $subIndustryName }}
                                </div>
                                @endif

                                {{-- <div class="geodir-category-content_price" style="font-weight:600;margin:8px 0;">
                                            {{ $listing->ebitda_range ?? 'Confidential' }}
                            </div> --}}

                            <p
                                style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;margin-bottom:14px;color:#5f6674;">
                                {{ $listing->description ?? 'No Description Available' }}
                            </p>

                            <div
                                style="display:flex;align-items:center;justify-content:space-between;gap:12px;margin-top:auto;padding-top:10px;border-top:1px solid rgba(22,34,53,0.08);">
                                <span
                                    style="display:inline-flex;align-items:center;gap:8px;color:#8a6a2f;font-size:12px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;">
                                    {!! $renderTablerIcon($categoryIcon, 'width:16px;height:16px;') !!}
                                    {{ $categoryLabel }}
                                </span>
                                <a href="{{ $singleUrl }}"
                                    style="display:inline-flex;align-items:center;gap:8px;padding:10px 16px;border-radius:999px;background:#ccaa57;color:#fff;font-weight:700;">
                                    View Business
                                    {!! $renderTablerIcon('trending-up', 'width:14px;height:14px;') !!}
                                </a>
                            </div>

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
                    <img src="{{ asset($listing->user->profile_photo ?? 'images/default-user.png') }}" alt=""
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
        })
    );

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
