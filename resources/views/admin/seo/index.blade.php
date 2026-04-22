@extends('dashboard')

@section('title')
    <title>SEO Dashboard | Mergersales</title>
@endsection

@section('content')
    <div class="row g-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h1 style="font-size:1.6rem;font-weight:700;">SEO Manager Dashboard</h1>
                    <p class="text-muted mb-0">Yahan se page SEO, listing SEO, blog SEO, sitemap aur schema manage hoga.</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">Pages</div>
                    <div style="font-size:2rem;font-weight:700;">{{ $stats['pages'] }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">Listings</div>
                    <div style="font-size:2rem;font-weight:700;">{{ $stats['listings'] }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">Blogs</div>
                    <div style="font-size:2rem;font-weight:700;">{{ $stats['blogs'] }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">Custom Schemas</div>
                    <div style="font-size:2rem;font-weight:700;">{{ $stats['schemas'] }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <a href="{{ route('admin.seo.pages') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h3 style="font-size:1.1rem;font-weight:700;">Manage Page SEO</h3>
                        <p class="text-muted mb-0">Meta title, description, canonical aur robots manage karein.</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4">
            <a href="{{ route('admin.seo.listings') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h3 style="font-size:1.1rem;font-weight:700;">Manage Listing SEO</h3>
                        <p class="text-muted mb-0">Slug, keyword, OG image aur schema JSON update karein.</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4">
            <a href="{{ route('admin.seo.blogs') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h3 style="font-size:1.1rem;font-weight:700;">Manage Blog SEO</h3>
                        <p class="text-muted mb-0">Blog slug, SEO title, description aur image alt text update karein.</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
@endsection
