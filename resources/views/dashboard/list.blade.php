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

        /* ====== PAGE BACKGROUND GRAPHICS ====== */
        .dash-page {
            position: relative;
            overflow: hidden;
            border-radius: 18px;
            background: linear-gradient(180deg, #ffffff 0%, #fbfbfd 55%, #ffffff 100%);
        }

        .dash-page:before,
        .dash-page:after {
            content: "";
            position: absolute;
            width: 520px;
            height: 520px;
            border-radius: 50%;
            filter: blur(40px);
            opacity: .22;
            z-index: 0;
            pointer-events: none;
        }

        .dash-page:before {
            top: -180px;
            left: -220px;
            background: radial-gradient(circle at 30% 30%, var(--brand) 0%, transparent 60%);
        }

        .dash-page:after {
            bottom: -220px;
            right: -240px;
            background: radial-gradient(circle at 60% 60%, #3A6EF2 0%, transparent 60%);
            opacity: .14;
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
            background:
                radial-gradient(1200px 260px at 10% 0%, rgba(204, 170, 87, .16), transparent 55%),
                radial-gradient(900px 240px at 90% 30%, rgba(58, 110, 242, .10), transparent 55%),
                linear-gradient(180deg, #ffffff, #fbfbfd);
            box-shadow: var(--shadow2);
            position: relative;
            overflow: hidden;
        }

        .dash-hero .pattern {
            position: absolute;
            inset: -20px -30px auto auto;
            width: 360px;
            height: 180px;
            opacity: .22;
            transform: rotate(-8deg);
            pointer-events: none;
            background:
                radial-gradient(circle at 8px 8px, rgba(16, 24, 40, .12) 2px, transparent 3px);
            background-size: 18px 18px;
            mask-image: radial-gradient(circle at 40% 40%, black 0%, transparent 65%);
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
            content: "";
            position: absolute;
            right: -80px;
            top: -80px;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background: radial-gradient(circle at 30% 30%, rgba(204, 170, 87, .22), transparent 60%);
            opacity: .85;
        }

        .stat-card:after {
            content: "";
            position: absolute;
            left: -60px;
            bottom: -60px;
            width: 160px;
            height: 160px;
            border-radius: 50%;
            background: radial-gradient(circle at 40% 40%, rgba(58, 110, 242, .12), transparent 60%);
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
            background: linear-gradient(180deg, #ffffff, #f7f8fb);
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
            background:
                radial-gradient(900px 180px at 0% 0%, rgba(204, 170, 87, .12), transparent 55%),
                linear-gradient(180deg, #fff, #fbfbfd);
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
            background:
                radial-gradient(900px 220px at 0% 0%, rgba(204, 170, 87, .12), transparent 55%),
                linear-gradient(180deg, #fff, #fbfbfd);
            box-shadow: var(--shadow2);
            position: relative;
            overflow: hidden;
        }

        .notification-box:after {
            content: "";
            position: absolute;
            right: -80px;
            top: -90px;
            width: 220px;
            height: 220px;
            border-radius: 50%;
            background: radial-gradient(circle at 40% 40%, rgba(58, 110, 242, .12), transparent 60%);
            pointer-events: none;
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
            color: #fff;
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

                            <a href="{{ route('buyer.notifications.open', $notification->id) }}"
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
                                href="{{ route('buyer.notifications.open', $notification->id) }}">
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
                                                    <img class="thumb"
                                                        src="{{ 'storage/app/public/' . $b->business_img }}"
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
                                                    <img class="thumb"
                                                        src="{{ 'storage/app/public/' . $b->business_img }}"
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
                                        $st = strtolower($b->status ?? 'pending');
                                    @endphp

                                    <tr>
                                        <td>{{ $i + 1 }}</td>

                                        {{-- Business --}}
                                        <td class="fw-semibold">
                                            <div class="d-flex align-items-center gap-2">

                                                @if ($b && $b->business_img)
                                                    <img class="thumb"
                                                        src="{{ 'storage/app/public/' . $b->business_img }}"
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

                                        {{-- Listing Status --}}
                                        <td>
                                            @if ($st === 'approved')
                                                <span class="badge-soft b-approved">Approved</span>
                                            @elseif($st === 'rejected')
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
                                                <a href="{{ route('listings.show', $b->id) }}" class="btn btn-sm"
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

        </div>
    </div>
@endsection
