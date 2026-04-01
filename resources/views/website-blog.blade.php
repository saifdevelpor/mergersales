@extends('home')

@section('website-title')
    <title>Mergersales | Blog</title>
@endsection

@section('website-content')
    <section class="parallax-section single-par color-bg" data-scrollax-parent="true">
        <div class="bg-wrap bg-parallax-wrap-gradien">
            <div class="bg par-elem" data-bg="{{ asset('images/39.jpg') }}" data-scrollax="properties: { translateY: '30%' }">
            </div>
        </div>

        <div class="container">
            <div class="section-title center-align big-title">
                <h2><span>Business Blog & Market Insights</span></h2>
                <h4>Practical strategies, M&A insights, and growth guides to help you buy, sell, and scale businesses with
                    confidence.</h4>
            </div>

            <div class="scroll-down-wrap">
                <div class="mousey">
                    <div class="scroller"></div>
                </div>
                <span>Scroll for Latest Blogs</span>
            </div>
        </div>

        <div class="pwh_bg"></div>
    </section>

    <div class="gray-bg small-padding fl-wrap">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="post-container fl-wrap">

                        @foreach ($blogs as $blog)
                            <article class="post-article fl-wrap">

                                {{-- Media --}}
                                <div class="list-single-main-media fl-wrap">
                                    @php
                                        $gallery = $blog->gallery ?? [];
                                    @endphp

                                    @if (is_array($gallery) && count($gallery) > 1)
                                        <div class="single-slider-wrapper carousel-wrap fl-wrap">
                                            <div class="single-slider fl-wrap carousel lightgallery">
                                                @foreach ($gallery as $img)
                                                    <div class="slick-slide-item">
                                                        <div class="box-item">
                                                            <a href="{{ asset($img) }}" class="gal-link popup-image">
                                                                <i class="fal fa-search"></i>
                                                            </a>
                                                            <img src="{{ asset($img) }}" alt="Blog image">
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <div class="swiper-button-prev ssw-btn"><i class="fas fa-caret-left"></i></div>
                                            <div class="swiper-button-next ssw-btn"><i class="fas fa-caret-right"></i></div>
                                        </div>
                                    @else
                                        <a href="{{ route('webite-blog-single', e_id($blog->id)) }}">
                                            <img src="{{ asset($blog->image ?? 'images/default-blog.jpg') }}"
                                                class="respimg" alt="Blog image">
                                        </a>
                                    @endif
                                </div>

                                {{-- Content --}}
                                <div class="list-single-main-item fl-wrap block_box">

                                    @php
                                        // Support both schemas: `description` (migration) or legacy `details`
                                        $bodyHtml = $blog->description ?? ($blog->details ?? '');
                                        $decodedBody = html_entity_decode(
                                            (string) $bodyHtml,
                                            ENT_QUOTES | ENT_HTML5,
                                            'UTF-8',
                                        );

                                        // Remove full-document wrappers/styles often pasted from editors
                                        $cleanBodyHtml = preg_replace('/<!doctype[^>]*>/i', '', $decodedBody);
                                        $cleanBodyHtml = preg_replace(
                                            '#<head\b[^>]*>.*?</head>#is',
                                            '',
                                            (string) $cleanBodyHtml,
                                        );
                                        $cleanBodyHtml = preg_replace(
                                            '#<style\b[^>]*>.*?</style>#is',
                                            '',
                                            (string) $cleanBodyHtml,
                                        );
                                        $cleanBodyHtml = preg_replace(
                                            '#<script\b[^>]*>.*?</script>#is',
                                            '',
                                            (string) $cleanBodyHtml,
                                        );
                                        $cleanBodyHtml = preg_replace(
                                            '#</?(html|body)\b[^>]*>#i',
                                            '',
                                            (string) $cleanBodyHtml,
                                        );
                                        $cleanBodyHtml = trim((string) $cleanBodyHtml);

                                        $plainBody = strip_tags($cleanBodyHtml);
                                        $plainBody = str_replace("\xc2\xa0", ' ', $plainBody);
                                        $plainBody = preg_replace('/\s+/u', ' ', (string) $plainBody);
                                        $plainBody = trim((string) $plainBody);
                                        $title = $blog->title ?? \Illuminate\Support\Str::limit($plainBody, 60);
                                    @endphp

                                    @if ($title)
                                        <h2 class="post-opt-title" style="margin-bottom:10px;">
                                            <a href="{{ route('webite-blog-single', e_id($blog->id)) }}">{{ $title }}</a>
                                        </h2>
                                    @endif

                                    {{-- ✅ DETAILS PREVIEW (HTML render + decode) --}}
                                    <div class="blog-excerpt blog-excerpt-html" style="line-height:1.9;">
                                        {!! $cleanBodyHtml !!}
                                    </div>

                                    <span class="fw-separator fl-wrap"></span>

                                    <div class="post-author">
                                        <a href="{{ route('webite-blog-single', e_id($blog->id)) }}">
                                            <img src="{{ asset($blog->user->profile_photo ?? 'images/21.jpg') }}"
                                                alt="{{ $blog->user->name ?? 'Admin' }}">
                                            <span>By
                                                {{ $blog->user->name ?? 'Admin' }}</span>
                                        </a>
                                    </div>

                                    <div class="post-opt">
                                        <ul class="no-list-style">
                                            <li>
                                                <i class="fal fa-calendar"></i>
                                                <span>{{ optional($blog->created_at)->format('d M Y') }}</span>
                                            </li>
                                        </ul>
                                    </div>

                                    <a href="{{ route('webite-blog-single', e_id($blog->id)) }}"
                                        class="btn color-bg float-btn small-btn">
                                        Read Full Guide
                                    </a>

                                </div>

                            </article>
                        @endforeach

                        {{-- Pagination --}}
                        <div class="mt-4 d-flex justify-content-center">
                            {{ $blogs->links() }}
                        </div>

                    </div>
                </div>

                {{-- Right sidebar (same as your code) --}}
                <div class="col-md-4">
                    <div class="box-widget-wrap fl-wrap fixed-bar">
                        {{-- Popular Blogs --}}
                        <div class="box-widget fl-wrap">
                            <div class="box-widget-title fl-wrap">Popular Blogs</div>
                            <div class="box-widget-content fl-wrap">
                                <div class="widget-posts fl-wrap">
                                    <ul class="no-list-style">
                                        @foreach ($popularResources as $p)
                                            @php
                                                $pBody = $p->description ?? ($p->details ?? '');
                                                $pText = html_entity_decode($pBody, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                                                $pText = strip_tags($pText);
                                                $pText = str_replace("\xc2\xa0", ' ', $pText);
                                                $pText = preg_replace('/\s+/u', ' ', (string) $pText);
                                                $pText = trim((string) $pText);
                                                $pTitle = $p->title ?? \Illuminate\Support\Str::limit($pText, 45);
                                            @endphp
                                            <li>
                                                <div class="widget-posts-img">
                                                    <a href="{{ route('webite-blog-single', e_id($p->id)) }}">
                                                        <img src="{{ asset($p->image ?? 'images/default-blog.jpg') }}"
                                                            alt="{{ $pTitle }}">
                                                    </a>
                                                </div>
                                                <div class="widget-posts-descr">
                                                    <h4>
                                                        <a href="{{ route('webite-blog-single', e_id($p->id)) }}">
                                                            {{ $pTitle }}
                                                        </a>
                                                    </h4>
                                                    <div class="geodir-category-location fl-wrap">
                                                        <a href="{{ route('webite-blog-single', e_id($p->id)) }}">
                                                            <i class="fal fa-calendar"></i>
                                                            {{ optional($p->created_at)->format('d M Y') }}
                                                        </a>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>

                        {{-- Latest Business --}}
                        <div class="box-widget fl-wrap">
                            <div class="box-widget-title fl-wrap">Latest Business</div>

                            <div class="box-widget-content fl-wrap">
                                <div class="widget-posts fl-wrap">
                                    <ul class="no-list-style">
                                        @foreach ($latestListings as $l)
                                            @php
                                                $img = $l->business_img
                                                    ? asset('storage/' . ltrim($l->business_img, '/'))
                                                    : asset('assets/images/1.jpg');

                                                $singleUrl = route('business.single', e_id($l->id));
                                            @endphp

                                            <li>
                                                <div class="widget-posts-img">
                                                    <a href="{{ $singleUrl }}">
                                                        <img src="{{ $img }}" alt="{{ $l->title }}">
                                                    </a>
                                                </div>

                                                <div class="widget-posts-descr">
                                                    <h4>
                                                        <a href="{{ $singleUrl }}">
                                                            {{ \Illuminate\Support\Str::limit($l->business_name, 45) }}
                                                        </a>
                                                    </h4>

                                                    <div class="geodir-category-location fl-wrap">
                                                        <a href="{{ $singleUrl }}">
                                                            <i class="fal fa-map-marker-alt"></i>
                                                            {{ $l->country ?? 'Location' }}
                                                        </a>
                                                    </div>

                                                    <div class="geodir-category-location fl-wrap">
                                                        <a href="{{ $singleUrl }}">
                                                            <i class="fal fa-calendar"></i>
                                                            {{ optional($l->created_at)->format('d M Y') }}
                                                        </a>
                                                    </div>

                                                    @if (!is_null($l->price))
                                                        <div class="geodir-category-location fl-wrap">
                                                            <a href="{{ $singleUrl }}">
                                                                <i class="fal fa-tag"></i>
                                                                {{ number_format($l->ebitda_range) }}
                                                            </a>
                                                        </div>
                                                    @endif
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>

                                <a href="{{ route('webite-business') }}" class="btn float-btn color-bg small-btn"
                                    style="width:100%; text-align:center;">
                                    Browse Businesses
                                </a>
                            </div>
                        </div>

                        {{-- Archive --}}
                        <div class="box-widget fl-wrap">
                            <div class="box-widget-title fl-wrap">Archive</div>
                            <div class="box-widget-content fl-wrap">
                                <ul class="cat-item cat-item_dec no-list-style">
                                    @foreach ($archives as $a)
                                        @php
                                            $fullDate = \Carbon\Carbon::createFromDate(
                                                $a->year,
                                                $a->month,
                                                $a->day,
                                            )->format('d F Y');
                                        @endphp
                                        <li>
                                            {{ $fullDate }} ({{ $a->total }})
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="limit-box fl-wrap"></div>
    <style>
        .blog-excerpt-html {
            max-height: 210px;
            overflow: hidden;
        }
    </style>
@endsection
