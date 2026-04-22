@extends('home')

@section('website-title')
<title>Mergersales | Home</title>
@endsection

@section('website-content')
@php
use Illuminate\Support\HtmlString;

$homeTablerIcons = [
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
'tool' => '
<path stroke="none" d="M0 0h24v24H0z" fill="none" />
<path
    d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.3 -3.3a6 6 0 0 1 -7.94 7.94l-6.76 6.76a2 2 0 1 1 -2.83 -2.83l6.76 -6.76a6 6 0 0 1 7.94 -7.94l-3.3 3.3z" />
',
'trending-up' => '
<path stroke="none" d="M0 0h24v24H0z" fill="none" />
<path d="M3 17l6 -6l4 4l8 -8" />
<path d="M14 7l7 0l0 7" />',
];

$renderHomeTablerIcon = static function (string $name, string $style = '', string $class = '') use ($homeTablerIcons):
HtmlString {
$paths = $homeTablerIcons[$name] ?? $homeTablerIcons['briefcase'];
$styleAttr = $style !== '' ? ' style="' . e($style) . '"' : '';
$classAttr = trim('icon icon-tabler ' . $class);

return new HtmlString(
'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
    stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="' . e($classAttr) . '"' . $styleAttr . '>'
    . $paths . '</svg>'
);
};
@endphp

<section class="hero-section hidden-section">
    <div class="media-container bg-parallax-wrap-gradien">
        <div class="video-container">
            <video autoplay loop muted class="bgvid">
                <source src="{{ asset('video/1.mp4') }}" type="video/mp4">
            </video>
        </div>
    </div>

    <div class="container">
        <div class="hero-title hero-title_center">
            <h4>World's Largest Free M&A Marketplace</h4>
            <h2>Buy or Sell a Business<br>Confidentially and Free</h2>
        </div>

        <div class="main-search-input-wrap shadow_msiw msiw-center">
            <form class="main-search-input fl-wrap" method="GET" action="{{ route('webite-business') }}">

                <div class="main-search-input-item">
                    <input type="text" name="keyword" id="keywordInput" placeholder="Search by keyword..."
                        value="{{ request('keyword') }}" />
                </div>

                <div class="main-search-input-item">
                    <select name="deal_type" id="dealTypeSelect" data-placeholder="Deal Type"
                        class="chosen-select no-search-select">
                        <option value="">All Deal Types</option>
                        <option value="Sell Business" {{ request('deal_type') == 'Sell Business' ? 'selected' : '' }}>
                            Sell
                            Business</option>
                        <option value="Raise Capital" {{ request('deal_type') == 'Raise Capital' ? 'selected' : '' }}>
                            Raise Capital</option>
                        </option>
                        <option value="Find Partner" {{ request('deal_type') == 'Find Partner' ? 'selected' : '' }}>Find
                            Partner</option>
                    </select>
                </div>

                <div class="main-search-input-item">
                    <select name="industry" id="industrySelect" data-placeholder="Industry" class="chosen-select">
                        <option value="">All Industries</option>

                        @foreach ($industries as $ind)
                        <option value="{{ $ind->id }}"
                            {{ (string) request('industry') === (string) $ind->id ? 'selected' : '' }}>
                            {{ $ind->name }}
                        </option>
                        @endforeach

                    </select>
                </div>



                <!-- ✅ Normal submit -->
                <button type="submit" class="main-search-button color-bg">
                    Search <i class="far fa-search"></i>
                </button>

            </form>
        </div>


        <div class="hero-notifer hn_center fl-wrap">
            All listings are 100% anonymous | <a href="{{ route('webite-business') }}">Advanced Search</a>
        </div>
    </div>
</section>

