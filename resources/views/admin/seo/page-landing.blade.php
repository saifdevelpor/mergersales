@extends('home')

@section('website-content')
    @php
        $keywords = collect(explode(',', (string) $page->keywords))->map(fn ($keyword) => trim($keyword))->filter()->values();
        $content = trim((string) $page->description);
        $paragraphs = collect(preg_split('/\r\n\r\n|\r\r|\n\n/', $content))->map(fn ($paragraph) => trim($paragraph))->filter()->values();

        if ($paragraphs->isEmpty() && $content !== '') {
            $paragraphs = collect([$content]);
        }

        $primaryKeyword = $keywords->first() ?: 'Confidential M&A opportunities';
        $secondaryKeywords = $keywords->slice(1, 4)->values();
        $ogImageUrl = \App\Helpers\SeoHelper::imageUrl($page->og_image);
        $topicCount = max(3, $keywords->count());
        $marketFocus = str_contains(strtolower($page->slug), 'saudi') ? 'Middle East' : (str_contains(strtolower($page->slug), 'uk') || str_contains(strtolower($page->slug), 'london') ? 'Europe' : 'Global');
        $rawSelectedState = trim((string) request('state', ''));
        $selectedState = preg_replace('/\s*,\s*(united states|usa|us)\s*$/i', '', $rawSelectedState) ?: $rawSelectedState;
        $selectedState = trim($selectedState);
        $locationLabel = $selectedState !== '' ? $selectedState : $marketFocus;
        $knownLocationNames = [
            'United States',
            'USA',
            'US',
            'U.S.A.',
            'U.S.',
            'America',
            'United Kingdom',
            'UK',
            'England',
            'Scotland',
            'Wales',
            'Ireland',
            'Saudi Arabia',
            'United Arab Emirates',
            'UAE',
            'Pakistan',
            'India',
            'Canada',
            'Australia',
            'New Zealand',
            'Germany',
            'France',
            'Spain',
            'Italy',
            'Netherlands',
            'Belgium',
            'Switzerland',
            'Austria',
            'Sweden',
            'Norway',
            'Denmark',
            'Finland',
            'Poland',
            'Portugal',
            'Turkey',
            'Qatar',
            'Bahrain',
            'Kuwait',
            'Oman',
            'Egypt',
            'South Africa',
            'Nigeria',
            'Singapore',
            'Malaysia',
            'Indonesia',
            'Thailand',
            'Philippines',
            'Vietnam',
            'China',
            'Japan',
            'South Korea',
            'Mexico',
            'Brazil',
        ];
        $pageTextForDetection = strtolower(implode(' ', array_filter([
            (string) $page->slug,
            (string) $page->name,
            (string) $page->heading,
            (string) $page->meta_title,
            (string) $page->meta_description,
            (string) $page->og_title,
            (string) $page->og_description,
            (string) $page->description,
        ])));
        $priorityLocationDetectionText = strtolower(implode(' ', array_filter([
            (string) $page->name,
            (string) $page->heading,
            (string) $page->meta_title,
            (string) $page->meta_description,
            (string) $page->og_title,
            (string) $page->og_description,
        ])));
        $locationSourceTexts = array_values(array_filter([
            (string) $page->name,
            (string) $page->heading,
            (string) $page->meta_title,
            (string) $page->meta_description,
            (string) $page->og_title,
            (string) $page->og_description,
            (string) $page->description,
            (string) $page->keywords,
        ]));
        $locationReplacementTargets = collect($knownLocationNames)
            ->filter(function (string $location) use ($priorityLocationDetectionText) {
                return str_contains($priorityLocationDetectionText, strtolower($location));
            })
            ->values();

        if ($locationReplacementTargets->isEmpty()) {
            $locationReplacementTargets = collect($knownLocationNames)
                ->filter(function (string $location) use ($pageTextForDetection) {
                    return str_contains($pageTextForDetection, strtolower($location));
                })
                ->values();
        }

        $locationReplacementTargets = $locationReplacementTargets
            ->push('{country}')
            ->unique(fn ($location) => strtolower($location))
            ->sortByDesc(fn ($location) => strlen($location))
            ->values();
        $extractedLocationTargets = collect();

        foreach ($locationSourceTexts as $sourceText) {
            preg_match_all('/\b(?:in|at|for|from|across|within|inside|near)\s+([A-Z][A-Za-z]+(?:[\s-][A-Z][A-Za-z]+){0,3})\b/u', $sourceText, $matches);

            foreach (($matches[1] ?? []) as $match) {
                $candidate = trim((string) $match, " \t\n\r\0\x0B.,");

                if ($candidate === '' || in_array(strtolower($candidate), ['business', 'businesses', 'sale', 'sales', 'market'], true)) {
                    continue;
                }

                $extractedLocationTargets->push($candidate);
            }
        }

        $locationReplacementTargets = $locationReplacementTargets
            ->merge($extractedLocationTargets)
            ->reject(function ($location) {
                return in_array(strtolower((string) $location), ['us'], true);
            })
            ->unique(fn ($location) => strtolower((string) $location))
            ->sortByDesc(fn ($location) => strlen((string) $location))
            ->values();
        $isUnitedStatesPage = str_contains($pageTextForDetection, 'usa')
            || str_contains($pageTextForDetection, 'united states')
            || str_contains($pageTextForDetection, 'u.s.a')
            || str_contains($pageTextForDetection, 'u.s.')
            || str_contains($pageTextForDetection, ' america');
        $injectLocation = static function (?string $text) use ($locationLabel, $selectedState, $isUnitedStatesPage, $locationReplacementTargets) {
            if ($text === null) {
                return null;
            }

            $text = str_replace('{country}', $locationLabel, $text);

            if ($selectedState !== '') {
                foreach ($locationReplacementTargets as $target) {
                    if ($target === '{country}') {
                        continue;
                    }

                    $quotedTarget = preg_quote((string) $target, '/');
                    $text = preg_replace('/(?<![A-Za-z])' . $quotedTarget . '(?![A-Za-z])/iu', $selectedState, $text) ?? $text;
                }
            }

            if ($selectedState !== '' && $isUnitedStatesPage) {
                $text = preg_replace('/\bUnited States\b/i', $selectedState, $text) ?? $text;
                $text = preg_replace('/\bUSA\b/i', $selectedState, $text) ?? $text;
                $text = preg_replace('/(?<![A-Za-z])US(?![A-Za-z])/i', $selectedState, $text) ?? $text;
                $text = preg_replace('/(?<![A-Za-z])U\.S\.A\.(?![A-Za-z])/i', $selectedState, $text) ?? $text;
                $text = preg_replace('/(?<![A-Za-z])U\.S\.(?![A-Za-z])/i', $selectedState, $text) ?? $text;
                $text = preg_replace('/\bAmerica\b/i', $selectedState, $text) ?? $text;
            }

            return $text;
        };
        $summary = $injectLocation($page->meta_description ?: \Illuminate\Support\Str::limit($content, 210, ''));
        $readingMinutes = max(3, (int) ceil(str_word_count(strip_tags($content ?: $summary)) / 120));
        $canonical = $page->canonical_url ?: route('seo.pages.show', ['page' => $page->slug] + ($selectedState !== '' ? ['state' => $selectedState] : []));
        $resolvedHeading = $injectLocation($page->heading ?: $page->name);
        $heroTitle = $resolvedHeading;
        $pageTitle = $resolvedHeading;
        if (isset($seo) && is_array($seo)) {
            $seo['og'] = is_array($seo['og'] ?? null) ? $seo['og'] : [];
            $seo['twitter'] = is_array($seo['twitter'] ?? null) ? $seo['twitter'] : [];
            $seo['title'] = $injectLocation($seo['title'] ?? null);
            $seo['description'] = $injectLocation($seo['description'] ?? null);
            $seo['og']['title'] = $injectLocation($seo['og']['title'] ?? null);
            $seo['og']['description'] = $injectLocation($seo['og']['description'] ?? null);
            $seo['twitter']['title'] = $injectLocation($seo['twitter']['title'] ?? null);
            $seo['twitter']['description'] = $injectLocation($seo['twitter']['description'] ?? null);
        }
        $benefits = [
            ['title' => 'Confidential outreach', 'copy' => 'Start conversations with serious operators and investors while protecting momentum and discretion.', 'icon' => 'fas fa-user-secret'],
            ['title' => 'Qualified demand capture', 'copy' => 'Bring high-intent buyers into a focused page experience built around acquisition-ready search intent.', 'icon' => 'fas fa-chart-line'],
            ['title' => 'Clear next steps', 'copy' => 'Move visitors from discovery to enquiry through stronger structure, proof points, and visible actions.', 'icon' => 'fas fa-compass'],
        ];
        $journey = [
            ['step' => '01', 'title' => 'Discover the market', 'copy' => 'Understand the opportunity, buyer demand, and themes shaping this acquisition landscape.'],
            ['step' => '02', 'title' => 'Review aligned listings', 'copy' => 'Explore businesses and opportunities that match the intent behind this page.'],
            ['step' => '03', 'title' => 'Connect with confidence', 'copy' => 'Open discreet conversations with sellers and move toward serious deal evaluation.'],
        ];
        $marketSignals = [
            ['label' => 'Search Themes', 'value' => $topicCount, 'suffix' => '+'],
            ['label' => 'Read Time', 'value' => $readingMinutes, 'suffix' => ' min'],
            ['label' => $selectedState !== '' ? 'Selected State' : 'Market Focus', 'value' => $locationLabel, 'suffix' => ''],
            ['label' => 'Page Status', 'value' => $page->robots_index ? 'Live' : 'Draft', 'suffix' => ''],
        ];
        $faqItems = [
            ['question' => 'Who is this page for?', 'answer' => 'It is designed for buyers, investors, and strategic acquirers looking for focused opportunities related to ' . $pageTitle . '.'],
            ['question' => 'What should visitors do next?', 'answer' => 'Visitors should review relevant listings, assess the market fit in ' . $locationLabel . ', and contact the team or sellers for the next step.'],
            ['question' => 'How does this page help SEO?', 'answer' => 'It aligns focused search phrases, supporting content, and clear conversion pathways into one structured landing page for ' . $locationLabel . '.'],
        ];
        $states = [
            'Alabama',
            'Alaska',
            'Arizona',
            'Arkansas',
            'California',
            'Colorado',
            'Connecticut',
            'Delaware',
            'Florida',
            'Georgia',
            'Hawaii',
            'Idaho',
            'Illinois',
            'Indiana',
            'Iowa',
            'Kansas',
            'Kentucky',
            'Louisiana',
            'Maine',
            'Maryland',
            'Massachusetts',
            'Michigan',
            'Minnesota',
            'Mississippi',
            'Missouri',
            'Montana',
            'Nebraska',
            'Nevada',
            'New Hampshire',
            'New Jersey',
            'New Mexico',
            'New York',
            'North Carolina',
            'North Dakota',
            'Ohio',
            'Oklahoma',
            'Oregon',
            'Pennsylvania',
            'Rhode Island',
            'South Carolina',
            'South Dakota',
            'Tennessee',
            'Texas',
            'Utah',
            'Vermont',
            'Virginia',
            'Washington',
            'West Virginia',
            'Wisconsin',
            'Wyoming',
        ];
    @endphp

    <style>
    .seo-landing { background:linear-gradient(180deg, #f8fafc 0%, #fffdf8 45%, #f8fafc 100%); padding-bottom:40px; }
    .seo-landing .gold-badge { display:inline-flex; align-items:center; gap:8px; padding:10px 16px; border-radius:999px; background:rgba(204,170,87,.14); color:#8e6920; font-size:12px; font-weight:700; letter-spacing:.12em; text-transform:uppercase; }
    .seo-landing .hero-shell { padding:38px 0 20px; }
    .seo-landing .hero-panel { background:linear-gradient(135deg, rgba(255,255,255,.98), rgba(248,243,229,.98)); border-radius:30px; padding:58px 58px 34px; box-shadow:0 28px 70px rgba(17,24,39,.09); border:1px solid rgba(204,170,87,.14); position:relative; overflow:hidden; }
    .seo-landing .hero-panel:before { content:""; position:absolute; inset:-60px auto auto -40px; width:240px; height:240px; border-radius:50%; background:rgba(204,170,87,.08); }
    .seo-landing .hero-panel:after { content:""; position:absolute; inset:auto -100px -120px auto; width:320px; height:320px; border-radius:50%; background:rgba(204,170,87,.06); }
    .seo-landing .hero-grid { position:relative; z-index:1; display:grid; grid-template-columns:minmax(0, 1.15fr) minmax(340px, .85fr); gap:46px; align-items:center; }
    .seo-landing .hero-title { font-size:clamp(2.6rem, 4.2vw, 4.8rem); line-height:1; margin:18px 0 18px; color:#152033; max-width:11ch; }
    .seo-landing .hero-copy { max-width:720px; color:#55657d; font-size:18px; line-height:1.8; margin-bottom:30px; }
    .seo-landing .hero-actions { display:flex; flex-wrap:wrap; gap:14px; margin-bottom:26px; }
    .seo-landing .hero-actions .btn { min-width:180px; text-align:center; }
    .seo-landing .hero-highlights { display:flex; flex-wrap:wrap; gap:10px; margin-bottom:26px; }
    .seo-landing .hero-chip { padding:10px 14px; border-radius:999px; background:#fff; border:1px solid rgba(21,32,51,.08); color:#6f7f95; font-weight:700; box-shadow:0 8px 20px rgba(17,24,39,.04); }
    .seo-landing .hero-metrics { display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:16px; }
    .seo-landing .metric-card, .seo-landing .surface-card { background:#fff; border-radius:22px; border:1px solid rgba(17,24,39,.06); box-shadow:0 16px 34px rgba(17,24,39,.05); }
    .seo-landing .metric-card { padding:20px 18px; min-height:100%; }
    .seo-landing .metric-card span { display:block; color:#70829a; font-size:12px; text-transform:uppercase; letter-spacing:.08em; margin-bottom:8px; }
    .seo-landing .metric-card strong { color:#152033; font-size:1.4rem; line-height:1.3; font-weight:800; display:block; overflow-wrap:anywhere; }
    .seo-landing .hero-media-wrap { position:relative; }
    .seo-landing .hero-media-frame { background:#fff; border-radius:30px; padding:14px; box-shadow:0 30px 60px rgba(17,24,39,.15); border:1px solid rgba(204,170,87,.16); }
    .seo-landing .hero-media img { width:100%; height:440px; object-fit:cover; display:block; border-radius:22px; background:#eef2f7; }
    .seo-landing .hero-caption { display:flex; justify-content:space-between; gap:16px; align-items:center; padding:14px 4px 0; color:#6d7f97; font-size:13px; letter-spacing:.04em; text-transform:uppercase; }
    .seo-landing .floating-stats { position:absolute; left:-26px; bottom:28px; width:min(260px, calc(100% - 40px)); padding:20px; background:rgba(21,32,51,.92); color:#fff; border-radius:24px; box-shadow:0 24px 40px rgba(17,24,39,.24); }
    .seo-landing .floating-stats small { display:block; color:rgba(255,255,255,.72); text-transform:uppercase; letter-spacing:.08em; margin-bottom:8px; }
    .seo-landing .floating-stats strong { display:block; font-size:1.7rem; line-height:1.2; margin-bottom:8px; }
    .seo-landing .floating-stats p { margin:0; color:rgba(255,255,255,.84); line-height:1.6; }
    .seo-landing .section-box { padding:34px 0; }
    .seo-landing .section-heading { margin-bottom:24px; }
    .seo-landing .section-heading h2 { color:#152033; font-size:2.1rem; margin-bottom:10px; }
    .seo-landing .section-heading p { color:#64748b; max-width:760px; margin:0; line-height:1.8; }
    .seo-landing .state-panel { background:linear-gradient(135deg, #152033 0%, #1f304b 100%); border-radius:28px; padding:34px 30px; box-shadow:0 22px 44px rgba(17,24,39,.16); border:1px solid rgba(204,170,87,.2); }
    .seo-landing .state-panel .section-heading { margin-bottom:22px; }
    .seo-landing .state-panel .section-heading h2 { color:#fff; }
    .seo-landing .state-panel .section-heading p { color:rgba(255,255,255,.74); max-width:880px; }
    .seo-landing .state-grid { display:grid; grid-template-columns:repeat(5, minmax(0,1fr)); gap:16px; }
    .seo-landing .state-link { display:inline-flex; align-items:center; justify-content:center; min-height:56px; padding:12px 18px; text-align:center; border-radius:999px; background:#CCAA57; color:#fff; font-weight:800; font-size:15px; line-height:1.3; text-decoration:none; box-shadow:0 10px 24px rgba(204,170,87,.28); transition:transform .18s ease, box-shadow .18s ease, background .18s ease; }
    .seo-landing .state-link:hover { color:#fff; background:#d9b76a; transform:translateY(-2px); box-shadow:0 14px 28px rgba(204,170,87,.34); }
    .seo-landing .state-link.is-active { background:#fff; color:#152033; box-shadow:0 12px 26px rgba(255,255,255,.16); }
    .seo-landing .state-note { margin-top:18px; color:rgba(255,255,255,.7); font-size:14px; line-height:1.7; }
    .seo-landing .surface-card { padding:30px; height:100%; }
    .seo-landing .copy-card h3, .seo-landing .info-card h3, .seo-landing .surface-card h3 { color:#152033; margin-bottom:16px; font-size:1.5rem; }
    .seo-landing .copy-card p, .seo-landing .surface-card p { color:#5d6d84; line-height:1.9; margin-bottom:16px; }
    .seo-landing .insight-grid { display:grid; grid-template-columns:1.2fr .8fr; gap:24px; }
    .seo-landing .info-list { display:grid; gap:14px; }
    .seo-landing .info-item { padding:16px 18px; border-radius:14px; background:#f8fafc; border:1px solid rgba(17,24,39,.05); }
    .seo-landing .info-item small { display:block; color:#6c7d93; font-size:12px; text-transform:uppercase; letter-spacing:.08em; margin-bottom:6px; }
    .seo-landing .info-item div { color:#152033; font-weight:700; word-break:break-word; }
    .seo-landing .info-image { overflow:hidden; margin-bottom:18px; }
    .seo-landing .info-image img { width:100%; height:220px; object-fit:cover; display:block; border-radius:18px; }
    .seo-landing .benefit-card { background:#fff; border-radius:20px; padding:30px 24px; height:100%; box-shadow:0 14px 28px rgba(17,24,39,.05); border-top:3px solid #CCAA57; }
    .seo-landing .benefit-card h4 { margin:14px 0 10px; color:#152033; font-size:1.3rem; }
    .seo-landing .benefit-card p { color:#5c6d84; line-height:1.8; margin:0; }
    .seo-landing .benefit-icon { width:58px; height:58px; border-radius:18px; display:inline-flex; align-items:center; justify-content:center; background:rgba(204,170,87,.14); color:#CCAA57; font-size:22px; }
    .seo-landing .signal-grid, .seo-landing .journey-grid, .seo-landing .faq-grid { display:grid; gap:18px; }
    .seo-landing .signal-grid { grid-template-columns:repeat(4, minmax(0,1fr)); }
    .seo-landing .signal-card { padding:24px; background:#fff; border-radius:20px; border:1px solid rgba(17,24,39,.06); box-shadow:0 14px 28px rgba(17,24,39,.05); }
    .seo-landing .signal-card span { display:block; color:#74859d; text-transform:uppercase; letter-spacing:.08em; font-size:12px; margin-bottom:8px; }
    .seo-landing .signal-card strong { font-size:1.75rem; color:#152033; }
    .seo-landing .journey-grid { grid-template-columns:repeat(3, minmax(0,1fr)); }
    .seo-landing .journey-card { padding:28px; background:linear-gradient(180deg, #ffffff 0%, #fffaf0 100%); border-radius:24px; border:1px solid rgba(204,170,87,.16); box-shadow:0 16px 32px rgba(17,24,39,.05); }
    .seo-landing .journey-step { display:inline-flex; width:52px; height:52px; align-items:center; justify-content:center; border-radius:16px; background:#152033; color:#fff; font-size:16px; font-weight:800; margin-bottom:16px; }
    .seo-landing .journey-card h4 { color:#152033; font-size:1.25rem; margin-bottom:10px; }
    .seo-landing .journey-card p { color:#5d6d84; line-height:1.8; margin:0; }
    .seo-landing .keyword-wrap { display:flex; flex-wrap:wrap; gap:12px; margin-top:18px; }
    .seo-landing .keyword-chip { padding:11px 16px; border-radius:999px; background:#fff; border:1px solid rgba(204,170,87,.24); color:#8e6920; font-weight:700; box-shadow:0 8px 18px rgba(17,24,39,.04); }
    .seo-landing .keyword-panel { display:grid; grid-template-columns:1fr 1fr; gap:22px; }
    .seo-landing .mini-list { display:grid; gap:12px; }
    .seo-landing .mini-item { padding:14px 16px; background:#f8fafc; border-radius:14px; border:1px solid rgba(17,24,39,.05); color:#425168; }
    .seo-landing .faq-grid { grid-template-columns:repeat(3, minmax(0,1fr)); }
    .seo-landing .faq-card { padding:26px; background:#fff; border-radius:20px; box-shadow:0 12px 28px rgba(17,24,39,.05); border:1px solid rgba(17,24,39,.06); }
    .seo-landing .faq-card h4 { color:#152033; font-size:1.1rem; margin-bottom:12px; }
    .seo-landing .faq-card p { color:#5c6d84; line-height:1.8; margin:0; }
    .seo-landing .cta-strip { background:linear-gradient(135deg, #fffaf0 0%, #f7edd1 48%, #e7cb85 100%); border-radius:28px; padding:46px 38px; color:#152033; box-shadow:0 20px 44px rgba(204,170,87,.18); border:1px solid rgba(204,170,87,.26); }
    .seo-landing .cta-strip h3 { color:#152033; font-size:2rem; margin-bottom:12px; }
    .seo-landing .cta-strip p { color:#5f6f86; line-height:1.85; margin-bottom:0; }
    .seo-landing .cta-actions { display:flex; flex-wrap:wrap; gap:14px; justify-content:flex-end; align-items:center; }
    .seo-landing .cta-actions .btn { min-width:190px; text-align:center; }
    @media (max-width: 1199px) {
        .seo-landing .hero-panel { padding:42px 32px 28px; }
        .seo-landing .hero-grid, .seo-landing .insight-grid, .seo-landing .keyword-panel { grid-template-columns:1fr; }
        .seo-landing .hero-title, .seo-landing .hero-copy { max-width:none; }
        .seo-landing .hero-metrics, .seo-landing .signal-grid, .seo-landing .journey-grid, .seo-landing .faq-grid { grid-template-columns:repeat(2, minmax(0,1fr)); }
        .seo-landing .state-grid { grid-template-columns:repeat(3, minmax(0,1fr)); }
        .seo-landing .hero-media img { height:360px; }
        .seo-landing .floating-stats { position:static; width:100%; margin-top:16px; }
    }
    @media (max-width: 991px) {
        .seo-landing .hero-panel { padding:32px 20px 22px; border-radius:24px; }
        .seo-landing .hero-title { font-size:clamp(2.1rem, 10vw, 3.4rem); }
        .seo-landing .hero-metrics, .seo-landing .signal-grid, .seo-landing .journey-grid, .seo-landing .faq-grid { grid-template-columns:1fr; }
        .seo-landing .state-panel { padding:28px 18px; }
        .seo-landing .state-grid { grid-template-columns:repeat(2, minmax(0,1fr)); gap:12px; }
        .seo-landing .cta-actions { justify-content:flex-start; margin-top:20px; }
        .seo-landing .hero-media img { height:280px; }
    }
    </style>

    <div class="seo-landing">
        <section class="parallax-section single-par color-bg">
            <div class="container">
                <div class="section-title center-align big-title">
                    <h2><span>{{ $pageTitle }}</span></h2>
                    <h4>{{ $summary ?: ('Confidentially discover acquisition opportunities and connect with serious buyers and sellers in ' . $locationLabel . ' on Mergersales.') }}</h4>
                </div>
            </div>
            <div class="pwh_bg"></div>
        </section>

        <section class="hero-shell">
            <div class="container">
                <div class="hero-panel">
                    <div class="hero-grid">
                        <div>
                            <div class="gold-badge">Mergersales Opportunity Page</div>
                            <h1 class="hero-title">{{ $heroTitle }}</h1>
                            <div class="hero-copy">{{ $summary ?: ('This landing page is designed to match the Mergersales website style and highlight serious, confidential M&A opportunities in ' . $locationLabel . '.') }}</div>

                            <div class="hero-highlights">
                                <span class="hero-chip">{{ $injectLocation($primaryKeyword) }}</span>
                                <span class="hero-chip">{{ $locationLabel }}</span>
                                <span class="hero-chip">{{ $readingMinutes }} minute overview</span>
                            </div>

                            <div class="hero-actions">
                                <a href="{{ route('webite-business') }}" class="btn float-btn color-bg small-btn">Browse Businesses</a>
                                <a href="{{ route('webite-contact') }}" class="btn float-btn small-btn" style="background:#fff;color:#152033;border:1px solid rgba(0,0,0,0.08);">Contact Us</a>
                            </div>

                            <div class="hero-metrics">
                                <div class="metric-card"><span>Primary Focus</span><strong>{{ $injectLocation($primaryKeyword) }}</strong></div>
                                <div class="metric-card"><span>SEO Slug</span><strong>{{ $page->slug }}</strong></div>
                                <div class="metric-card"><span>Schema Type</span><strong>{{ $page->schema_type ?: 'WebPage' }}</strong></div>
                                <div class="metric-card"><span>{{ $selectedState !== '' ? 'Selected State' : 'Content Depth' }}</span><strong>{{ $selectedState !== '' ? $selectedState : ($readingMinutes . ' min read') }}</strong></div>
                            </div>
                        </div>

                        <div class="hero-media-wrap">
                            @if ($ogImageUrl)
                                <div class="hero-media-frame hero-media">
                                    <img src="{{ $ogImageUrl }}" alt="{{ $page->og_title ?: $pageTitle }}">
                                    <div class="hero-caption">
                                        <span>{{ $selectedState !== '' ? 'Selected State Visual' : 'Featured Market Visual' }}</span>
                                        <span>{{ $selectedState !== '' ? $selectedState : $page->slug }}</span>
                                    </div>
                                </div>
                            @else
                                <div class="hero-media-frame hero-media">
                                    <img src="{{ asset('assets/img/default-og-image.jpg') }}" alt="{{ $pageTitle }}">
                                    <div class="hero-caption">
                                        <span>Marketplace Preview</span>
                                        <span>{{ $locationLabel }}</span>
                                    </div>
                                </div>
                            @endif

                            <div class="floating-stats">
                                <small>{{ $selectedState !== '' ? 'State Summary' : 'Market Summary' }}</small>
                                <strong>{{ $topicCount }} high-intent themes</strong>
                                <p>This page is structured to support serious acquisition discovery, stronger relevance, and clearer buyer next steps for {{ $locationLabel }}.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="section-box">
            <div class="container">
                <div class="state-panel">
                    <div class="section-heading">
                        <h2>Choose Your State</h2>
                        <p>Select any state below. It will open this same landing page in a new tab and automatically replace the location content with the selected state name.</p>
                    </div>

                    <div class="state-grid">
                        @foreach ($states as $state)
                            <a href="{{ route('seo.pages.show', ['page' => $page->slug, 'state' => $state]) }}" target="_blank" rel="noopener noreferrer" class="state-link{{ $selectedState === $state ? ' is-active' : '' }}">
                                {{ $state }}
                            </a>
                        @endforeach
                    </div>

                    <div class="state-note">
                        @if ($selectedState !== '')
                            Current landing page is personalized for <strong style="color:#fff;">{{ $selectedState }}</strong>.
                        @else
                            This section uses your existing Mergersales theme colors and creates state-specific landing page views.
                        @endif
                    </div>
                </div>
            </div>
        </section>

        <section class="section-box">
            <div class="container">
                <div class="section-heading">
                    <h2>{{ $selectedState !== '' ? ($selectedState . ' Market Overview') : 'Market Overview' }}</h2>
                    <p>Use this section to frame the opportunity, explain why demand matters, and give visitors a stronger sense of the market behind this landing page for {{ $locationLabel }}.</p>
                </div>

                <div class="insight-grid">
                    <div class="surface-card copy-card">
                        <h3>Why {{ $locationLabel }} Matters</h3>
                        @if ($paragraphs->isNotEmpty())
                            @foreach ($paragraphs as $paragraph)
                                <p>{{ $injectLocation($paragraph) }}</p>
                            @endforeach
                        @else
                            <p>This custom page is ready to publish as a Mergersales-style landing page for {{ $locationLabel }}. Add focused buyer intent copy, market insights, and a clear CTA from the SEO manager.</p>
                            <p>Use this space for {{ $locationLabel }} demand, industry activity, valuation expectations, and reasons why users should explore opportunities through your marketplace.</p>
                        @endif
                    </div>

                    <div class="surface-card info-card">
                        <h3>Page SEO Snapshot</h3>
                        @if ($ogImageUrl)
                            <div class="info-image">
                                <img src="{{ $ogImageUrl }}" alt="{{ $page->og_title ?: ($page->heading ?: $page->name) }}">
                            </div>
                        @endif
                        <div class="info-list">
                            <div class="info-item"><small>Meta Title</small><div>{{ $injectLocation($page->meta_title ?: ($page->heading ?: $page->name)) }}</div></div>
                            <div class="info-item"><small>Canonical URL</small><div>{{ $canonical }}</div></div>
                            <div class="info-item"><small>Index Status</small><div>{{ $page->robots_index ? 'Index enabled' : 'Noindex' }}</div></div>
                            <div class="info-item"><small>Follow Status</small><div>{{ $page->robots_follow ? 'Follow enabled' : 'Nofollow' }}</div></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="section-box">
            <div class="container">
                <div class="section-heading">
                    <h2>Opportunity Signals</h2>
                    <p>Give visitors stronger context with quick signals that make the page feel more substantial and strategic for {{ $locationLabel }}.</p>
                </div>

                <div class="signal-grid">
                    @foreach ($marketSignals as $signal)
                        <div class="signal-card">
                            <span>{{ $signal['label'] }}</span>
                            <strong>{{ $signal['value'] }}{{ $signal['suffix'] }}</strong>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="gray-bg small-padding">
            <div class="container">
                <div class="section-heading">
                    <h2>Why Buyers Use Mergersales</h2>
                    <p>Built around the same confident, clean style as your main marketplace pages, with stronger messaging and more reasons to continue in {{ $locationLabel }}.</p>
                </div>

                <div class="row">
                    @foreach ($benefits as $benefit)
                        <div class="col-md-4">
                            <div class="benefit-card">
                                <div class="benefit-icon"><i class="{{ $benefit['icon'] }}"></i></div>
                                <h4>{{ $benefit['title'] }}</h4>
                                <p>{{ $benefit['copy'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="section-box">
            <div class="container">
                <div class="section-heading">
                    <h2>How Visitors Move Forward</h2>
                    <p>Turn this landing page into a journey for {{ $locationLabel }}, not just a banner and a few paragraphs.</p>
                </div>

                <div class="journey-grid">
                    @foreach ($journey as $item)
                        <div class="journey-card">
                            <div class="journey-step">{{ $item['step'] }}</div>
                            <h4>{{ $item['title'] }}</h4>
                            <p>{{ $item['copy'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="section-box">
            <div class="container">
                <div class="section-heading">
                    <h2>Target Search Themes</h2>
                    <p>Use keyword support blocks to make the page feel deeper and reinforce what this landing page is designed to capture in {{ $locationLabel }}.</p>
                </div>

                <div class="keyword-panel">
                    <div class="surface-card">
                        <h3>Priority Keywords</h3>
                        @if ($keywords->isNotEmpty())
                            <div class="keyword-wrap">
                                @foreach ($keywords as $keyword)
                                    <span class="keyword-chip">{{ $injectLocation($keyword) }}</span>
                                @endforeach
                            </div>
                        @else
                            <p style="margin:0;">Add page keywords from the SEO manager to show focused search themes here.</p>
                        @endif
                    </div>

                    <div class="surface-card">
                        <h3>What This Page Covers</h3>
                        <div class="mini-list">
                            <div class="mini-item">Buyer intent related to {{ strtolower($injectLocation($page->heading ?: $page->name)) }}</div>
                            <div class="mini-item">SEO-focused discovery paths tied to market, geography, and opportunity themes</div>
                            <div class="mini-item">A clearer bridge from search visibility to listing exploration and contact</div>
                            @foreach ($secondaryKeywords as $keyword)
                                <div class="mini-item">{{ $keyword }}</div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="gray-bg small-padding">
            <div class="container">
                <div class="section-heading">
                    <h2>Common Questions</h2>
                    <p>Add useful answers so the page feels more complete and conversion-ready.</p>
                </div>

                <div class="faq-grid">
                    @foreach ($faqItems as $faq)
                        <div class="faq-card">
                            <h4>{{ $faq['question'] }}</h4>
                            <p>{{ $faq['answer'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="small-padding">
            <div class="container">
                <div class="cta-strip">
                    <div class="row" style="align-items:center;">
                        <div class="col-md-8">
                            <h3>Ready to explore confidential business opportunities?</h3>
                            <p>Browse verified listings, discover acquisition targets, and connect with serious market participants through the Mergersales platform. This page is now structured to feel more substantial, more informative, and more conversion-focused.</p>
                        </div>
                        <div class="col-md-4">
                            <div class="cta-actions">
                                <a href="{{ route('webite-business') }}" class="btn float-btn small-btn" style="background:#CCAA57;color:#fff;border:1px solid #CCAA57;">View Businesses</a>
                                <a href="{{ route('webite-contact') }}" class="btn float-btn small-btn" style="background:#fff;color:#152033;border:1px solid rgba(21,32,51,0.12);">Speak to Team</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
