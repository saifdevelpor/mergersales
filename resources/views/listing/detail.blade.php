@extends('dashboard')

@section('title')
    <title>Business Details - {{ $listing->deal_type }} | Mergersales</title>
@endsection

@section('content')
    @php
        // ✅ single storage base (NO asset, NO storage/app/public in URL)
        $storageBase = rtrim(url('storage/app/public/'), '/') . '/';

        // helpers
        $extOf = function ($path) {
            return strtolower(pathinfo($path ?? '', PATHINFO_EXTENSION));
        };
        $isImageExt = function ($ext) {
            return in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']);
        };
        $isPdfExt = function ($ext) {
            return $ext === 'pdf';
        };

        // main business file
        $businessFile = $listing->business_img ?? null;
        $businessExt = $extOf($businessFile);
        $businessUrl = $businessFile ? $storageBase . ltrim($businessFile, '/') : null;

        // teaser
        $teaserFile = $listing->teaser_path ?? null;
        $teaserExt = $extOf($teaserFile);
        $teaserUrl = $teaserFile ? $storageBase . ltrim($teaserFile, '/') : null;

        // im
        $imFile = $listing->im_path ?? null;
        $imExt = $extOf($imFile);
        $imUrl = $imFile ? $storageBase . ltrim($imFile, '/') : null;
    @endphp

    <div class="container py-4">

        <!-- HERO -->
        <div class="card border-0 shadow-sm hero-card mb-4 overflow-hidden">
            <div class="hero-top p-4">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <div>
                        <div class="hero-kicker">Business Details</div>

                        @if (!empty($listing->business_name))
                            <h2 class="hero-title mb-1">{{ $listing->business_name }}</h2>
                        @else
                            <h2 class="hero-title mb-1 text-muted">Business name not available</h2>
                        @endif

                        <div class="hero-sub d-flex flex-wrap gap-3 align-items-center">
                            <span class="d-inline-flex align-items-center gap-1">
                                <i class="ti ti-briefcase"></i> {{ $listing->deal_type }}
                            </span>
                            <span class="d-inline-flex align-items-center gap-1">
                                <i class="ti ti-map-pin"></i> {{ $listing->country ?? 'N/A' }}
                            </span>
                        </div>

                        <div class="mt-3 d-flex flex-wrap gap-2">
                            <span class="chip">
                                <i class="ti ti-building-store me-1"></i>
                                {{ $listing->industry->name ?? 'N/A' }}
                            </span>
                            <span class="chip chip-soft">
                                <i class="ti ti-layers me-1"></i>
                                {{ $listing->subIndustry->name ?? 'N/A' }}
                            </span>
                        </div>
                    </div>

                    <div class="text-center text-md-end">
                        @if ($businessUrl)
                            <a href="{{ $businessUrl }}" target="_blank" class="d-inline-block text-decoration-none">
                                @if ($isImageExt($businessExt))
                                    <img src="{{ $businessUrl }}" class="hero-image shadow-sm"
                                        alt="{{ $listing->business_name ?? 'Business Image' }}">
                                @elseif ($isPdfExt($businessExt))
                                    <div class="file-preview file-preview-pdf">
                                        <i class="ti ti-file-type-pdf"></i>
                                        <div class="fw-semibold">View Business PDF</div>
                                        <div class="small opacity-75">Open in new tab</div>
                                    </div>
                                @else
                                    <div class="file-preview">
                                        <i class="ti ti-file"></i>
                                        <div class="fw-semibold">View File</div>
                                        <div class="small opacity-75">Open in new tab</div>
                                    </div>
                                @endif
                            </a>
                        @else
                            <div class="file-empty">
                                <i class="ti ti-photo-off"></i>
                                <div class="fw-semibold">No business file uploaded</div>
                                <div class="small text-muted">Image/PDF will appear here</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="hero-bottom p-3 px-4 d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div class="text-muted small">
                    <i class="ti ti-clock me-1"></i>
                    Posted: {{ $listing->created_at ? $listing->created_at->format('d M Y, h:i A') : 'N/A' }}
                </div>

                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('listings.index') }}" class="btn btn-light btn-sm px-3">
                        <i class="ti ti-arrow-left me-1"></i> Back
                    </a>
                    @if ($businessUrl)
                        <a href="{{ $businessUrl }}" target="_blank" class="btn btn-outline-light btn-sm px-3">
                            <i class="ti ti-external-link me-1"></i> Open File
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- STATS -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm stat-card h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="stat-badge">
                            <i class="ti ti-currency-dollar"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Revenue</div>
                            <div class="stat-value">{{ $listing->currency }} {{ $listing->revenue_range }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm stat-card h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="stat-badge">
                            <i class="ti ti-chart-bar"></i>
                        </div>
                        <div>
                            <div class="text-muted small">EBITDA</div>
                            <div class="stat-value">{{ $listing->currency }} {{ $listing->ebitda_range }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm stat-card h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="stat-badge">
                            <i class="ti ti-users"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Employees</div>
                            <div class="stat-value">{{ $listing->employee_range }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- OVERVIEW -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-bold d-flex align-items-center gap-2">
                <i class="ti ti-info-circle"></i> Business Overview
            </div>

            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="text-muted small">Deal Type</div>
                        <div class="fw-semibold">{{ $listing->deal_type }}</div>
                    </div>

                    <div class="col-md-8">
                        <div class="text-muted small">Reason for Sale</div>
                        <div class="overview-box">
                            {{ $listing->reason_for_sale ?? 'N/A' }}
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="text-muted small">Description</div>
                        <div class="overview-box">
                            {{ $listing->description ?? 'N/A' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- DOCUMENTS -->
        <div class="row g-3 mb-4">
            <!-- TEASER -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white fw-bold d-flex align-items-center justify-content-between">
                        <span>Teaser</span>
                        @if ($teaserUrl)
                            <a href="{{ $teaserUrl }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="ti ti-external-link me-1"></i> Open
                            </a>
                        @endif
                    </div>

                    <div class="card-body">
                        @if ($teaserUrl)
                            <a href="{{ $teaserUrl }}" target="_blank" class="doc-tile text-decoration-none">
                                @if ($isImageExt($teaserExt))
                                    <img src="{{ $teaserUrl }}" class="doc-img" alt="Teaser">
                                @elseif ($isPdfExt($teaserExt))
                                    <div class="doc-file doc-file-pdf">
                                        <i class="ti ti-file-type-pdf"></i>
                                        <div class="fw-semibold">View Teaser PDF</div>
                                        <div class="small opacity-75">Open in new tab</div>
                                    </div>
                                @else
                                    <div class="doc-file">
                                        <i class="ti ti-file"></i>
                                        <div class="fw-semibold">View File</div>
                                        <div class="small opacity-75">Open in new tab</div>
                                    </div>
                                @endif
                            </a>
                        @else
                            <div class="doc-empty">
                                <i class="ti ti-file-off"></i>
                                <div class="fw-semibold">No teaser uploaded</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- IM -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white fw-bold d-flex align-items-center justify-content-between">
                        <span>Information Memorandum (IM)</span>
                        @if ($imUrl)
                            <a href="{{ $imUrl }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="ti ti-external-link me-1"></i> Open
                            </a>
                        @endif
                    </div>

                    <div class="card-body">
                        @if ($imUrl)
                            <a href="{{ $imUrl }}" target="_blank" class="doc-tile text-decoration-none">
                                @if ($isImageExt($imExt))
                                    <img src="{{ $imUrl }}" class="doc-img" alt="IM">
                                @elseif ($isPdfExt($imExt))
                                    <div class="doc-file doc-file-pdf">
                                        <i class="ti ti-file-type-pdf"></i>
                                        <div class="fw-semibold">View IM PDF</div>
                                        <div class="small opacity-75">Open in new tab</div>
                                    </div>
                                @else
                                    <div class="doc-file">
                                        <i class="ti ti-file"></i>
                                        <div class="fw-semibold">View File</div>
                                        <div class="small opacity-75">Open in new tab</div>
                                    </div>
                                @endif
                            </a>
                        @else
                            <div class="doc-empty">
                                <i class="ti ti-file-off"></i>
                                <div class="fw-semibold">No IM uploaded</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- ACTION -->
        <a href="{{ route('listings.index') }}" class="btn btn-warning text-white px-4">
            <i class="ti ti-arrow-left me-1"></i> Back to Business
        </a>

    </div>

    <style>
        /* HERO */
        .hero-card {
            border-radius: 18px;
        }

        .hero-title {
            font-weight: 800;
        }

        /* IMAGE/PDF PREVIEW */
        .hero-image {
            width: 320px;
            max-width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, .25);
        }

        .file-preview {
            width: 320px;
            max-width: 100%;
            height: 200px;
            border-radius: 16px;
            border: 1px dashed rgba(255, 255, 255, .35);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 6px;
            color: #fff;
            background: rgba(255, 255, 255, .08);
        }

        .file-preview i {
            font-size: 46px;
        }

        .file-preview-pdf {
            border-style: solid;
            background: rgba(220, 53, 69, .18);
            border-color: rgba(255, 255, 255, .25);
        }

        .file-empty {
            width: 320px;
            max-width: 100%;
            padding: 18px;
            border-radius: 16px;
            border: 1px dashed rgba(255, 255, 255, .35);
            color: #fff;
            background: rgba(255, 255, 255, .08);
            display: inline-flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
        }

        .file-empty i {
            font-size: 40px;
            opacity: .9;
        }

        /* STATS */
        .stat-card {
            border-radius: 16px;
            transition: .2s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
        }

        .stat-badge {
            width: 46px;
            height: 46px;
            border-radius: 14px;
            display: grid;
            place-items: center;
            background: rgba(13, 110, 253, .10);
            color: #0d6efd;
            font-size: 22px;
        }

        .stat-value {
            font-weight: 800;
            font-size: 18px;
        }

        /* OVERVIEW */
        .overview-box {
            background: #f8fafc;
            border: 1px solid #eef2f6;
            border-radius: 14px;
            padding: 14px;
            color: #1f2937;
        }

        /* DOCUMENTS */
        .doc-tile {
            display: block;
        }

        .doc-img {
            width: 100%;
            height: 240px;
            object-fit: cover;
            border-radius: 16px;
            border: 1px solid #eef2f6;
        }

        .doc-file {
            width: 100%;
            height: 240px;
            border-radius: 16px;
            border: 1px dashed #cfd8e3;
            background: #f8fafc;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 6px;
            color: #0f172a;
            text-align: center;
        }

        .doc-file i {
            font-size: 52px;
            color: #0d6efd;
        }

        .doc-file-pdf {
            border-style: solid;
            border-color: rgba(220, 53, 69, .25);
            background: rgba(220, 53, 69, .06);
        }

        .doc-file-pdf i {
            color: #dc3545;
        }

        .doc-empty {
            width: 100%;
            height: 240px;
            border-radius: 16px;
            border: 1px dashed #cfd8e3;
            background: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 6px;
            color: #64748b;
        }

        .doc-empty i {
            font-size: 44px;
        }

        .card {
            border-radius: 16px;
        }

        .card-header {
            border-bottom: 1px solid #f0f0f0;
        }
    </style>
@endsection
