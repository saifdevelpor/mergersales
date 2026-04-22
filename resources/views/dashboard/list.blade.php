@extends('dashboard')

@section('title')
<title>DashBoard | Mergersales</title>
@endsection

@section('content')
<style>
:root {
    --brand: #CCAA57;
    --brand2: #b88e2b;
    --text: #101828;
    --muted: #667085;
    --border: #eef0f4;
    --bg: #ffffff;
    --soft: #f8fafc;
    --shadow: 0 18px 45px rgba(16, 24, 40, .08);
    --shadow2: 0 10px 28px rgba(16, 24, 40, .06);
}

/* ====== PAGE BACKGROUND (PURE WHITE) ====== */
.dash-page {
    position: relative;
    overflow: hidden;
    border-radius: 18px;
    background: #ffffff;
    border: 1px solid var(--border);
}

.dash-wrap {
    padding: 18px;
    position: relative;
    z-index: 1;
}

/* ====== HEADER / HERO ====== */
.dash-hero {
    border: 1px solid var(--border);
    border-radius: 20px;
    padding: 16px 16px;
    background: #ffffff;
    box-shadow: var(--shadow2);
    position: relative;
    overflow: hidden;
}

/* ====== GRAPHIC PANEL ====== */
.graphic-panel {
    border: 1px solid var(--border);
    border-radius: 20px;
    background: #fff;
    box-shadow: var(--shadow2);
    overflow: hidden;
}

.graphic-panel .gp-head {
    padding: 14px 16px;
    border-bottom: 1px solid var(--border);
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
    background: #fff;
}

.graphic-panel .gp-title {
    margin: 0;
    font-weight: 950;
    color: var(--text);
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
}

.graphic-panel .gp-sub {
    margin: 0;
    color: var(--muted);
    font-weight: 700;
    font-size: 12px;
}

.graphic-panel .gp-body {
    padding: 12px 16px 16px;
}

.kpi-row {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 10px;
    margin-bottom: 12px;
}

.kpi {
    border: 1px solid var(--border);
    border-radius: 14px;
    padding: 10px 12px;
    background: #fff;
}

.kpi .k {
    font-size: 12px;
    color: var(--muted);
    font-weight: 900;
    margin: 0;
}

.kpi .v {
    font-size: 18px;
    font-weight: 950;
    color: var(--text);
    margin: 4px 0 0;
}

.spark {
    width: 100%;
    height: 130px;
    border-radius: 16px;
    border: 1px solid var(--border);
    background: #fff;
}

@media (max-width: 992px) {
    .kpi-row {
        grid-template-columns: 1fr;
    }
}

.dash-hero .pattern {
    position: absolute;
    inset: -20px -30px auto auto;
    width: 360px;
    height: 180px;
    opacity: .12;
    transform: rotate(-8deg);
    pointer-events: none;
    background: transparent;
}

.welcome-note {
    margin-top: 10px;
    padding-top: 10px;
    border-top: 1px dashed var(--border);
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
}

.welcome-note p {
    margin: 0;
    color: var(--muted);
    font-weight: 700;
    font-size: 13px;
    line-height: 1.45;
}

.welcome-actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    justify-content: flex-end;
}

.btn-ghost {
    border: 1px solid var(--border);
    background: #fff;
    color: var(--text);
    font-weight: 850;
}

.btn-ghost:hover {
    background: #fff;
    border-color: rgba(16, 24, 40, .18);
    color: var(--text);
}

/* Bigger welcome */
.dash-title {
    font-size: 28px;
    font-weight: 950;
}

.dash-subtitle {
    font-size: 15px;
    font-weight: 750;
}

.dash-title .title-icon {
    width: 48px;
    height: 48px;
    border-radius: 16px;
}

/* Graphics layout row */
.graphics-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 12px;
}

@media (max-width: 992px) {
    .graphics-grid {
        grid-template-columns: 1fr;
    }
}

/* Bar chart svg wrapper */
.bars {
    width: 100%;
    height: 210px;
    border-radius: 16px;
    border: 1px solid var(--border);
    background: #fff;
}

/* 6-month trend chart (line + area like screenshot) */
.month6 {
    width: 100%;
    height: 280px;
    border-radius: 16px;
    border: 1px solid var(--border);
    background: #fff;
}

        /* Mini charts (different style) */
        .mini-chart {
            width: 100%;
            height: 150px;
            border-radius: 14px;
            border: 1px solid var(--border);
            background: #fff;
        }

        .mini-row {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }

        @media (max-width: 992px) {
            .mini-row {
                grid-template-columns: 1fr;
            }
        }

.chart-legend {
    display: flex;
    gap: 14px;
    flex-wrap: wrap;
    align-items: center;
    justify-content: center;
    margin-top: 10px;
    color: var(--muted);
    font-weight: 850;
    font-size: 12px;
}

.chart-key {
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.chart-swatch {
    width: 38px;
    height: 12px;
    border-radius: 3px;
    background: #CCAA57;
    opacity: 0.95;
}

.dash-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 0;
}

.dash-title {
    font-weight: 950;
    font-size: 20px;
    margin: 0;
    color: var(--text);
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

.dash-title .title-icon {
    width: 40px;
    height: 40px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(204, 170, 87, .14);
    border: 1px solid rgba(204, 170, 87, .22);
    color: var(--text);
}

.dash-subtitle {
    margin: 4px 0 0;
    color: var(--muted);
    font-size: 13px;
    font-weight: 650;
}

.dash-chips {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    align-items: center;
    justify-content: flex-end;
}

.chip {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 10px;
    border-radius: 999px;
    border: 1px solid var(--border);
    background: #fff;
    box-shadow: 0 6px 18px rgba(16, 24, 40, .05);
    font-weight: 850;
    color: #344054;
    font-size: 12px;
    white-space: nowrap;
}

.chip i {
    color: var(--brand2);
}

/* ====== SECTION TITLE ====== */
.section-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin: 18px 0 10px;
    gap: 10px;
    flex-wrap: wrap;
}

.section-head h5 {
    margin: 0;
    font-weight: 950;
    font-size: 14px;
    color: var(--text);
    display: flex;
    align-items: center;
    gap: 8px;
}

.section-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: var(--brand);
    box-shadow: 0 0 0 5px rgba(204, 170, 87, .12);
    display: inline-block;
}

/* ====== STATS CARDS (with graphics) ====== */
.stat-card {
    position: relative;
    border: 1px solid var(--border);
    border-radius: 18px;
    padding: 16px;
    background: var(--bg);
    box-shadow: var(--shadow2);
    height: 100%;
    transition: .18s ease-in-out;
    overflow: hidden;
}

.stat-card:before {
    content: none;
}