<section class="gray-bg small-padding">
    <div class="container">

        <div class="row">
            <div class="col-md-4">
                <div class="section-title fl-wrap">
                    <h4>Browse Confidential Listings</h4>
                    <h2>Latest Businesses</h2>
                </div>
            </div>

            <div class="col-md-8">
                <div class="listing-filters gallery-filters" id="dealFilters">
                    <a href="#" class="gallery-filter gallery-filter-active" data-filter="*"><span>All Deal
                            Types</span></a>
                    <a href="#" class="gallery-filter" data-filter=".for_sale"><span>Sell Business</span></a>
                    <a href="#" class="gallery-filter" data-filter=".capital_raise"><span>Raise Capital</span></a>
                    <a href="#" class="gallery-filter" data-filter=".find_partner"><span>Find Partner</span></a>
                </div>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="grid-item-holder gallery-items gisp fl-wrap" id="listingContainer">
            <div id="noDealTypeResultMessage" class="col-12 text-center p-5" style="display:none;">
                <h4>No Business available in this deal type</h4>
            </div>
            @forelse ($listings as $listing)
            @php
            // deal class normalize
            $dealClass = strtolower(str_replace(' ', '_', $listing->deal_type ?? 'for_sale'));

            $map = [
            'sell_business' => 'for_sale',
            'raise_capital' => 'capital_raise',
            'find_partner' => 'find_partner',
            'for_sale' => 'for_sale',
            'capital_raise' => 'capital_raise',
            'partnership' => 'find_buyer',
            ];
            $dealClass = $map[$dealClass] ?? $dealClass;

            // data attrs
            $dealText = $listing->deal_type ?? 'For Sale';
            $industryText = $listing->industry->name ?? 'Technology';
            $subIndustryText = $listing->subIndustry->name ?? 'Confidential Segment';
            $countryText = $listing->country ?? 'Global';
            $titleLower = strtolower($listing->title ?? '');
            $descLower = strtolower($listing->description ?? '');
            $singleUrl = route('seo.business.show', $listing->slug);
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
            'investment' => 'trending-up',
            'energy' => 'rocket',
            'agriculture' => 'leaf',
            'fashion' => 'briefcase',
            'beauty' => 'plant-2',
            'hospitality' => 'building-store',
            'travel' => 'plane',
            'media' => 'megaphone',
            'marketing' => 'megaphone',
            'automotive' => 'tool',
            'cleaning' => 'tool',
            ];
            $lookupText = strtolower(trim(($subIndustryText ? $subIndustryText . ' ' : '') . $industryText . ' ' .
            $dealText));
            $categoryIcon = 'briefcase';
            foreach ($iconMap as $keyword => $icon) {
            if (str_contains($lookupText, $keyword)) {
            $categoryIcon = $icon;
            break;
            }
            }
            @endphp

            <div class="gallery-item {{ $dealClass }}" data-deal="{{ $dealText }}" data-industry="{{ $industryText }}"
                data-title="{{ $titleLower }}" data-desc="{{ $descLower }}">

                <div class="listing-item" style="height:100%; display:flex; flex-direction:column;">
                    <article class="geodir-category-listing fl-wrap"
                        style="height:100%;display:flex;flex-direction:column;background:#ffffff;border-radius:22px;overflow:hidden;border:1px solid rgba(22,34,53,0.08);box-shadow:0 18px 36px rgba(18,34,53,0.08);">

                        <div class="geodir-category-img fl-wrap"
                            style="height:210px; overflow:hidden; position:relative; border-bottom:1px solid rgba(22,34,53,0.06);">
                            <a href="{{ $singleUrl }}" class="geodir-category-img_item"
                                style="display:block; width:100%; height:100%;">
                                <div
                                    style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;padding:24px;background:linear-gradient(180deg, #fffdf9 0%, #f7f1e2 100%);position:relative;">
                                    <div
                                        style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;pointer-events:none;color:#162235;opacity:.08;">
                                        {!! $renderHomeTablerIcon($categoryIcon,
                                        'width:140px;height:140px;stroke-width:1.45;') !!}
                                    </div>
                                    <div
                                        style="position:absolute;right:-24px;bottom:-28px;width:170px;height:170px;border-radius:50%;background:radial-gradient(circle, rgba(204,170,87,.24) 0%, rgba(204,170,87,.08) 48%, rgba(204,170,87,0) 72%);pointer-events:none;">
                                    </div>
                                    <div
                                        style="position:relative;z-index:1;width:118px;height:118px;border-radius:30px;background:#ffffff;border:1px solid rgba(22,34,53,0.10);display:flex;align-items:center;justify-content:center;color:#162235;box-shadow:0 18px 34px rgba(18,34,53,0.10);">
                                        {!! $renderHomeTablerIcon($categoryIcon,
                                        'width:62px;height:62px;stroke-width:1.7;') !!}
                                    </div>
                                </div>
                            </a>

                            <div
                                style="position:absolute;top:14px;left:14px;right:14px;display:flex;justify-content:space-between;align-items:center;gap:10px;z-index:5;">
                                <span
                                    style="display:inline-flex;align-items:center;gap:6px;padding:7px 12px;border-radius:999px;background:linear-gradient(135deg, #ccaa57 0%, #b88e2b 100%);color:#ffffff;font-size:11px;font-weight:700;letter-spacing:.05em;text-transform:uppercase;box-shadow:0 10px 18px rgba(204,170,87,0.28);border:1px solid rgba(184,142,43,0.30);">
                                    {!! $renderHomeTablerIcon('briefcase', 'width:14px;height:14px;') !!}
                                    {{ $dealText }}
                                </span>
                                <span
                                    style="display:inline-flex;align-items:center;gap:6px;padding:7px 12px;border-radius:999px;background:rgba(255,255,255,0.96);color:#8b6a2d;font-size:11px;font-weight:700;letter-spacing:.05em;text-transform:uppercase;border:1px solid rgba(204,170,87,0.24);box-shadow:0 10px 18px rgba(22,34,53,0.08);">
                                    <i class="fas fa-map-marker-alt"></i>
                                    {{ $countryText }}
                                </span>
                            </div>

                        </div>

                        <div class="geodir-category-content fl-wrap"
                            style="flex:1; display:flex; flex-direction:column; padding:20px 20px 22px;">
                            <div style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:12px;">
                                <span
                                    style="display:inline-flex;align-items:center;gap:6px;padding:6px 10px;border-radius:999px;background:#f7f8fa;color:#162235;font-size:11px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;">
                                    {!! $renderHomeTablerIcon($categoryIcon, 'width:14px;height:14px;') !!}
                                    {{ $industryText }}
                                </span>
                            </div>

                            <h3 class="title-sin_item"
                                style="line-height:1.35;min-height:50px;margin-bottom:8px;font-size:20px;color:#162235;">
                                <a href="{{ $singleUrl }}">{{ $listing->business_name }}</a>
                            </h3>

                            @if ($subIndustryText)
                            <p
                                style="margin:0 0 10px;color:#8b6a2d;font-size:13px;font-weight:700;letter-spacing:.02em;">
                                {{ $subIndustryText }}
                            </p>
                            @endif

                            <p
                                style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;margin-bottom:10px;color:#555;">
                                {{ \Illuminate\Support\Str::limit($listing->description ?? 'No Description Available', 90) }}
                            </p>

                            <div
                                style="margin-top:auto;padding-top:8px;display:flex;align-items:center;justify-content:space-between;gap:12px;border-top:1px solid rgba(22,34,53,0.06);">
                                <span
                                    style="font-size:12px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.06em;">
                                    Confidential Listing
                                </span>
                                <a href="{{ $singleUrl }}"
                                    style="display:inline-flex;align-items:center;gap:8px;padding:10px 16px;border-radius:999px;background:#ccaa57;color:#ffffff;font-weight:700;line-height:1;">
                                    View Business
                                    {!! $renderHomeTablerIcon('trending-up', 'width:14px;height:14px;') !!}
                                </a>
                            </div>

                            {{-- <div class="geodir-category-content-details">
                            <ul>
                                <li><i class="fal fa-dollar-sign"></i> <span>Revenue:
                                        {{ $listing->revenue_range ?? 'N/A' }}</span></li>
                            <li><i class="fal fa-chart-bar"></i> <span>EBITDA:
                                    {{ $listing->ebitda_range ?? 'N/A' }}</span></li>
                            <li><i class="fal fa-users"></i> <span>Team:
                                    {{ $listing->employee_range ?? 'N/A' }}</span></li>
                            </ul>
                        </div> --}}

                        {{-- <div class="geodir-category-footer fl-wrap" style="margin-top:auto;">
                            <a href="{{ route('business.single', e_id($listing->id)) }}" class="gcf-company">
                        <img src="{{ asset($listing->user->profile_photo ?? 'images/default-user.png') }}" alt=""
                            style="width:36px; height:36px; object-fit:cover; border-radius:50%; display:block;">

                        <span>{{ $listing->user->name ?? 'Anonymous Seller' }}</span>
                        </a>

                        <div class="listing-rating card-popup-rainingvis tolt" data-microtip-position="top"
                            data-tooltip="{{ $listing->deal_type ?? 'High Potential' }}"
                            data-starrating2="{{ $listing->rating ?? 5 }}">
                        </div>
                </div> --}}

            </div>

            </article>
        </div>

    </div>

    @empty
    <div class="col-12 text-center p-5">
        <h4>No Latest Business Available</h4>
    </div>
    @endforelse
    </div>

    <a href="{{ route('webite-business') }}" class="btn float-btn small-btn color-bg">View All Businesses</a>
    </div>

    <!-- ✅ ONE JS for BOTH sections -->
    <script>
    document.addEventListener("DOMContentLoaded", function() {

        const container = document.getElementById("listingContainer");
        if (!container) return;

        // ✅ save original order
        const items = Array.from(container.querySelectorAll(".gallery-item"));
        items.forEach((item, idx) => item.dataset.originalIndex = idx);

        const dealLinks = Array.from(document.querySelectorAll("#dealFilters .gallery-filter"));

        // hero filters (agar hero section same page pe hai)
        const keywordInput = document.getElementById("keywordInput");
        const dealSelect = document.getElementById("dealTypeSelect");
        const indSelect = document.getElementById("industrySelect");
        const searchBtn = document.getElementById("searchBtn");

        function normalize(v) {
            return (v || "").toString().trim().toLowerCase();
        }

        function restoreOriginalOrder() {
            const all = Array.from(container.querySelectorAll(".gallery-item"));
            all.sort((a, b) => Number(a.dataset.originalIndex) - Number(b.dataset.originalIndex));
            all.forEach(el => container.appendChild(el));
        }

        // ✅ deal select value => class
        function mapDealValueToClass(val) {
            const v = normalize(val);
            if (!v || v === "all" || v === "all deal types") return "*";

            // accept either value "for_sale" OR label "Sell Business"
            if (v === "sell business" || v === "for_sale" || v === "for sale") return "for_sale";
            if (v === "raise capital" || v === "capital_raise") return "capital_raise";
            if (v === "find partner" || v === "find_partner") return "find_partner";

            return v.replace(/\s+/g, "_");
        }

        function setActiveLink(filter) {
            dealLinks.forEach(a => a.classList.remove("gallery-filter-active"));
            const active = dealLinks.find(a => a.getAttribute("data-filter") === filter);
            if (active) active.classList.add("gallery-filter-active");
        }

        // ✅ core filter
        function applyFilters(fromLinkFilter = null) {
            const keyword = keywordInput ? normalize(keywordInput.value) : "";
            const industryVal = indSelect ? normalize(indSelect.value) : "all industries";
            const emptyMsg = document.getElementById("noDealTypeResultMessage");

            // deal class: link se ya dropdown se
            let dealClass = "*";
            if (fromLinkFilter) {
                dealClass = (fromLinkFilter === "*") ? "*" : fromLinkFilter.replace(".", "");
            } else {
                dealClass = dealSelect ? mapDealValueToClass(dealSelect.value) : "*";
            }

            const all = Array.from(container.querySelectorAll(".gallery-item"));

            // ✅ reset order first
            restoreOriginalOrder();

            const matched = [];
            const hidden = [];

            all.forEach(item => {
                const itemIndustry = normalize(item.getAttribute("data-industry"));
                const title = normalize(item.getAttribute("data-title"));
                const desc = normalize(item.getAttribute("data-desc"));

                const keywordMatch = !keyword || title.includes(keyword) || desc.includes(keyword);
                const dealMatch = (dealClass === "*") || item.classList.contains(dealClass);
                const industryMatch = (!industryVal || industryVal === "all industries") || (
                    industryVal === itemIndustry);

                if (keywordMatch && dealMatch && industryMatch) matched.push(item);
                else hidden.push(item);
            });

            // hide + show
            hidden.forEach(el => el.style.display = "none");
            matched.forEach(el => el.style.display = "");

            // ✅ empty state for deal-type filter
            if (emptyMsg) {
                const onlyDealFilterActive = (dealClass !== "*") && !keyword && (!industryVal || industryVal ===
                    "all industries");
                emptyMsg.style.display = (onlyDealFilterActive && matched.length === 0) ? "" : "none";
            }

            // ✅ move matched on top (clean + stable)
            if (matched.length) {
                // keep original relative order
                matched.sort((a, b) => Number(a.dataset.originalIndex) - Number(b.dataset.originalIndex));

                const frag = document.createDocumentFragment();
                matched.forEach(el => frag.appendChild(el));
                hidden.sort((a, b) => Number(a.dataset.originalIndex) - Number(b.dataset.originalIndex));
                hidden.forEach(el => frag.appendChild(el));
                container.appendChild(frag);
            }

            // if no filters => show all + original order
            const noDealFilter = (dealClass === "*");
            const noKeyword = !keyword;
            const noIndustry = (!industryVal || industryVal === "all industries");

            if (noDealFilter && noKeyword && noIndustry) {
                restoreOriginalOrder();
                all.forEach(el => el.style.display = "");
                if (emptyMsg) emptyMsg.style.display = "none";
            }
        }

        // ✅ Deal filter links
        dealLinks.forEach(link => {
            link.addEventListener("click", function(e) {
                e.preventDefault();
                const filter = this.getAttribute("data-filter"); // "*" or ".for_sale"
                setActiveLink(filter);
                applyFilters(filter);
            });
        });

        // ✅ Hero button
        if (searchBtn) {
            searchBtn.addEventListener("click", function(e) {
                e.preventDefault();
                setActiveLink("*");
                applyFilters(null);
            });
        }

        // ✅ Enter key
        if (keywordInput) {
            keywordInput.addEventListener("keydown", function(e) {
                if (e.key === "Enter") {
                    e.preventDefault();
                    applyFilters(null);
                }
            });
        }

        // ✅ Dropdown change
        if (dealSelect) dealSelect.addEventListener("change", () => applyFilters(null));
        if (indSelect) indSelect.addEventListener("change", () => applyFilters(null));

    });
    </script>



</section>

<section>
    <div class="container">
        <div class="about-wrap">
            <div class="row">
                <div class="col-md-5">
                    <div class="about-title ab-hero fl-wrap">
                        <h2>Why Choose Mergersales</h2>
                        <h4>Discover why we're the trusted platform for confidential M&A transactions.</h4>
                    </div>
                    <div class="services-opions fl-wrap">
                        <ul>
                            <li>
                                <i class="fal fa-user-secret"></i>
                                <h4>100% Confidential Listings</h4>
                                <p>Businesses are listed anonymously by default. No company names, no exposure, only
                                    essential details to maintain complete privacy.</p>
                            </li>
                            <li>
                                <i class="fal fa-hand-holding-usd"></i>
                                <h4>Completely Free Platform</h4>
                                <p>No listing fees, no commissions, no subscriptions. Join, browse, and connect with
                                    buyers/sellers without any cost barriers.</p>
                            </li>
                            <li>
                                <i class="fal fa-globe-americas"></i>
                                <h4>Global Marketplace</h4>
                                <p>Access businesses from around the world across all industries. Connect with
                                    international buyers and sellers seamlessly.</p>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-1"></div>
                <div class="col-md-6">
                    <div class="about-img fl-wrap">
                        <img src="{{ asset('images/7.jpg') }}" class="respimg" alt="">
                        <div class="about-img-hotifer color-bg">
                            <p>Mergersales solved our #1 concern: maintaining confidentiality while finding the right
                                buyer for our business.</p>
                            <h4>Anonymous Seller</h4>
                            <h5>Technology Business Owner</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="hidden-section no-padding-section">
    <div class="half-carousel-wrap">
        <div class="half-carousel-title color-bg">
            <div class="half-carousel-title-item fl-wrap">
                <h2>Explore Top Industries</h2>
                <h5>Browse businesses across various sectors. From technology to manufacturing, find opportunities that
                    match your investment criteria.</h5>
            </div>
            <div class="pwh_bg"></div>
        </div>
        <div class="half-carousel-conatiner">
            <div class="half-carousel fl-wrap full-height">
                @php
                $industryImages = [
                'images/industry/softwear.jpg',
                'images/industry/menufactring.jpg',
                'images/industry/health.jpg',
                'images/industry/ecomerace.jpg',
                'images/industry/finance.jpg',
                'images/industry/transport.jpg',
                'images/industry/food.jpg',
                'images/industry/energy.jpg',
                'images/industry/media.jpg',
                'images/industry/business.jpg',
                ];
                $industryDescriptions = [
                'Financial Services & Investment' =>
                'M&A-ready firms in advisory, insurance, and investment services. Discover opportunities with stable
                client portfolios, recurring revenue, and scalable financial operations.',
                'Technology & Software' =>
                'Explore software, SaaS, and IT-enabled businesses with strong growth potential. Ideal for buyers
                seeking recurring revenue, product-led scale, and digital market reach.',
                'Industrial, Manufacturing & Engineering' =>
                'Browse manufacturing and engineering businesses with proven operations. Find opportunities with
                established production capacity, B2B contracts, and supply-chain depth.',
                'Transportation, Logistics & Supply Chain' =>
                'Access logistics and transport businesses supporting regional and global commerce. Evaluate assets with
                route networks, fulfillment capabilities, and operational efficiency.',
                'Healthcare, Pharma & Life Sciences' =>
                'Discover healthcare and life sciences opportunities across services and specialized care. Suitable for
                strategic buyers focused on resilient demand and long-term value.',
                'Consumer, Retail & E-commerce' =>
                'Find retail and e-commerce businesses with active customer bases. Explore brands with omnichannel
                presence, repeat purchase behavior, and expansion potential.',
                'Food, Beverage & Agriculture' =>
                'Review food, beverage, and agriculture ventures across production and distribution. Opportunities
                include strong local demand, supplier ecosystems, and brand-led growth.',
                'Energy, Utilities & Environment' =>
                'Identify businesses in energy, utilities, and sustainability-focused services. Compare opportunities
                tied to infrastructure demand, efficiency solutions, and green transition trends.',
                'Media, Entertainment & Creative' =>
                'Explore media and creative businesses with engaged audiences and monetization channels. Includes
                digital content, production, and brand-driven platforms.',
                'Professional Services' =>
                'Evaluate service firms with repeat clients and dependable cash flows. Common opportunities include
                consulting, compliance, and specialist advisory businesses.',
                'Construction, Property & Facilities' =>
                'Browse construction and facilities businesses with active projects and contracts. Suitable for buyers
                seeking operational scale in infrastructure and property services.',
                'Education & Training' =>
                'Find education and training providers serving academic and professional markets. Opportunities include
                scalable delivery models and recurring enrollment pipelines.',
                'Government, Public Sector & Non-Profit' =>
                'Discover organizations and service providers aligned with public and social impact needs. Explore
                stable demand models backed by institutional partnerships.',
                'Travel, Hospitality & Leisure' =>
                'Review hospitality and leisure businesses with strong customer experience focus. Ideal for investors
                targeting tourism recovery and destination-led growth.',
                'Business Services & Misc' =>
                'Explore diverse business services opportunities across support and specialist niches. A broad category
                with flexible acquisition options for strategic expansion.',
                ];
                @endphp

                @foreach ($industries as $idx => $industry)
                @php
                $img = $industryImages[$idx % count($industryImages)];
                $count = (int) ($industryListingCounts[$industry->id] ?? 0);
                $desc =
                $industryDescriptions[$industry->name] ??
                "Explore available businesses in {$industry->name}. Connect with relevant buyers and sellers through
                confidential, sector-focused opportunities.";
                @endphp
                <div class="slick-item">
                    <div class="half-carousel-item fl-wrap">
                        <div class="bg-wrap bg-parallax-wrap-gradien">
                            <div class="bg" data-bg="{{ asset($img) }}"></div>
                        </div>
                        <div class="half-carousel-content">
                            <div class="hc-counter color-bg">{{ $count }} Listings</div>
                            <h3>{{ $industry->name }}</h3>
                            <p>{{ $desc }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
<section>
    <div class="container">
        <div class="section-title st-center fl-wrap">
            <h4>Featured Blogs</h4>
            <h2>Latest Blogs</h2>
        </div>
        <div class="clearfix"></div>

        <div class="listing-carousel-wrapper lc_hero carousel-wrap fl-wrap">
            <div class="listing-carousel carousel">

                @forelse ($blogs as $blog)
                <div class="slick-slide-item">
                    <div class="listing-item" style="height:100%; display:flex; flex-direction:column;">
                        <article class="geodir-category-listing fl-wrap"
                            style="height:100%; display:flex; flex-direction:column;">

                            {{-- IMAGE --}}
                            <div class="geodir-category-img fl-wrap agent_card"
                                style="height:220px; overflow:hidden; position:relative;">

                                @php
                                $img = $blog->image ? asset($blog->image) : asset('images/12.jpg');
                                $singleUrl = route('seo.blog.show', $blog->slug);
                                @endphp

                                <a href="{{ $singleUrl }}" class="geodir-category-img_item"
                                    style="display:block; width:100%; height:100%;">
                                    <img src="{{ $img }}" alt=""
                                        style="width:100%; height:100%; object-fit:cover; display:block;">

                                    <ul class="list-single-opt_header_cat">
                                        <li>
                                            <span class="cat-opt color-bg">
                                                {{ $blog->category->name ?? 'Blog' }} {{-- optional --}}
                                            </span>
                                        </li>
                                    </ul>
                                </a>

                                {{-- icons optional --}}
                                <div class="agent-card-social fl-wrap">
                                    <ul>
                                        <li><a href="{{ $singleUrl }}"><i class="fas fa-arrow-right"></i></a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="listing-rating card-popup-rainingvis">
                                    <span class="re_stars-title">
                                        {{ $blog->created_at?->format('M d, Y') }}
                                    </span>
                                </div>
                            </div>

                            {{-- CONTENT --}}
                            <div class="geodir-category-content fl-wrap"
                                style="flex:1; display:flex; flex-direction:column;">

                                <div class="agent_card-title fl-wrap">
                                    <h4>
                                        <a href="{{ $singleUrl }}">{{ $blog->title }}</a>
                                    </h4>

                                    <h5>
                                        <a href="{{ $singleUrl }}">
                                            By: {{ $blog->user->name ?? 'Admin' }}
                                        </a>
                                    </h5>
                                </div>

                                {{-- Description 3 lines --}}
                                <p style="
                        display:-webkit-box;
                        -webkit-line-clamp:3;
                        -webkit-box-orient:vertical;
                        overflow:hidden;">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($blog->content ?? ($blog->description ?? '')), 140) }}
                                </p>

                                {{-- FOOTER --}}
                                <div class="geodir-category-footer fl-wrap" style="margin-top:auto;">
                                    <a href="{{ $singleUrl }}" class="btn float-btn color-bg small-btn">
                                        Read More
                                    </a>
                                    <a href="{{ asset($blog->image) }}" target="_blank" class="tolt ftr-btn"
                                        data-microtip-position="left" data-tooltip="View Image">
                                        <i class="fal fa-eye"></i>
                                    </a>

                                </div>

                            </div>
                        </article>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center p-5">
                    <h4>No Latest Blogs Available</h4>
                </div>
                @endforelse
            </div>
            <div class="swiper-button-prev lc-wbtn lc-wbtn_prev"><i class="far fa-angle-left"></i></div>
            <div class="swiper-button-next lc-wbtn lc-wbtn_next"><i class="far fa-angle-right"></i></div>

        </div>
    </div>
</section>

<section class="color-bg small-padding">
    <div class="container">
        <div class="main-facts fl-wrap">

            {{-- Businesses Listed --}}
            <div class="inline-facts-wrap">
                <div class="inline-facts">
                    <div class="milestone-counter">
                        <div class="stats animaper">
                            <div class="num" data-content="0" data-num="{{ $businessCount ?? 0 }}">
                                {{ $businessCount ?? 0 }}
                            </div>
                        </div>
                    </div>
                    <h6>Businesses Listed</h6>
                </div>
            </div>

            {{-- Verified Buyers --}}
            <div class="inline-facts-wrap">
                <div class="inline-facts">
                    <div class="milestone-counter">
                        <div class="stats animaper">
                            <div class="num" data-content="0" data-num="{{ $buyerCount ?? 0 }}">
                                {{ $buyerCount ?? 0 }}
                            </div>
                        </div>
                    </div>
                    <h6>Verified Buyers</h6>
                </div>
            </div>

            {{-- Deals Completed --}}
            <div class="inline-facts-wrap">
                <div class="inline-facts">
                    <div class="milestone-counter">
                        <div class="stats animaper">
                            <div class="num" data-content="0" data-num="{{ $dealCount ?? 0 }}">
                                {{ $dealCount ?? 0 }}
                            </div>
                        </div>
                    </div>
                    <h6>Deals Completed</h6>
                </div>
            </div>

            {{-- Countries Covered --}}
            <div class="inline-facts-wrap">
                <div class="inline-facts">
                    <div class="milestone-counter">
                        <div class="stats animaper">
                            <div class="num" data-content="0" data-num="{{ $countryCount ?? 0 }}">
                                {{ $countryCount ?? 0 }}
                            </div>
                        </div>
                    </div>
                    <h6>Countries Covered</h6>
                </div>
            </div>
        </div>
    </div>

    <div class="svg-bg">
        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
            width="100%" height="100%" viewBox="0 0 1600 900" preserveAspectRatio="xMidYMax slice">
            <defs>
                <lineargradient id="bg">
                    <stop offset="0%" style="stop-color:rgba(255, 255, 255, 0.6)"></stop>
                    <stop offset="50%" style="stop-color:rgba(255, 255, 255, 0.1)"></stop>
                    <stop offset="100%" style="stop-color:rgba(255, 255, 255, 0.6)"></stop>
                </lineargradient>
                <path id="wave" stroke="url(#bg)" fill="none"
                    d="M-363.852,502.589c0,0,236.988-41.997,505.475,0
                                                                                                                                                                                                                                                                                                                                                                                                                                                    s371.981,38.998,575.971,0s293.985-39.278,505.474,5.859s493.475,48.368,716.963-4.995v560.106H-363.852V502.589z" />
            </defs>
            <g>
                <use xlink:href="#wave">
                    <animatetransform attributeName="transform" attributeType="XML" type="translate" dur="10s"
                        calcMode="spline" values="270 230; -334 180; 270 230" keyTimes="0; .5; 1"
                        keySplines="0.42, 0, 0.58, 1.0;0.42, 0, 0.58, 1.0" repeatCount="indefinite" />
                </use>
                <use xlink:href="#wave">
                    <animatetransform attributeName="transform" attributeType="XML" type="translate" dur="8s"
                        calcMode="spline" values="-270 230;243 220;-270 230" keyTimes="0; .6; 1"
                        keySplines="0.42, 0, 0.58, 1.0;0.42, 0, 0.58, 1.0" repeatCount="indefinite" />
                </use>
                <use xlink:href="#wave">
                    <animatetransform attributeName="transform" attributeType="XML" type="translate" dur="6s"
                        calcMode="spline" values="0 230;-140 200;0 230" keyTimes="0; .4; 1"
                        keySplines="0.42, 0, 0.58, 1.0;0.42, 0, 0.58, 1.0" repeatCount="indefinite" />
                </use>
                <use xlink:href="#wave">
                    <animatetransform attributeName="transform" attributeType="XML" type="translate" dur="12s"
                        calcMode="spline" values="0 240;140 200;0 230" keyTimes="0; .4; 1"
                        keySplines="0.42, 0, 0.58, 1.0;0.42, 0, 0.58, 1.0" repeatCount="indefinite" />
                </use>
            </g>
        </svg>
    </div>
</section>

<section class="gray-bg ">
    <div class="container">
        <div class="section-title st-center fl-wrap">
            <h4>Success Stories</h4>
            <h2>What Our Users Say</h2>
        </div>
    </div>

    <div class="clearfix"></div>

    <div class="testimonials-slider-wrap">
        <div class="testimonials-slider">

            <!-- CARD 1 -->
            <div class="slick-item">
                <div class="text-carousel-item fl-wrap" style="display:flex; flex-direction:column; min-height:420px;">

                    <div class="text-carousel-item-header fl-wrap">
                        <div class="popup-avatar">
                            <img src="{{ asset('images/16.jpg') }}" alt="">
                        </div>
                        <div class="review-owner fl-wrap">Tech Entrepreneur</div>
                        <div class="listing-rating card-popup-rainingvis" data-starrating2="5"></div>
                    </div>

                    <div class="text-carousel-content fl-wrap" style="display:flex; flex-direction:column; flex:1;">

                        <p>
                            "Mergersales allowed me to confidentially sell my SaaS business without exposing my identity
                            to competitors. Found a serious buyer within 3 weeks, and the platform being completely free
                            was unbelievable!"
                        </p>

                        <a href="{{ route('webite-business') }}" class="testim-link color-bg" style="margin-top:auto;">
                            Software Business Owner
                        </a>
                    </div>
                </div>
            </div>

            <!-- CARD 2 -->
            <div class="slick-item">
                <div class="text-carousel-item fl-wrap" style="display:flex; flex-direction:column; min-height:420px;">

                    <div class="text-carousel-item-header fl-wrap">
                        <div class="popup-avatar">
                            <img src="{{ asset('images/17.jpg') }}" alt="">
                        </div>
                        <div class="review-owner fl-wrap">Private Equity Investor</div>
                        <div class="listing-rating card-popup-rainingvis" data-starrating2="4"></div>
                    </div>

                    <div class="text-carousel-content fl-wrap" style="display:flex; flex-direction:column; flex:1;">

                        <p>
                            "As a PE firm, we're always looking for quality deals. Mergersales gave us access to
                            businesses we wouldn't have found otherwise. The anonymous listings mean sellers are more
                            willing to list early-stage opportunities."
                        </p>

                        <a href="{{ route('webite-business') }}" class="testim-link color-bg" style="margin-top:auto;">
                            Investment Director
                        </a>
                    </div>
                </div>
            </div>

            <!-- CARD 3 -->
            <div class="slick-item">
                <div class="text-carousel-item fl-wrap" style="display:flex; flex-direction:column; min-height:420px;">

                    <div class="text-carousel-item-header fl-wrap">
                        <div class="popup-avatar">
                            <img src="{{ asset('images/18.jpg') }}" alt="">
                        </div>
                        <div class="review-owner fl-wrap">Manufacturing Business Owner</div>
                        <div class="listing-rating card-popup-rainingvis" data-starrating2="4"></div>
                    </div>

                    <div class="text-carousel-content fl-wrap" style="display:flex; flex-direction:column; flex:1;">

                        <p>
                            "After 25 years running my manufacturing business, I wanted to retire without employees or
                            competitors knowing. Mergersales kept everything confidential while connecting me with 8
                            serious buyers. The deal closed in 4 months."
                        </p>

                        <a href="#" class="testim-link color-bg" style="margin-top:auto;">
                            Industrial Business Seller
                        </a>
                    </div>
                </div>
            </div>

            <!-- CARD 4 -->
            <div class="slick-item">
                <div class="text-carousel-item fl-wrap" style="display:flex; flex-direction:column; min-height:420px;">

                    <div class="text-carousel-item-header fl-wrap">
                        <div class="popup-avatar">
                            <img src="{{ asset('images/19.jpg') }}" alt="">
                        </div>
                        <div class="review-owner fl-wrap">First-Time Acquirer</div>
                        <div class="listing-rating card-popup-rainingvis" data-starrating2="5"></div>
                    </div>

                    <div class="text-carousel-content fl-wrap" style="display:flex; flex-direction:column; flex:1;">

                        <p>
                            "Looking to buy my first business was overwhelming. Mergersales made it simple - no fees,
                            straightforward listings, and the confidential approach meant sellers were more transparent.
                            Found and acquired an e-commerce business within 6 months."
                        </p>

                        <a href="{{ route('webite-business') }}" class="testim-link color-bg" style="margin-top:auto;">
                            New Business Owner
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

</section>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- ✅ LOGIN SUCCESS --}}
@if (session('login_success'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    Swal.fire({
        icon: 'success',
        title: 'Login Successful',
        text: 'Welcome back!',
        confirmButtonText: 'OK',
        confirmButtonColor: '#CCAA57',
    });
});
</script>
@endif

{{-- ✅ REGISTER SUCCESS --}}
@if (session('register_success'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    Swal.fire({
        icon: 'success',
        title: 'Account Created Successfully',
        text: 'You can now login and continue.',
        confirmButtonText: 'OK',
        confirmButtonColor: '#CCAA57',
    });
});
</script>
@endif

{{-- ✅ REGISTER / LOGIN ERRORS --}}
@if ($errors->any())
<script>
document.addEventListener('DOMContentLoaded', function() {
    Swal.fire({
        icon: 'error',
        title: 'Something went wrong',
        html: `{!! implode('<br>', $errors->all()) !!}`,
        confirmButtonText: 'OK',
        confirmButtonColor: '#CCAA57',
    });
});
</script>
@endif

{{-- ✅ ALREADY LOGGED IN --}}
@if (session('already_logged_in'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    Swal.fire({
        icon: 'info',
        title: 'Already Logged In',
        text: 'You are already logged in.',
        confirmButtonText: 'OK',
        confirmButtonColor: '#CCAA57',
    });
});
</script>
@endif
@endsection