@extends('home')

@section('website-title')
    <title>Mergersales | Single Blog</title>
@endsection

@section('website-content')
    <div class="gray-bg small-padding fl-wrap">
        <div class="container">
            <div class="row">

                {{-- LEFT: Blog Detail --}}
                <div class="col-md-8">
                    <div class="post-container fl-wrap">
                        <article class="post-article fl-wrap">

                            {{-- Media (Gallery OR Featured Image) --}}
                            <div class="list-single-main-media fl-wrap">
                                @php
                                    // gallery can be json array OR empty
                                    $gallery = $blog->gallery ?? []; // e.g. ["uploads/a.jpg","uploads/b.jpg"]
                                    $hasGallery = is_array($gallery) && count($gallery) > 1;
                                    $featured = $blog->image ?? 'images/default-blog.jpg';
                                @endphp

                                @if ($hasGallery)
                                    <div class="single-slider-wrapper carousel-wrap fl-wrap">
                                        <div class="single-slider fl-wrap carousel lightgallery">
                                            @foreach ($gallery as $img)
                                                <div class="slick-slide-item">
                                                    <div class="box-item">
                                                        <a href="{{ asset($img) }}" class="gal-link popup-image">
                                                            <i class="fal fa-search"></i>
                                                        </a>
                                                        <img src="{{ asset($img) }}" alt="{{ $blog->title }}">
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="swiper-button-prev ssw-btn"><i class="fas fa-caret-left"></i></div>
                                        <div class="swiper-button-next ssw-btn"><i class="fas fa-caret-right"></i></div>
                                    </div>
                                @else
                                    <img src="{{ asset($featured) }}" class="respimg" alt="{{ $blog->title }}">
                                @endif
                            </div>

                            {{-- Content --}}
                            <div class="list-single-main-item fl-wrap block_box">
                                <div class="single-article-header fl-wrap">
                                    <h2 class="post-opt-title">
                                        {!! \Illuminate\Support\Str::limit(htmlspecialchars_decode($blog->details ?? ''), 150) !!}
                                    </h2>

                                    <span class="fw-separator"></span>
                                    <div class="clearfix"></div>

                                    @php
                                        $authorName = $blog->user->name ?? 'Admin';
                                        $authorImg = $blog->user->profile_photo ?? 'images/21.jpg';
                                    @endphp

                                    <div class="post-author">
                                        <a href="{{ route('webite-blog') }}">
                                            <img src="{{ asset($authorImg) }}" alt="{{ $authorName }}">
                                            <span>By {{ $authorName }}</span>
                                        </a>
                                    </div>

                                    <div class="post-opt">
                                        <ul class="no-list-style">
                                            <li>
                                                <i class="fal fa-calendar"></i>
                                                <span>{{ optional($blog->created_at)->format('d M Y') ?? '—' }}</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <span class="fw-separator fl-wrap"></span>

                                {{-- Blog Body (fallback) --}}
                                <div class="blog-content">
                                    {!! htmlspecialchars_decode($blog->details ?? '<p>No details available for this post yet.</p>') !!}
                                </div>
                            </div>
                        </article>
                        {{-- OPTIONAL: Related Posts --}}
                        @if (isset($relatedBlogs) && $relatedBlogs->count())
                            <div class="list-single-main-container fl-wrap" style="margin-top: 30px;">
                                <div class="list-single-main-item fl-wrap">
                                    <div class="list-single-main-item-title">
                                        <h3>Related Blogs</h3>
                                    </div>

                                    <div class="box-widget-content fl-wrap">
                                        <div class="widget-posts fl-wrap">
                                            <ul class="no-list-style">
                                                @foreach ($relatedBlogs as $rp)
                                                    @php
                                                        // ✅ Clean text (no HTML)
                                                        $plain = strip_tags(
                                                            htmlspecialchars_decode($rp->details ?? ''),
                                                        );
                                                        $short = \Illuminate\Support\Str::limit($plain, 70);

                                                        // ✅ Image fallback
                                                        $img = $rp->image
                                                            ? asset($rp->image)
                                                            : asset('images/default-blog.jpg');

                                                        // ✅ Single page link
                                                        $url = route('webite-blog-single', $rp->id);
                                                    @endphp

                                                    <li>
                                                        <div class="widget-posts-img">
                                                            <a href="{{ $url }}">
                                                                <img src="{{ $img }}" alt="Related blog">
                                                            </a>
                                                        </div>

                                                        <div class="widget-posts-descr">
                                                            <h4 style="margin:0;">
                                                                <a href="{{ $url }}">
                                                                    {{ $short }}
                                                                </a>
                                                            </h4>

                                                            <div class="geodir-category-location fl-wrap">
                                                                <a href="{{ $url }}">
                                                                    <i class="fal fa-calendar"></i>
                                                                    {{ optional($rp->created_at)->format('d M Y') }}
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>

                                        <a href="{{ route('webite-blog') }}" class="btn float-btn color-bg small-btn"
                                            style="width:100%; text-align:center;">
                                            Browse blogs
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Comments part (abhi static hai) --}}
                        {{-- Agar chaho to comments table bana ke main isko bhi fully dynamic kar dunga --}}
                    </div>
                </div>

                {{-- RIGHT: Sidebar --}}
                <div class="col-md-4">
                    <div class="box-widget-wrap fl-wrap fixed-bar">

                        {{-- Search --}}
                        {{-- <div class="box-widget fl-wrap">
                            <div class="search-widget fl-wrap">
                                <form action="#" method="GET" class="fl-wrap custom-form">
                                    <input name="q" id="se" type="text" class="search"
                                        placeholder="Search.." value="{{ request('q') }}" />
                                    <button class="search-submit" id="submit_btn"><i class="far fa-search"></i></button>
                                </form>
                            </div>
                        </div> --}}

                        {{-- Popular Posts --}}
                        <div class="box-widget fl-wrap">
                            <div class="box-widget-title fl-wrap">Popular Blogs</div>

                            <div class="box-widget-content fl-wrap">
                                <div class="widget-posts fl-wrap">
                                    <ul class="no-list-style">
                                        @foreach ($popularPosts as $p)
                                            @php
                                                // ✅ clean text for sidebar (no HTML tags)
                                                $plain = strip_tags(htmlspecialchars_decode($p->details ?? ''));
                                                $short = \Illuminate\Support\Str::limit($plain, 80);

                                                // ✅ image fallback
                                                $img = $p->image ? asset($p->image) : asset('images/default-blog.jpg');

                                                // ✅ blog link
                                                $url = route('webite-blog-single', $p->id);
                                            @endphp

                                            <li>
                                                <div class="widget-posts-img">
                                                    <a href="{{ $url }}">
                                                        <img src="{{ $img }}" alt="Blog">
                                                    </a>
                                                </div>

                                                <div class="widget-posts-descr">
                                                    <h4 style="margin:0;">
                                                        <a href="{{ $url }}">
                                                            {{ $short }}
                                                        </a>
                                                    </h4>

                                                    <div class="geodir-category-location fl-wrap">
                                                        <a href="{{ $url }}">
                                                            <i class="fal fa-calendar"></i>
                                                            {{ optional($p->created_at)->format('d M Y') }}
                                                        </a>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>

                                <a href="{{ route('webite-blog') }}" class="btn float-btn color-bg small-btn"
                                    style="width:100%; text-align:center;">
                                    Browse blogs
                                </a>
                            </div>
                        </div>

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
                                            <a href="#">
                                                {{ $fullDate }} ({{ $a->total }})
                                            </a>
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
@endsection
