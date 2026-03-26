@extends('home')

@section('website-title')
    <title>Mergersales | Home</title>
@endsection

@section('website-content')
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
                        $titleLower = strtolower($listing->title ?? '');
                        $descLower = strtolower($listing->description ?? '');
                    @endphp

                    <div class="gallery-item {{ $dealClass }}" data-deal="{{ $dealText }}"
                        data-industry="{{ $industryText }}" data-title="{{ $titleLower }}"
                        data-desc="{{ $descLower }}">

                        <div class="listing-item" style="height:100%; display:flex; flex-direction:column;">
                            <article class="geodir-category-listing fl-wrap"
                                style="height:100%; display:flex; flex-direction:column;">

                                <div class="geodir-category-img fl-wrap"
                                    style="height:220px; overflow:hidden; position:relative;">
                                    <a href="{{ route('business.single', $listing->id) }}" class="geodir-category-img_item"
                                        style="display:block; width:100%; height:100%;">
                                        <img src="{{ $listing->business_img ? asset('storage/' . $listing->business_img) : asset('assets/images/1.jpg') }}"
                                            alt=""
                                            style="width:100%; height:100%; object-fit:cover; display:block;">
                                        <div class="overlay"></div>
                                    </a>

                                    <div class="geodir-category-location">
                                        <a href="#" class="single-map-item tolt"
                                            data-newlatitude="{{ $listing->lat }}" data-newlongitude="{{ $listing->lng }}"
                                            data-microtip-position="top-left" data-tooltip="Business Region">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <span>{{ $listing->country ?? 'N/A' }}</span>
                                        </a>
                                    </div>

                                    <ul class="list-single-opt_header_cat">
                                        <li><a href="#"
                                                class="cat-opt blue-bg">{{ $listing->deal_type ?? 'For Sale' }}</a></li>
                                        <li><a href="#"
                                                class="cat-opt color-bg">{{ $listing->industry->name ?? 'Technology' }}</a>
                                        </li>
                                    </ul>

                                    {{-- <a href="#" class="geodir_save-btn tolt" data-microtip-position="left"
                            data-tooltip="Save Listing">
                            <span><i class="fal fa-heart"></i></span>
                        </a>

                        <a href="#" class="compare-btn tolt" data-microtip-position="left"
                            data-tooltip="Compare">
                            <span><i class="fal fa-random"></i></span>
                        </a> --}}

                                    <div class="geodir-category-listing_media-list">
                                        <span>
                                            <i class="{{ $listing->badge_icon ?? 'fas fa-chart-line' }}"></i>
                                            {{ $listing->subIndustry->name ?? 'High Growth' }}
                                        </span>
                                    </div>
                                </div>

                                <div class="geodir-category-content fl-wrap"
                                    style="flex:1; display:flex; flex-direction:column;">
                                    <h3 class="title-sin_item"><a
                                            href="{{ route('business.single', $listing->id) }}">{{ $listing->business_name }}</a>
                                    </h3>

                                    {{-- <div class="geodir-category-content_price">
                            {{ $listing->ebitda_range ?? '$0' }}
                        </div> --}}

                                    <p
                                        style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;margin-bottom:8px;color:#555;">
                                        {{ $listing->description ?? 'No Description Available' }}
                                    </p>

                                    <a href="{{ route('business.single', $listing->id) }}">Read More</a>

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
                            <a href="{{ route('business.single', $listing->id) }}" class="gcf-company">
                                <img src="{{ asset($listing->user->profile_photo ?? 'images/default-user.png') }}"
                                    alt=""
                                    style="width:36px; height:36px; object-fit:cover; border-radius:50%; display:block;">

                                <span>{{ $listing->user->name ?? 'Anonymous Seller' }}</span>
                            </a>

                            <div class="listing-rating card-popup-rainingvis tolt"
                                data-microtip-position="top"
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

            <a href="{{ url('website-business') }}" class="btn float-btn small-btn color-bg">View All Businesses</a>
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
                        const onlyDealFilterActive = (dealClass !== "*") && !keyword && (!industryVal || industryVal === "all industries");
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
                                'M&A-ready firms in advisory, insurance, and investment services. Discover opportunities with stable client portfolios, recurring revenue, and scalable financial operations.',
                            'Technology & Software' =>
                                'Explore software, SaaS, and IT-enabled businesses with strong growth potential. Ideal for buyers seeking recurring revenue, product-led scale, and digital market reach.',
                            'Industrial, Manufacturing & Engineering' =>
                                'Browse manufacturing and engineering businesses with proven operations. Find opportunities with established production capacity, B2B contracts, and supply-chain depth.',
                            'Transportation, Logistics & Supply Chain' =>
                                'Access logistics and transport businesses supporting regional and global commerce. Evaluate assets with route networks, fulfillment capabilities, and operational efficiency.',
                            'Healthcare, Pharma & Life Sciences' =>
                                'Discover healthcare and life sciences opportunities across services and specialized care. Suitable for strategic buyers focused on resilient demand and long-term value.',
                            'Consumer, Retail & E-commerce' =>
                                'Find retail and e-commerce businesses with active customer bases. Explore brands with omnichannel presence, repeat purchase behavior, and expansion potential.',
                            'Food, Beverage & Agriculture' =>
                                'Review food, beverage, and agriculture ventures across production and distribution. Opportunities include strong local demand, supplier ecosystems, and brand-led growth.',
                            'Energy, Utilities & Environment' =>
                                'Identify businesses in energy, utilities, and sustainability-focused services. Compare opportunities tied to infrastructure demand, efficiency solutions, and green transition trends.',
                            'Media, Entertainment & Creative' =>
                                'Explore media and creative businesses with engaged audiences and monetization channels. Includes digital content, production, and brand-driven platforms.',
                            'Professional Services' =>
                                'Evaluate service firms with repeat clients and dependable cash flows. Common opportunities include consulting, compliance, and specialist advisory businesses.',
                            'Construction, Property & Facilities' =>
                                'Browse construction and facilities businesses with active projects and contracts. Suitable for buyers seeking operational scale in infrastructure and property services.',
                            'Education & Training' =>
                                'Find education and training providers serving academic and professional markets. Opportunities include scalable delivery models and recurring enrollment pipelines.',
                            'Government, Public Sector & Non-Profit' =>
                                'Discover organizations and service providers aligned with public and social impact needs. Explore stable demand models backed by institutional partnerships.',
                            'Travel, Hospitality & Leisure' =>
                                'Review hospitality and leisure businesses with strong customer experience focus. Ideal for investors targeting tourism recovery and destination-led growth.',
                            'Business Services & Misc' =>
                                'Explore diverse business services opportunities across support and specialist niches. A broad category with flexible acquisition options for strategic expansion.',
                        ];
                    @endphp

                    @foreach ($industries as $idx => $industry)
                        @php
                            $img = $industryImages[$idx % count($industryImages)];
                            $count = (int) ($industryListingCounts[$industry->id] ?? 0);
                            $desc =
                                $industryDescriptions[$industry->name] ??
                                "Explore available businesses in {$industry->name}. Connect with relevant buyers and sellers through confidential, sector-focused opportunities.";
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
                                            $singleUrl = route('blog.single', $blog->id); // ✅ apna route name set karo
                                        @endphp

                                        <a href="{{ route('webite-blog-single', $blog->id) }}"
                                            class="geodir-category-img_item"
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
                                                <li><a href="{{ route('webite-blog-single', $blog->id) }}"><i
                                                            class="fas fa-arrow-right"></i></a>
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
                                                <a
                                                    href="{{ route('webite-blog-single', $blog->id) }}">{{ $blog->title }}</a>
                                            </h4>

                                            <h5>
                                                <a href="{{ route('webite-blog-single', $blog->id) }}">
                                                    By: {{ $blog->user->name ?? 'Admin' }}
                                                </a>
                                            </h5>
                                        </div>

                                        {{-- Description 3 lines --}}
                                        <p
                                            style="
                        display:-webkit-box;
                        -webkit-line-clamp:3;
                        -webkit-box-orient:vertical;
                        overflow:hidden;">
                                            {{ \Illuminate\Support\Str::limit(strip_tags($blog->content ?? ($blog->description ?? '')), 140) }}
                                        </p>

                                        {{-- FOOTER --}}
                                        <div class="geodir-category-footer fl-wrap" style="margin-top:auto;">
                                            <a href="{{ route('webite-blog-single', $blog->id) }}"
                                                class="btn float-btn color-bg small-btn">
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
            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px"
                y="0px" width="100%" height="100%" viewBox="0 0 1600 900" preserveAspectRatio="xMidYMax slice">
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
                    <div class="text-carousel-item fl-wrap"
                        style="display:flex; flex-direction:column; min-height:420px;">

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

                            <a href="{{ route('webite-business') }}" class="testim-link color-bg"
                                style="margin-top:auto;">
                                Software Business Owner
                            </a>
                        </div>
                    </div>
                </div>

                <!-- CARD 2 -->
                <div class="slick-item">
                    <div class="text-carousel-item fl-wrap"
                        style="display:flex; flex-direction:column; min-height:420px;">

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

                            <a href="{{ route('webite-business') }}" class="testim-link color-bg"
                                style="margin-top:auto;">
                                Investment Director
                            </a>
                        </div>
                    </div>
                </div>

                <!-- CARD 3 -->
                <div class="slick-item">
                    <div class="text-carousel-item fl-wrap"
                        style="display:flex; flex-direction:column; min-height:420px;">

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
                    <div class="text-carousel-item fl-wrap"
                        style="display:flex; flex-direction:column; min-height:420px;">

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

                            <a href="{{ route('webite-business') }}" class="testim-link color-bg"
                                style="margin-top:auto;">
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