.stat-card:after {
    content: none;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 22px 52px rgba(16, 24, 40, .12);
    border-color: rgba(204, 170, 87, .35);
}

.stat-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 10px;
    position: relative;
    z-index: 1;
}

.stat-icon {
    width: 44px;
    height: 44px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #ffffff;
    border: 1px solid #eef2f6;
    color: var(--text);
    box-shadow: 0 10px 24px rgba(16, 24, 40, .08);
    flex: 0 0 auto;
}

.stat-label {
    color: var(--muted);
    font-size: 12px;
    font-weight: 900;
    margin: 0;
    position: relative;
    z-index: 1;
}

.stat-value {
    font-size: 28px;
    font-weight: 950;
    line-height: 1.1;
    color: var(--text);
    margin: 0;
    position: relative;
    z-index: 1;
}

.stat-sub {
    color: #98a2b3;
    font-size: 12px;
    margin-top: 6px;
    position: relative;
    z-index: 1;
}

/* ====== TABLE ====== */
.table-wrap {
    border: 1px solid var(--border);
    border-radius: 18px;
    overflow: hidden;
    background: var(--bg);
    box-shadow: var(--shadow2);
}

.card-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    flex-wrap: wrap;
    margin-bottom: 0;
    background: #fff;
    border-bottom: 1px solid var(--border);
}

.card-head h5 {
    margin: 0;
    font-weight: 950;
    font-size: 15px;
    color: var(--text);
    display: flex;
    align-items: center;
    gap: 8px;
}

.table thead th {
    background: var(--soft);
    font-size: 12px;
    color: var(--muted);
    border-bottom: 1px solid var(--border) !important;
    padding: 12px 14px !important;
    white-space: nowrap;
}

.table td {
    padding: 12px 14px !important;
    border-bottom: 1px solid #f2f4f7 !important;
    vertical-align: middle;
}

.table tbody tr:hover {
    background: #fafcff;
}

.badge-soft {
    padding: 6px 10px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 950;
    display: inline-block;
    white-space: nowrap;
}

.b-pending {
    background: #fff7ed;
    color: #c2410c;
}

.b-approved {
    background: #ecfdf3;
    color: #027a48;
}

.b-rejected {
    background: #fef2f2;
    color: #b42318;
}

.thumb {
    width: 72px;
    height: 52px;
    object-fit: cover;
    border-radius: 12px;
    border: 1px solid var(--border);
    background: #f2f4f7;
}

/* ====== NOTIFICATIONS (modern) ====== */
.notification-box {
    max-width: 100%;
    border: 1px solid var(--border) !important;
    border-radius: 18px;
    background: #fff;
    box-shadow: var(--shadow2);
    position: relative;
    overflow: hidden;
}

.notification-box:after {
    content: none;
}

.notification-item {
    position: relative;
    transition: .14s ease;
}

.notification-item:hover {
    background: rgba(248, 250, 252, .8);
    cursor: pointer;
}

/* ====== QUICK ACTION BUTTONS ====== */
.btn-brand {
    background: var(--brand);
    border-color: var(--brand);
    color: #fff;
    font-weight: 900;
}

.btn-brand:hover {
    filter: brightness(.95);
    color: black;
}

/* Mobile tweaks */
@media (max-width:576px) {
    .dash-wrap {
        padding: 12px;
    }

    .stat-value {
        font-size: 24px;
    }

    .thumb {
        width: 62px;
        height: 46px;
    }
}
</style>

<div class="card dash-page">
    <div class="dash-wrap">

        {{-- HERO HEADER --}}
        <div class="dash-hero mb-3">
            <div class="pattern"></div>

            <div class="dash-header">
                <div>
                    <h1 class="dash-title">
                        <span class="title-icon"><i class="ti ti-layout-dashboard"></i></span>
                        Dashboard
                    </h1>
                    <p class="dash-subtitle">
                        Welcome, <strong>{{ auth()->user()->name }}</strong>
                    </p>
                </div>

                <div class="dash-chips">
                    <span class="chip"><i class="ti ti-user"></i> Role:
                        {{ $role ?? (auth()->user()->role ?? '—') }}</span>
                    <span class="chip"><i class="ti ti-calendar-event"></i> {{ now()->format('d M, Y') }}</span>
                </div>
            </div>

            @php
            $roleLabel = $role ?? (auth()->user()->role ?? '—');
            $welcomeLine = 'Manage your account and track activity in one place.';
            if ($roleLabel === 'Admin') {
            $welcomeLine = 'Monitor users, listings, and approvals from one dashboard.';
            } elseif ($roleLabel === 'seo_manager') {
            $welcomeLine = 'Manage search visibility, metadata, slugs, schemas, and sitemap health from one place.';
            } elseif ($roleLabel === 'Seller') {
            $welcomeLine = 'Track your listings, enquiries, and approvals from one place.';
            } elseif ($roleLabel === 'Buyer') {
            $welcomeLine = 'Track your enquiries, approvals, and updates quickly.';
            }
            @endphp

            <div class="welcome-note">
                <div>
                    <p>
                        {{ $welcomeLine }}
                        @if (($roleLabel === 'Admin') && isset($total_users, $total_businesses))
                        Today snapshot: <strong>{{ number_format($total_users) }}</strong> users and
                        <strong>{{ number_format($total_businesses) }}</strong> businesses in system.
                        @elseif(($roleLabel === 'seo_manager') && isset($seo_total_pages, $seo_total_listings))
                        Today snapshot: <strong>{{ number_format($seo_total_pages) }}</strong> SEO pages and
                        <strong>{{ number_format($seo_total_listings) }}</strong> listing records ready for optimization.
                        @elseif(($roleLabel === 'Seller') && isset($my_total_businesses, $total_enquiries))
                        Today snapshot: <strong>{{ number_format($my_total_businesses) }}</strong> listings and
                        <strong>{{ number_format($total_enquiries) }}</strong> enquiries.
                        @elseif(($roleLabel === 'Buyer') && isset($buyer_total_enquiries))
                        Today snapshot: <strong>{{ number_format($buyer_total_enquiries) }}</strong> total enquiries.
                        @endif
                    </p>
                </div>
                <div class="welcome-actions">
                    <a class="btn btn-sm btn-ghost" href="{{ route('profile') }}"><i class="ti ti-user me-1"></i>
                        Profile</a>
                    @if ($roleLabel === 'Admin')
                    <a class="btn btn-sm btn-ghost" href="{{ route('user-management') }}"><i
                            class="ti ti-users me-1"></i> Users</a>
                    <a class="btn btn-sm btn-brand" href="{{ route('listings.index') }}"><i
                            class="ti ti-briefcase me-1"></i> Listings</a>
                    @elseif($roleLabel === 'seo_manager')
                    <a class="btn btn-sm btn-ghost" href="{{ route('admin.seo.pages') }}"><i
                            class="ti ti-file-text me-1"></i> Pages</a>
                    <a class="btn btn-sm btn-brand" href="{{ route('admin.seo.index') }}"><i
                            class="ti ti-world-search me-1"></i> SEO Panel</a>
                    @elseif($roleLabel === 'Seller')
                    <a class="btn btn-sm btn-ghost" href="{{ route('listings.approved') }}"><i
                            class="ti ti-circle-check me-1"></i> Approved</a>
                    <a class="btn btn-sm btn-brand" href="{{ route('listings.index') }}"><i class="ti ti-plus me-1"></i>
                        My Listings</a>
                    @else
                    <a class="btn btn-sm btn-ghost" href="{{ route('tickets.index') }}"><i
                            class="ti ti-life-buoy me-1"></i> Tickets</a>
                    <a class="btn btn-sm btn-brand" href="{{ route('webite-business') }}"><i
                            class="ti ti-search me-1"></i> Browse</a>
                    @endif
                </div>
            </div>
        </div>

        {{-- GRAPHIC + WELCOME NOTE (simple white background) --}}
        @php
        $kpiA = 0;
        $kpiB = 0;
        $kpiC = 0;

        if (($role ?? '') === 'Admin') {
        $kpiA = (int) ($total_businesses ?? 0);
        $kpiB = (int) ($approved_businesses ?? 0);
        $kpiC = (int) ($pending_businesses ?? 0);
        } elseif (($role ?? '') === 'seo_manager') {
        $kpiA = (int) ($seo_total_pages ?? 0);
        $kpiB = (int) ($seo_listing_slugs ?? 0);
        $kpiC = (int) ($seo_blog_slugs ?? 0);
        } elseif (($role ?? '') === 'Seller') {
        $kpiA = (int) ($my_total_businesses ?? 0);
        $kpiB = (int) ($my_approved ?? 0);
        $kpiC = (int) ($total_enquiries ?? 0);
        } else {
        $kpiA = (int) ($buyer_total_enquiries ?? 0);
        $kpiB = (int) ($buyer_approved_enquiries ?? 0);
        $kpiC = (int) ($buyer_pending_enquiries ?? 0);
        }
        @endphp

        @php
        // Status bars (role-wise)
        $barApproved = (int) ($approved_businesses ?? $my_approved ?? $buyer_approved_enquiries ?? 0);
        $barPending = (int) ($pending_businesses ?? $my_pending ?? $buyer_pending_enquiries ?? 0);
        $barRejected = (int) ($rejected_businesses ?? $my_rejected ?? $buyer_rejected_enquiries ?? 0);
        if (($role ?? '') === 'seo_manager') {
        $barApproved = (int) ($seo_listing_slugs ?? 0);
        $barPending = (int) ($seo_blog_slugs ?? 0);
        $barRejected = (int) ($seo_custom_schema_count ?? 0);
        }
        $barTotal = max(1, $barApproved + $barPending + $barRejected);

        $hApproved = (int) round(160 * ($barApproved / $barTotal));
        $hPending = (int) round(160 * ($barPending / $barTotal));
        $hRejected = (int) round(160 * ($barRejected / $barTotal));
        @endphp

        <div class="graphics-grid mb-3">
            <div class="graphic-panel">
                <div class="gp-head">
                    <div>
                        <p class="gp-title"><i class="ti ti-chart-line" style="color:var(--brand2)"></i> Activity Trend
                        </p>
                        <p class="gp-sub">Simple visual — lightweight graphic</p>
                    </div>
                    <span class="chip"><i class="ti ti-sparkles"></i> Welcome back</span>
                </div>

                <div class="gp-body">
                    <div class="kpi-row">
                        <div class="kpi">
                            <p class="k">Total</p>
                            <p class="v">{{ number_format($kpiA) }}</p>
                        </div>
                        <div class="kpi">
                            <p class="k">Approved / Completed</p>
                            <p class="v">{{ number_format($kpiB) }}</p>
                        </div>
                        <div class="kpi">
                            <p class="k">Pending</p>
                            <p class="v">{{ number_format($kpiC) }}</p>
                        </div>
                    </div>

                    <svg class="spark" viewBox="0 0 600 130" preserveAspectRatio="none" aria-hidden="true">
                        <defs>
                            <linearGradient id="msLine" x1="0" y1="0" x2="1" y2="0">
                                <stop offset="0" stop-color="#CCAA57" stop-opacity="1" />
                                <stop offset="1" stop-color="#3A6EF2" stop-opacity="1" />
                            </linearGradient>
                            <linearGradient id="msFill" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0" stop-color="#CCAA57" stop-opacity="0.18" />
                                <stop offset="1" stop-color="#3A6EF2" stop-opacity="0.04" />
                            </linearGradient>
                        </defs>

                        <g opacity="0.18" stroke="#101828" stroke-width="1">
                            <path d="M0 105 H600" />
                            <path d="M0 75 H600" />
                            <path d="M0 45 H600" />
                        </g>

                        <path
                            d="M0,100 C70,88 110,92 160,70 C220,44 260,82 310,60 C360,38 420,62 470,42 C520,26 560,32 600,22 L600,130 L0,130 Z"
                            fill="url(#msFill)" />
                        <path
                            d="M0,100 C70,88 110,92 160,70 C220,44 260,82 310,60 C360,38 420,62 470,42 C520,26 560,32 600,22"
                            fill="none" stroke="url(#msLine)" stroke-width="3.5" />

                        <g fill="#fff" stroke="#CCAA57" stroke-width="2">
                            <circle cx="160" cy="70" r="5" />
                            <circle cx="310" cy="60" r="5" />
                            <circle cx="470" cy="42" r="5" />
                            <circle cx="600" cy="22" r="5" />
                        </g>
                    </svg>
                </div>
            </div>

            <div class="graphic-panel">
                <div class="gp-head">
                    <div>
                        <p class="gp-title"><i class="ti ti-chart-bar" style="color:var(--brand2)"></i> Status Overview
                        </p>
                        <p class="gp-sub">Approved / Pending / Rejected</p>
                    </div>
                    <span class="chip"><i class="ti ti-info-circle"></i> Total: {{ number_format($barTotal) }}</span>
                </div>

                <div class="gp-body">
                    <div class="kpi-row">
                        <div class="kpi">
                            <p class="k">Approved</p>
                            <p class="v">{{ number_format($barApproved) }}</p>
                        </div>
                        <div class="kpi">
                            <p class="k">Pending</p>
                            <p class="v">{{ number_format($barPending) }}</p>
                        </div>
                        <div class="kpi">
                            <p class="k">Rejected</p>
                            <p class="v">{{ number_format($barRejected) }}</p>
                        </div>
                    </div>

                    <svg class="bars" viewBox="0 0 600 210" preserveAspectRatio="none" aria-hidden="true">
                        <defs>
                            <linearGradient id="bA" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0" stop-color="#CCAA57" stop-opacity="0.95" />
                                <stop offset="1" stop-color="#CCAA57" stop-opacity="0.55" />
                            </linearGradient>
                            <linearGradient id="bP" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0" stop-color="#3A6EF2" stop-opacity="0.85" />
                                <stop offset="1" stop-color="#3A6EF2" stop-opacity="0.45" />
                            </linearGradient>
                            <linearGradient id="bR" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0" stop-color="#E11D48" stop-opacity="0.75" />
                                <stop offset="1" stop-color="#E11D48" stop-opacity="0.38" />
                            </linearGradient>
                        </defs>

                        <g opacity="0.18" stroke="#101828" stroke-width="1">
                            <path d="M60 170 H560" />
                            <path d="M60 120 H560" />
                            <path d="M60 70 H560" />
                        </g>

                        @php
                        $baseY = 170;
                        $barW = 90;
                        $gap = 70;
                        $x1 = 150;
                        $x2 = $x1 + $barW + $gap;
                        $x3 = $x2 + $barW + $gap;
                        @endphp

                        <rect x="{{ $x1 }}" y="{{ $baseY - $hApproved }}" width="{{ $barW }}" height="{{ $hApproved }}"
                            rx="14" fill="url(#bA)" />
                        <rect x="{{ $x2 }}" y="{{ $baseY - $hPending }}" width="{{ $barW }}" height="{{ $hPending }}"
                            rx="14" fill="url(#bP)" />
                        <rect x="{{ $x3 }}" y="{{ $baseY - $hRejected }}" width="{{ $barW }}" height="{{ $hRejected }}"
                            rx="14" fill="url(#bR)" />

                        <text x="{{ $x1 + ($barW/2) }}" y="195" text-anchor="middle" font-size="12" fill="#667085"
                            font-weight="800">Approved</text>
                        <text x="{{ $x2 + ($barW/2) }}" y="195" text-anchor="middle" font-size="12" fill="#667085"
                            font-weight="800">Pending</text>
                        <text x="{{ $x3 + ($barW/2) }}" y="195" text-anchor="middle" font-size="12" fill="#667085"
                            font-weight="800">Rejected</text>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Buyer Notifications Bell --}}
        @if (auth()->check() && auth()->user()->role === 'Buyer')
        <div class="notification-box p-3 mb-3">
            <h5 class="mb-3" style="font-weight:950;color:var(--text);">
                <i class="ti ti-bell-ringing" style="color:var(--brand2);"></i>
                Enquiry Status & NDA Notifications
                @php $count = auth()->user()->unreadNotifications->count(); @endphp
                @if ($count > 0)
                <span class="badge bg-danger">{{ $count }}</span>
                @endif
            </h5>

            @forelse (auth()->user()->unreadNotifications as $notification)
            @php $type = $notification->data['type'] ?? 'status_update'; @endphp

            <div class="notification-item border-bottom py-2">
                <div class="small text-muted">
                    @if ($type === 'nda_sent')
                    📄 NDA Update
                    @else
                    📢 Enquiry Status
                    @endif
                </div>

                <a href="{{ route('buyer.notifications.open', e_id($notification->id)) }}"
                    class="fw-bold text-decoration-none">
                    {{ $notification->data['message'] ?? 'Update received' }}
                </a>
            </div>
            @empty
            <div class="text-center text-muted py-3">
                No new enquiry updates
            </div>
            @endforelse
        </div>
        @endif

        {{-- Seller Notifications --}}
        @if (auth()->check() && auth()->user()->role === 'Seller')
        <div class="notification-box p-3 mb-3">
            <h5 class="mb-3" style="font-weight:950;color:var(--text);">
                <i class="ti ti-bell" style="color:var(--brand2);"></i>
                Seller Notifications
                @php $count = auth()->user()->unreadNotifications->count(); @endphp
                @if ($count > 0)
                <span class="badge bg-danger">{{ $count }}</span>
                @endif
            </h5>

            @forelse(auth()->user()->unreadNotifications as $notification)
            @php $type = $notification->data['type'] ?? ''; @endphp

            <div class="notification-item border-bottom py-2">
                <div class="small text-muted">
                    @if ($type === 'new_enquiry')
                    🆕 New Enquiry
                    @elseif($type === 'nda_signed')
                    ✅ NDA Completed
                    @else
                    📢 Update
                    @endif
                </div>

                <a class="fw-bold text-decoration-none"
                    href="{{ route('buyer.notifications.open', e_id($notification->id)) }}">
                    {{ $notification->data['message'] ?? 'Update received' }}
                </a>
            </div>
            @empty
            <div class="text-center text-muted py-3">No new updates</div>
            @endforelse
        </div>
        @endif

        {{-- ========================= ADMIN DASHBOARD ========================= --}}
        @if (($role ?? '') === 'Admin')
        <div class="section-head">
            <h5><span class="section-dot"></span> Overview</h5>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-12 col-md-6 col-lg-4">
                <div class="stat-card">
                    <div class="stat-top">
                        <p class="stat-label">Total Users</p>
                        <div class="stat-icon"><i class="ti ti-users"></i></div>
                    </div>
                    <p class="stat-value">{{ $total_users ?? 0 }}</p>
                    <div class="stat-sub">All registered accounts</div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-4">
                <div class="stat-card">
                    <div class="stat-top">
                        <p class="stat-label">Total Sellers</p>
                        <div class="stat-icon"><i class="ti ti-briefcase"></i></div>
                    </div>
                    <p class="stat-value">{{ $total_sellers ?? 0 }}</p>
                    <div class="stat-sub">Users with seller role</div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-4">
                <div class="stat-card">
                    <div class="stat-top">
                        <p class="stat-label">Total Buyers</p>
                        <div class="stat-icon"><i class="ti ti-user-check"></i></div>
                    </div>
                    <p class="stat-value">{{ $total_buyers ?? 0 }}</p>
                    <div class="stat-sub">Users with buyer role</div>
                </div>
            </div>
        </div>

        <div class="section-head">
            <h5><span class="section-dot"></span> Business Status</h5>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <p class="stat-label">Total Businesses</p>
                        <div class="stat-icon"><i class="ti ti-building-store"></i></div>
                    </div>
                    <p class="stat-value">{{ $total_businesses ?? 0 }}</p>
                    <div class="stat-sub">All listed businesses</div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <p class="stat-label">Approved</p>
                        <div class="stat-icon"><i class="ti ti-circle-check"></i></div>
                    </div>
                    <p class="stat-value">{{ $approved_businesses ?? 0 }}</p>
                    <div class="stat-sub">Live listings</div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <p class="stat-label">Pending</p>
                        <div class="stat-icon"><i class="ti ti-clock"></i></div>
                    </div>
                    <p class="stat-value">{{ $pending_businesses ?? 0 }}</p>
                    <div class="stat-sub">Need review</div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <p class="stat-label">Rejected</p>
                        <div class="stat-icon"><i class="ti ti-circle-x"></i></div>
                    </div>
                    <p class="stat-value">{{ $rejected_businesses ?? 0 }}</p>
                    <div class="stat-sub">Not approved</div>
                </div>
            </div>
        </div>

        <div class="table-wrap mt-3">
            <div class="card-head p-3">
                <h5><i class="ti ti-list-details"></i> Recent Business Listings</h5>
                <a href="{{ route('listings.index') ?? '#' }}" class="btn btn-sm btn-outline-secondary">
                    View All
                </a>
            </div>

            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Business</th>
                            <th>Deal Type</th>
                            <th>Owner</th>
                            <th>Status</th>
                            <th>Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(($recent_businesses ?? []) as $i => $b)
                        @php $st = strtolower($b->status ?? 'pending'); @endphp
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td class="fw-semibold">
                                <div class="d-flex align-items-center gap-2">
                                    @if ($b->business_img)
                                    <img class="thumb" src="{{ asset('storage/' . ltrim($b->business_img, '/')) }}"
                                        alt="Business">
                                    @else
                                    <div class="thumb d-flex align-items-center justify-content-center text-muted"
                                        style="font-size:12px;">
                                        No Img
                                    </div>
                                    @endif
                                    <div>
                                        <div class="fw-semibold">{{ $b->business_name ?? '—' }}</div>
                                        <div class="text-muted" style="font-size:12px;">#{{ $b->id }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $b->deal_type ?? '—' }}</td>
                            <td>{{ $b->user->name ?? '—' }}</td>
                            <td>
                                @if ($st === 'approved')
                                <span class="badge-soft b-approved">Approved</span>
                                @elseif($st === 'rejected')
                                <span class="badge-soft b-rejected">Rejected</span>
                                @else
                                <span class="badge-soft b-pending">Pending</span>
                                @endif
                            </td>
                            <td class="text-muted">{{ optional($b->created_at)->format('d M Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted p-4">No businesses found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row g-3 mt-3">
            @php
                $uC = $six_month_users_counts ?? [];
                $eC = $six_month_enquiries_counts ?? [];
                $uMax = max(1, ...(array_map(fn ($v) => (int) $v, $uC ?: [0])));
                $eMax = max(1, ...(array_map(fn ($v) => (int) $v, $eC ?: [0])));
            @endphp

            <div class="col-12">
                <div class="mini-row">
                    <div class="table-wrap">
                        <div class="card-head p-3">
                            <h5><i class="ti ti-user-plus"></i> Users Joined (6 Months)</h5>
                            <span class="text-muted" style="font-weight:800;font-size:12px;">Mini bars</span>
                        </div>
                        <div class="p-3">
                            <svg class="mini-chart" viewBox="0 0 600 150" preserveAspectRatio="none" aria-hidden="true">
                                @php
                                    $left = 20;
                                    $right = 580;
                                    $bottom = 110;
                                    $h = 82;
                                    $barW = 60;
                                    $gap = 32;
                                    $startX = 28;
                                @endphp
                                <g opacity="0.16" stroke="#101828" stroke-width="1">
                                    <path d="M{{ $left }} {{ $bottom }} H{{ $right }}" />
                                </g>
                                @for ($i = 0; $i < 6; $i++)
                                    @php
                                        $val = (int) ($uC[$i] ?? 0);
                                        $bh = (int) round($h * ($val / $uMax));
                                        $x = $startX + ($i * ($barW + $gap));
                                        $y = $bottom - $bh;
                                        $ml = $six_month_labels[$i] ?? '';
                                        $mShort = $ml ? \Carbon\Carbon::parse('01 ' . $ml)->format('M') : '';
                                    @endphp
                                    <rect x="{{ $x }}" y="{{ $y }}" width="{{ $barW }}" height="{{ $bh }}" rx="12" fill="#111827" opacity="0.85" />
                                    <rect x="{{ $x }}" y="{{ $y }}" width="{{ $barW }}" height="{{ max(2, (int) round($bh * 0.25)) }}" rx="12" fill="#CCAA57" opacity="0.95" />
                                    <text x="{{ $x + ($barW/2) }}" y="136" text-anchor="middle" font-size="12" fill="#667085" font-weight="800">{{ $mShort }}</text>
                                @endfor
                            </svg>
                        </div>
                    </div>

                    <div class="table-wrap">
                        <div class="card-head p-3">
                            <h5><i class="ti ti-message-circle"></i> Enquiries (6 Months)</h5>
                            <span class="text-muted" style="font-weight:800;font-size:12px;">Dot line</span>
                        </div>
                        <div class="p-3">
                            <svg class="mini-chart" viewBox="0 0 600 150" preserveAspectRatio="none" aria-hidden="true">
                                @php
                                    $left = 24;
                                    $right = 576;
                                    $top = 18;
                                    $bottom = 110;
                                    $w = $right - $left;
                                    $step = $w / max(1, 5);
                                    $pts = [];
                                    for ($i = 0; $i < 6; $i++) {
                                        $val = (int) ($eC[$i] ?? 0);
                                        $x = $left + ($i * $step);
                                        $y = $bottom - (int) round(72 * ($val / $eMax));
                                        $ml = $six_month_labels[$i] ?? '';
                                        $mShort = $ml ? \Carbon\Carbon::parse('01 ' . $ml)->format('M') : '';
                                        $pts[] = ['x' => (int) $x, 'y' => (int) $y, 'm' => $mShort];
                                    }
                                    $d = '';
                                    foreach ($pts as $idx => $p) {
                                        $d .= ($idx === 0 ? 'M ' : ' L ') . $p['x'] . ' ' . $p['y'];
                                    }
                                @endphp
                                <g opacity="0.16" stroke="#101828" stroke-width="1">
                                    <path d="M{{ $left }} {{ $bottom }} H{{ $right }}" />
                                </g>
                                <path d="{{ $d }}" fill="none" stroke="#CCAA57" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                                @foreach ($pts as $p)
                                    <circle cx="{{ $p['x'] }}" cy="{{ $p['y'] }}" r="4.5" fill="#fff" stroke="#CCAA57" stroke-width="2.5" />
                                    <text x="{{ $p['x'] }}" y="136" text-anchor="middle" font-size="12" fill="#667085" font-weight="800">{{ $p['m'] }}</text>
                                @endforeach
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-5">
                <div class="table-wrap">
                    <div class="card-head p-3">
                        <h5><i class="ti ti-users"></i> Recent Users</h5>
                        <a href="{{ route('user-management') }}" class="btn btn-sm btn-outline-secondary">View All</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Role</th>
                                    <th>Joined</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(($recent_users ?? []) as $i => $u)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td class="fw-semibold">{{ $u->name ?? '—' }}</td>
                                        <td><span class="badge-soft b-approved">{{ $u->role ?? '—' }}</span></td>
                                        <td class="text-muted">{{ optional($u->created_at)->format('d M Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted p-4">No users found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-7">
                <div class="table-wrap">
                    <div class="card-head p-3">
                        <h5><i class="ti ti-message-circle"></i> Recent Enquiries</h5>
                        <a href="{{ route('seller.enquiries.all') }}" class="btn btn-sm btn-outline-secondary">All Enquiries</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Buyer</th>
                                    <th>Business</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(($recent_enquiries ?? []) as $i => $enq)
                                    @php $es = strtolower($enq->status ?? 'pending'); @endphp
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td class="fw-semibold">{{ $enq->user->name ?? '—' }}</td>
                                        <td>{{ $enq->listing->business_name ?? '—' }}</td>
                                        <td>
                                            @if ($es === 'approved')
                                                <span class="badge-soft b-approved">Approved</span>
                                            @elseif($es === 'rejected')
                                                <span class="badge-soft b-rejected">Rejected</span>
                                            @else
                                                <span class="badge-soft b-pending">Pending</span>
                                            @endif
                                        </td>
                                        <td class="text-muted">{{ optional($enq->created_at)->format('d M Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted p-4">No enquiries yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- ========================= SEO MANAGER DASHBOARD ========================= --}}
        @elseif (($role ?? '') === 'seo_manager')
        <div class="section-head">
            <h5><span class="section-dot"></span> SEO Overview</h5>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <p class="stat-label">SEO Pages</p>
                        <div class="stat-icon"><i class="ti ti-file-text"></i></div>
                    </div>
                    <p class="stat-value">{{ $seo_total_pages ?? 0 }}</p>
                    <div class="stat-sub">Static pages with metadata</div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <p class="stat-label">Listing Slugs</p>
                        <div class="stat-icon"><i class="ti ti-link"></i></div>
                    </div>
                    <p class="stat-value">{{ $seo_listing_slugs ?? 0 }}</p>
                    <div class="stat-sub">Listings ready for SEO URLs</div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <p class="stat-label">Blog Slugs</p>
                        <div class="stat-icon"><i class="ti ti-article"></i></div>
                    </div>
                    <p class="stat-value">{{ $seo_blog_slugs ?? 0 }}</p>
                    <div class="stat-sub">Blogs indexed with slugs</div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <p class="stat-label">Custom Schemas</p>
                        <div class="stat-icon"><i class="ti ti-code"></i></div>
                    </div>
                    <p class="stat-value">{{ $seo_custom_schema_count ?? 0 }}</p>
                    <div class="stat-sub">Listings with custom schema</div>
                </div>
            </div>
        </div>

        <div class="table-wrap mb-3">
            <div class="card-head p-3">
                <h5><i class="ti ti-world-search"></i> SEO Quick Actions</h5>
                <a href="{{ route('admin.seo.index') }}" class="btn btn-sm btn-outline-secondary">Open SEO Panel</a>
            </div>
            <div class="p-3">
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('admin.seo.pages') }}" class="btn btn-brand">
                        <i class="ti ti-file-text me-1"></i> Page SEO
                    </a>
                    <a href="{{ route('admin.seo.listings') }}" class="btn btn-outline-secondary">
                        <i class="ti ti-briefcase me-1"></i> Listing SEO
                    </a>
                    <a href="{{ route('admin.seo.blogs') }}" class="btn btn-outline-secondary">
                        <i class="ti ti-notebook me-1"></i> Blog SEO
                    </a>
                    <a href="{{ route('admin.seo.sitemap') }}" class="btn btn-outline-secondary">
                        <i class="ti ti-sitemap me-1"></i> Sitemap
                    </a>
                </div>
            </div>
        </div>

        <div class="table-wrap">
            <div class="card-head p-3">
                <h5><i class="ti ti-file-text"></i> Latest SEO Pages</h5>
                <a href="{{ route('admin.seo.pages') }}" class="btn btn-sm btn-outline-secondary">Manage</a>
            </div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Page</th>
                            <th>Slug</th>
                            <th>Public URL</th>
                            <th>Meta Title</th>
                            <th>Updated</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(($seo_recent_pages ?? []) as $i => $item)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $item->name ?? '—' }}</td>
                                <td>{{ $item->slug ?? '—' }}</td>
                                <td>
                                    @if (!empty($item->public_url))
                                        <a href="{{ $item->public_url }}" target="_blank" class="text-decoration-none">
                                            {{ $item->public_url }}
                                        </a>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>{{ $item->meta_title ?? '—' }}</td>
                                <td class="text-muted">{{ optional($item->updated_at)->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted p-4">No pages found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="table-wrap mt-3">
            <div class="card-head p-3">
                <h5><i class="ti ti-briefcase"></i> Recent Listings For SEO</h5>
                <a href="{{ route('admin.seo.listings') }}" class="btn btn-sm btn-outline-secondary">Manage</a>
            </div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Business</th>
                            <th>Slug</th>
                            <th>SEO Title</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(($seo_recent_listings ?? []) as $i => $item)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $item->business_name ?? '—' }}</td>
                                <td>{{ $item->slug ?? '—' }}</td>
                                <td>{{ $item->seo_title ?? '—' }}</td>
                                <td>{{ $item->status ?? '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted p-4">No listings found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="table-wrap mt-3">
            <div class="card-head p-3">
                <h5><i class="ti ti-article"></i> Recent Blogs For SEO</h5>
                <a href="{{ route('admin.seo.blogs') }}" class="btn btn-sm btn-outline-secondary">Manage</a>
            </div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Slug</th>
                            <th>SEO Title</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(($seo_recent_blogs ?? []) as $i => $item)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $item->title ?? '—' }}</td>
                                <td>{{ $item->slug ?? '—' }}</td>
                                <td>{{ $item->seo_title ?? '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted p-4">No blogs found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ========================= SELLER DASHBOARD ========================= --}}
        @elseif (($role ?? '') === 'Seller')
        <div class="section-head">
            <h5><span class="section-dot"></span> My Listings</h5>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <p class="stat-label">My Businesses</p>
                        <div class="stat-icon"><i class="ti ti-building-store"></i></div>
                    </div>
                    <p class="stat-value">{{ $my_total_businesses ?? 0 }}</p>
                    <div class="stat-sub">Total listings</div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <p class="stat-label">Pending</p>
                        <div class="stat-icon"><i class="ti ti-clock"></i></div>
                    </div>
                    <p class="stat-value">{{ $my_pending ?? 0 }}</p>
                    <div class="stat-sub">Waiting approval</div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <p class="stat-label">Approved</p>
                        <div class="stat-icon"><i class="ti ti-circle-check"></i></div>
                    </div>
                    <p class="stat-value">{{ $my_approved ?? 0 }}</p>
                    <div class="stat-sub">Live on site</div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <p class="stat-label">Rejected</p>
                        <div class="stat-icon"><i class="ti ti-circle-x"></i></div>
                    </div>
                    <p class="stat-value">{{ $my_rejected ?? 0 }}</p>
                    <div class="stat-sub">Need updates</div>
                </div>
            </div>
        </div>

        <div class="section-head">
            <h5><span class="section-dot"></span> Enquiries</h5>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <p class="stat-label">Total Enquiries</p>
                        <div class="stat-icon"><i class="ti ti-message"></i></div>
                    </div>
                    <p class="stat-value">{{ $total_enquiries ?? 0 }}</p>
                    <div class="stat-sub">All enquiries on your listings</div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <p class="stat-label">Pending</p>
                        <div class="stat-icon"><i class="ti ti-clock"></i></div>
                    </div>
                    <p class="stat-value">{{ $pending_enquiries ?? 0 }}</p>
                    <div class="stat-sub">Awaiting action</div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <p class="stat-label">Approved</p>
                        <div class="stat-icon"><i class="ti ti-circle-check"></i></div>
                    </div>
                    <p class="stat-value">{{ $approved_enquiries ?? 0 }}</p>
                    <div class="stat-sub">Accepted leads</div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <p class="stat-label">Rejected</p>
                        <div class="stat-icon"><i class="ti ti-circle-x"></i></div>
                    </div>
                    <p class="stat-value">{{ $rejected_enquiries ?? 0 }}</p>
                    <div class="stat-sub">Closed/Rejected</div>
                </div>
            </div>
        </div>

        <div class="table-wrap">
            <div class="card-head p-3">
                <h5><i class="ti ti-building"></i> My Recent Listings</h5>
                <a href="{{ route('listings.index') ?? '#' }}" class="btn btn-sm btn-outline-secondary">Manage
                    Listings</a>
            </div>

            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Business</th>
                            <th>Deal Type</th>
                            <th>Status</th>
                            <th>Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(($my_recent_businesses ?? []) as $i => $b)
                        @php $st = strtolower($b->status ?? 'pending'); @endphp
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td class="fw-semibold">
                                <div class="d-flex align-items-center gap-2">
                                    @if ($b->business_img)
                                    <img class="thumb" src="{{ asset('storage/' . ltrim($b->business_img, '/')) }}"
                                        alt="Business">
                                    @else
                                    <div class="thumb d-flex align-items-center justify-content-center text-muted"
                                        style="font-size:12px;">
                                        No Img
                                    </div>
                                    @endif
                                    <div>
                                        <div class="fw-semibold">{{ $b->business_name ?? '—' }}</div>
                                        <div class="text-muted" style="font-size:12px;">#{{ $b->id }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $b->deal_type ?? '—' }}</td>
                            <td>
                                @if ($st === 'approved')
                                <span class="badge-soft b-approved">Approved</span>
                                @elseif($st === 'rejected')
                                <span class="badge-soft b-rejected">Rejected</span>
                                @else
                                <span class="badge-soft b-pending">Pending</span>
                                @endif
                            </td>
                            <td class="text-muted">{{ optional($b->created_at)->format('d M Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted p-4">No listings yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="table-wrap mt-3">
            <div class="card-head p-3">
                <h5><i class="ti ti-message-circle"></i> Recent Enquiries</h5>
                <a href="{{ route('seller.enquiries.all') }}" class="btn btn-sm btn-outline-secondary">View All</a>
            </div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Buyer</th>
                            <th>Business</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(($seller_recent_enquiries ?? []) as $i => $enq)
                            @php $es = strtolower($enq->status ?? 'pending'); @endphp
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td class="fw-semibold">{{ $enq->user->name ?? '—' }}</td>
                                <td>{{ $enq->listing->business_name ?? '—' }}</td>
                                <td>
                                    @if ($es === 'approved')
                                        <span class="badge-soft b-approved">Approved</span>
                                    @elseif($es === 'rejected')
                                        <span class="badge-soft b-rejected">Rejected</span>
                                    @else
                                        <span class="badge-soft b-pending">Pending</span>
                                    @endif
                                </td>
                                <td class="text-muted">{{ optional($enq->created_at)->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted p-4">No enquiries yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ========================= BUYER DASHBOARD ========================= --}}
        @elseif (($role ?? '') === 'Buyer')
        <div class="section-head">
            <h5><span class="section-dot"></span> Buyer Overview</h5>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <p class="stat-label">Total Enquiries</p>
                        <div class="stat-icon"><i class="ti ti-send"></i></div>
                    </div>
                    <p class="stat-value">{{ $buyer_total_enquiries ?? 0 }}</p>
                    <div class="stat-sub">All enquiries you sent</div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <p class="stat-label">Pending</p>
                        <div class="stat-icon"><i class="ti ti-clock"></i></div>
                    </div>
                    <p class="stat-value">{{ $buyer_pending_enquiries ?? 0 }}</p>
                    <div class="stat-sub">Waiting for response</div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <p class="stat-label">Approved</p>
                        <div class="stat-icon"><i class="ti ti-circle-check"></i></div>
                    </div>
                    <p class="stat-value">{{ $buyer_approved_enquiries ?? 0 }}</p>
                    <div class="stat-sub">Accepted by sellers</div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <p class="stat-label">Rejected</p>
                        <div class="stat-icon"><i class="ti ti-circle-x"></i></div>
                    </div>
                    <p class="stat-value">{{ $buyer_rejected_enquiries ?? 0 }}</p>
                    <div class="stat-sub">Closed / declined</div>
                </div>
            </div>
        </div>

        <div class="table-wrap">
            <div class="card-head p-3">
                <h5><i class="ti ti-bolt"></i> Quick Actions</h5>
            </div>
            <div class="p-3">
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('listings.index') ?? '#' }}" class="btn btn-brand">
                        <i class="ti ti-search me-1"></i> Browse Businesses
                    </a>
                    <a href="{{ route('buyer.enquiries.rejected') }}" class="btn btn-outline-secondary">
                        <i class="ti ti-circle-x me-1"></i> Rejected Inquiries
                    </a>
                    <a href="{{ route('buyer.enquiries.pending') }}" class="btn btn-outline-secondary">
                        <i class="ti ti-clock me-1"></i> Pending Inquiries
                    </a>
                    <a href="{{ route('buyer.enquiries.approved') }}" class="btn btn-outline-secondary">
                        <i class="ti ti-circle-check me-1"></i> Approved Inquiries
                    </a>
                </div>
            </div>
        </div>

        <div class="table-wrap mt-3">
            <div class="card-head p-3">
                <h5>
                    <i class="ti ti-send"></i> My Enquired Businesses
                </h5>

                <a href="{{ route('buyer.enquiries.pending') }}" class="btn btn-sm btn-outline-secondary">
                    View All
                </a>
            </div>

            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Business</th>
                            <th>Deal Type</th>
                            <th>Status</th>
                            <th>Enquiry Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse(($buyer_enquired_businesses ?? []) as $i => $enq)
                        @php
                        $b = $enq->listing;
                        $enqSt = strtolower($enq->status ?? 'pending');
                        @endphp

                        <tr>
                            <td>{{ $i + 1 }}</td>

                            {{-- Business --}}
                            <td class="fw-semibold">
                                <div class="d-flex align-items-center gap-2">

                                    @if ($b && $b->business_img)
                                    <img class="thumb" src="{{ asset('storage/' . ltrim($b->business_img, '/')) }}"
                                        alt="Business">
                                    @else
                                    <div class="thumb d-flex align-items-center justify-content-center text-muted"
                                        style="font-size:12px;">
                                        No Img
                                    </div>
                                    @endif

                                    <div>
                                        <div class="fw-semibold">
                                            {{ $b->business_name ?? '—' }}
                                        </div>

                                        <div class="text-muted" style="font-size:12px;">
                                            #{{ $b->id ?? '—' }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Deal Type --}}
                            <td>{{ $b->deal_type ?? '—' }}</td>

                            {{-- Enquiry Status (Buyer) --}}
                            <td>
                                @if ($enqSt === 'approved')
                                <span class="badge-soft b-approved">Approved</span>
                                @elseif($enqSt === 'rejected')
                                <span class="badge-soft b-rejected">Rejected</span>
                                @else
                                <span class="badge-soft b-pending">Pending</span>
                                @endif
                            </td>

                            {{-- Enquiry Date --}}
                            <td class="text-muted">
                                {{ optional($enq->created_at)->format('d M Y') }}
                            </td>

                            {{-- Action --}}
                            <td>
                                @if ($b)
                                <a href="{{ route('listings.show', e_id($b->id)) }}" class="btn btn-sm"
                                    style="background:#CCAA57;color:white;">
                                    <i class="ti ti-eye"></i>
                                </a>
                                @endif
                            </td>
                        </tr>

                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted p-4">
                                You have not sent any enquiries yet.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        {{-- 6 MONTHS: HOW MANY BUSINESSES LISTED --}}
        @php
        $mLabels = $six_month_labels ?? [];
        $mCounts = $six_month_counts ?? [];
        $mMax = max(1, ...(array_map(fn ($v) => (int) $v, $mCounts ?: [0])));
        @endphp

        <div class="table-wrap mt-3">
            <div class="card-head p-3">
                <h5><i class="ti ti-chart-line"></i> Businesses Listed (Last 6 Months)</h5>
                <span class="text-muted" style="font-weight:800;font-size:12px;">Monthly totals</span>
            </div>
            <div class="p-3">
                <svg class="month6" viewBox="0 0 760 240" preserveAspectRatio="none" aria-hidden="true">
                    @php
                    $baseY = 200;
                    $chartH = 150;
                    $left = 60;
                    $right = 740;
                    $top = 35;
                    $bottom = 200;
                    $w = $right - $left;
                    $step = $w / max(1, 5);

                    $pts = [];
                    for ($i = 0; $i < 6; $i++) { $val=(int) ($mCounts[$i] ?? 0); $x=$left + ($i * $step); $y=$bottom -
                        (int) round($chartH * ($val / $mMax)); $pts[]=['x'=> (int) $x, 'y' => (int) $y, 'v' => $val,
                        'label' => (string) ($mLabels[$i] ?? '')];
                        }

                        $lineD = '';
                        foreach ($pts as $idx => $p) {
                        $lineD .= ($idx === 0 ? 'M ' : ' L ') . $p['x'] . ' ' . $p['y'];
                        }

                        $areaD = $lineD . ' L ' . ($pts[count($pts) - 1]['x'] ?? $right) . ' ' . $bottom . ' L ' .
                        ($pts[0]['x'] ?? $left) . ' ' . $bottom . ' Z';
                        @endphp

                        <!-- grid -->
                        <g opacity="0.16" stroke="#101828" stroke-width="1">
                            <path d="M{{ $left }} {{ $bottom }} H{{ $right }}" />
                            <path d="M{{ $left }} 150 H{{ $right }}" />
                            <path d="M{{ $left }} 100 H{{ $right }}" />
                            <path d="M{{ $left }} 50 H{{ $right }}" />
                        </g>

                        <!-- filled area -->
                        <path d="{{ $areaD }}" fill="#CCAA57" opacity="0.16" />
                        <!-- line -->
                        <path d="{{ $lineD }}" fill="none" stroke="#CCAA57" stroke-width="3.5" />

                        <!-- points + values -->
                        @foreach ($pts as $p)
                        <circle cx="{{ $p['x'] }}" cy="{{ $p['y'] }}" r="5" fill="#fff" stroke="#CCAA57"
                            stroke-width="2.5" />
                        <text x="{{ $p['x'] }}" y="{{ max($top, $p['y'] - 10) }}" text-anchor="middle" font-size="12"
                            fill="#101828" font-weight="900">{{ $p['v'] }}</text>
                        @endforeach

                        <!-- x labels -->
                        @foreach ($pts as $p)
                        @php
                        $label = $p['label'];
                        $m = $label ? \Carbon\Carbon::parse('01 ' . $label)->format('M') : '';
                        @endphp
                        <text x="{{ $p['x'] }}" y="226" text-anchor="middle" font-size="12" fill="#667085"
                            font-weight="800">{{ $m }}</text>
                        @endforeach
                </svg>

                <div class="chart-legend">
                    <span class="chart-key"><span class="chart-swatch"></span> Businesses Listed</span>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
