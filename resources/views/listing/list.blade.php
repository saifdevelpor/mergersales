@extends('dashboard')

@section('title')
    <title>Business | Mergersales</title>
@endsection

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <div class="container-fluid mt-3">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Business Listings</h5>

                        @if (auth()->user()->role === 'Seller')
                            <button class="btn"
                                style="background:#CCAA57; color:white; border:none; padding:8px 18px; border-radius:5px;"
                                data-bs-toggle="modal" data-bs-target="#addListing">
                                <i class="ti ti-plus me-1"></i> Create Business
                            </button>
                        @endif
                    </div>

                    <div class="row g-4">
                        @foreach ($listings as $listing)
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="card listing-card h-100 border-0 shadow-sm position-relative">

                                    <!-- 🔘 Vertical Dots Dropdown (Top Right) -->
                                    <div class="dropdown listing-actions">
                                        <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
                                            <i class="ti ti-dots-vertical"></i>
                                        </button>

                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('listings.show', $listing->id) }}">
                                                    <i class="ti ti-eye me-1"></i> View
                                                </a>
                                            </li>

                                            @if ((auth()->user()->role === 'Seller' && auth()->id() === $listing->user_id) || auth()->user()->role === 'Admin')
                                                <li>
                                                    <a class="dropdown-item" data-bs-toggle="modal"
                                                        data-bs-target="#editListing{{ $listing->id }}">
                                                        <i class="ti ti-edit me-1"></i> Edit
                                                    </a>
                                                </li>

                                                <li>
                                                    <a class="dropdown-item text-danger" href="#"
                                                        onclick="event.preventDefault(); document.getElementById('delete-form-{{ $listing->id }}').submit();">
                                                        <i class="ti ti-trash me-1"></i> Delete
                                                    </a>

                                                    <form id="delete-form-{{ $listing->id }}"
                                                        action="{{ route('listings.destroy', $listing->id) }}"
                                                        method="POST" class="d-none">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </li>
                                            @endif

                                            {{-- ✅ ADMIN ONLY: Approve / Reject --}}
                                            @if (auth()->user()->role === 'Admin')
                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>

                                                {{-- Approve (Pending/Rejeted -> Approved) --}}
                                                @if (in_array($listing->status, ['Pending', 'Rejected']))
                                                    <li>
                                                        <a class="dropdown-item text-success" href="#"
                                                            onclick="event.preventDefault(); document.getElementById('approve-form-{{ $listing->id }}').submit();">
                                                            <i class="ti ti-check me-1"></i> Approve
                                                        </a>

                                                        <form id="approve-form-{{ $listing->id }}"
                                                            action="{{ route('listings.approve', $listing->id) }}"
                                                            method="POST" class="d-none">
                                                            @csrf
                                                            @method('PATCH')
                                                        </form>
                                                    </li>
                                                @endif

                                                {{-- Reject (Pending/Approved -> Rejected) --}}
                                                @if (in_array($listing->status, ['Pending', 'Approved']))
                                                    <li>
                                                        <a class="dropdown-item text-warning" href="#"
                                                            onclick="event.preventDefault(); document.getElementById('reject-form-{{ $listing->id }}').submit();">
                                                            <i class="ti ti-x me-1"></i> Reject
                                                        </a>

                                                        <form id="reject-form-{{ $listing->id }}"
                                                            action="{{ route('listings.reject', $listing->id) }}"
                                                            method="POST" class="d-none">
                                                            @csrf
                                                            @method('PATCH')
                                                        </form>
                                                    </li>
                                                @endif
                                            @endif


                                        </ul>
                                    </div>


                                    <!-- Top Center Image -->
                                    <div class="listing-image-wrapper text-center mt-3">
                                        <img src="{{ rtrim('storage/app/public/', '/') . '/' . $listing->business_img }}"
                                            class="listing-image" alt="Business">
                                    </div>

                                    <!-- Card Body -->
                                    <div class="card-body pt-2 text-center">
                                        <div class="mb-2">
                                            <span class="fw-bold">{{ $listing->business_name }}</span>
                                        </div>
                                        <div class="mb-2">
                                            <span class="badge me-1"
                                                style="background: #CCAA57; color: white">{{ $listing->deal_type }}</span>
                                            <span class="badge bg-secondary">{{ $listing->country }}</span>
                                        </div>

                                        <h6 class="fw-bold mb-3">{{ $listing->industry->name ?? 'N/A' }}</h6>

                                        <div class="listing-info">
                                            @php
                                                $currencySymbols = [
                                                    'USD' => '$',
                                                    'EUR' => '€',
                                                    'GBP' => '£',
                                                    'AUD' => 'A$',
                                                    'CAD' => 'C$',
                                                    'CHF' => 'CHF',
                                                ];
                                                $currency = $currencySymbols[$listing->currency] ?? '€';
                                            @endphp
                                            <p><i class="ti ti-currency-dollar"></i> <strong>Revenue:</strong>
                                                {{ $currency }} {{ $listing->revenue_range }}</p>
                                            <p><i class="ti ti-chart-bar"></i> <strong>EBITDA:</strong> {{ $currency }}
                                                {{ $listing->ebitda_range }}</p>
                                            <p><i class="ti ti-users"></i> <strong>Employees:</strong>
                                                {{ $listing->employee_range }}</p>
                                        </div>
                                    </div>

                                    <!-- Card Footer with 3 buttons -->
                                    <div class="card-footer bg-white d-flex justify-content-between flex-wrap gap-2">

                                        <!-- View Details (always visible) -->
                                        <a href="{{ route('listings.show', $listing->id) }}"
                                            class="btn btn-outline-primary btn-sm flex-grow-1">
                                            View Details
                                        </a>

                                        <!-- Enquire Button -->
                                        @if (auth()->user()->role === 'Seller' || auth()->user()->role === 'Admin')
                                            <a class="btn btn-warning btn-sm flex-grow-1"
                                                href="{{ route('seller.listing.enquiries', $listing->id) }}">
                                                Enquire ({{ $listing->enquiries->where('status', 'pending')->count() }})
                                            </a>
                                        @elseif (auth()->user()->role === 'Buyer')
                                            @if ($listing->enquiries->where('user_id', auth()->id())->count() > 0)
                                                <button class="btn btn-success btn-sm flex-grow-1" disabled>Applied</button>
                                            @else
                                                <button class="btn btn-warning btn-sm flex-grow-1" data-bs-toggle="modal"
                                                    data-bs-target="#enquiryModal{{ $listing->id }}">
                                                    Enquire
                                                </button>
                                            @endif
                                        @endif

                                        @php
                                            $user = auth()->user();
                                            $unreadCount = (int) ($unreadByListing[$listing->id] ?? 0);

                                            // Buyer: always chat with listing owner (seller)
                                            $sellerId = $listing->user_id;

                                            // Seller: chat with last buyer who messaged on this listing
                                            $lastBuyerId = $lastSenderByListing[$listing->id] ?? null;

                                            $chatWith = null;
                                            if ($user->role === 'Buyer') {
                                                $chatWith = $sellerId;
                                            } elseif ($user->role === 'Seller' && $unreadCount > 0 && $lastBuyerId) {
                                                // ✅ seller button only when unread exists
                                                $chatWith = $lastBuyerId;
                                            }
                                        @endphp

                                        @if ($chatWith)
                                            <a href="{{ url('chatify/' . $chatWith . '?listing_id=' . $listing->id) }}"
                                                class="btn btn-success btn-sm position-relative">
                                                <i class="ti ti-message"></i> Chat

                                                @if ($unreadCount > 0)
                                                    <span
                                                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                                        {{ $unreadCount }}
                                                    </span>
                                                @endif
                                            </a>
                                        @endif
                                    </div>

                                </div>
                            </div>
                        @endforeach
                    </div>

                    <style>
                        .listing-card {
                            border-radius: 16px;
                            transition: all 0.3s ease;
                            background: #fff;
                        }

                        .listing-card:hover {
                            transform: translateY(-6px);
                            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
                        }

                        .listing-actions {
                            position: absolute;
                            top: 10px;
                            right: 10px;
                            z-index: 10;
                        }

                        .listing-image {
                            width: 90px;
                            height: 90px;
                            border-radius: 50%;
                            object-fit: cover;
                            background: #f8f9fa;
                            padding: 10px;
                            border: 3px solid #fff;
                            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
                        }

                        .listing-info p {
                            font-size: 14px;
                            margin-bottom: 6px;
                            color: #555;
                        }

                        .listing-info i {
                            color: #0d6efd;
                            margin-right: 6px;
                        }

                        .card-footer button,
                        .card-footer a.btn {
                            min-width: 90px;
                            text-align: center;
                        }
                    </style>

                </div>
            </div>
        </div>
    </div>

    <!-- Add Listing Modal -->
    <div class="modal fade" id="addListing" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Business</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('listings.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Deal Type</label>
                                <select name="deal_type" class="form-select" required>
                                    <option value="">Select deal type</option>
                                    <option value="Sell business">Sell business</option>
                                    <option value="Raise capital">Raise capital</option>
                                    <option value="Find partner">Find partner</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Business name</label>
                                <input type="text" name="business_name" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Business Image</label>
                                <input type="file" name="business_img" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Currency</label>
                                <select name="currency" class="form-select" required>
                                    <option value="">Select currency</option>
                                    <option value="USD">$ USD</option>
                                    <option value="EUR">€ EUR</option>
                                    <option value="GBP">£ GBP</option>
                                    <option value="AUD">A$ AUD</option>
                                    <option value="CAD">C$ CAD</option>
                                    <option value="CHF">CHF</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Industry</label>
                                <select name="industry_id" id="industryDropdown" class="form-select" required>
                                    <option value="">Select Industry</option>
                                    @foreach ($industries as $industry)
                                        <option value="{{ $industry->id }}">{{ $industry->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Sub-Industry</label>
                                <select name="sub_industry_id" id="subIndustryDropdown" class="form-select" required>
                                    <option value="">Select Sub-Industry</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Country</label>
                                <input type="text" name="country" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Revenue Range</label>
                                <select name="revenue_range" class="form-select" required>
                                    <option value="10k-50k">10k–50k</option>
                                    <option value="50k-100k">50k–100k</option>
                                    <option value="100k-500k">100k–500k</option>
                                    <option value="500k-1m">500k–1m</option>
                                    <option value="1m-5m">1m–5m</option>
                                    <option value="5m-10m">5m–10m</option>
                                    <option value="10m+">10m+</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">EBITDA Range</label>
                                <select name="ebitda_range" class="form-select" required>
                                    <option value="Negative">Negative</option>
                                    <option value="0-250k">0–250k</option>
                                    <option value="250k-500k">250k–500k</option>
                                    <option value="500k-1m">500k–1m</option>
                                    <option value="1m-2.5m">1m–2.5m</option>
                                    <option value="2.5m-5m">2.5m–5m</option>
                                    <option value="5m+">5m+</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Headcount Range</label>
                                <select name="employee_range" class="form-select" required>
                                    <option value="1-5">1–5</option>
                                    <option value="6-10">6–10</option>
                                    <option value="11-20">11–20</option>
                                    <option value="21-50">21–50</option>
                                    <option value="51-100">51–100</option>
                                    <option value="101-250">101–250</option>
                                    <option value="250+">250+</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Reason for Sale</label>
                                <select name="reason_for_sale" class="form-select">
                                    <option value="">Select reason</option>
                                    <option value="Succession / retirement">Succession / retirement</option>
                                    <option value="Shareholder exit">Shareholder exit</option>
                                    <option value="Strategic sale">Strategic sale</option>
                                    <option value="Burnout">Burnout</option>
                                    <option value="Carve-out">Carve-out</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="4" required></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Teaser</label>
                                <input type="file" name="teaser_path" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">IM (optional)</label>
                                <input type="file" name="im_path" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn text-white" style="background:#CCAA57">Create Business</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Listing Modals + Other Modals -->
    @foreach ($listings as $listing)
        <div class="modal fade" id="editListing{{ $listing->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Business</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('listings.update', $listing->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Deal Type</label>
                                    <select name="deal_type" class="form-select" required>
                                        <option value="">Select deal type</option>
                                        <option value="Sell business"
                                            {{ $listing->deal_type == 'Sell business' ? 'selected' : '' }}>Sell business
                                        </option>
                                        <option value="Raise capital"
                                            {{ $listing->deal_type == 'Raise capital' ? 'selected' : '' }}>Raise capital
                                        </option>
                                        <option value="Find partner"
                                            {{ $listing->deal_type == 'Find partner' ? 'selected' : '' }}>Find partner
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">business Name</label>
                                    <input type="text" name="business_name" class="form-control"
                                        value="{{ $listing->business_name }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Business Image</label>
                                    <input type="file" name="business_img" class="form-control">
                                    @if ($listing->business_img)
                                        <small>Current: <a href="{{ asset('storage/' . $listing->business_img) }}"
                                                target="_blank">View</a></small>
                                    @endif
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Currency</label>
                                    <select name="currency" class="form-select" required>
                                        <option value="">Select currency</option>
                                        <option value="USD" {{ $listing->currency == 'USD' ? 'selected' : '' }}>$ USD
                                        </option>
                                        <option value="EUR" {{ $listing->currency == 'EUR' ? 'selected' : '' }}>€ EUR
                                        </option>
                                        <option value="GBP" {{ $listing->currency == 'GBP' ? 'selected' : '' }}>£ GBP
                                        </option>
                                        <option value="AUD" {{ $listing->currency == 'AUD' ? 'selected' : '' }}>A$ AUD
                                        </option>
                                        <option value="CAD" {{ $listing->currency == 'CAD' ? 'selected' : '' }}>C$ CAD
                                        </option>
                                        <option value="CHF" {{ $listing->currency == 'CHF' ? 'selected' : '' }}>CHF
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Industry</label>
                                    <select name="industry_id" id="editIndustryDropdown{{ $listing->id }}"
                                        class="form-select" required>
                                        <option value="">Select Industry</option>
                                        @foreach ($industries as $industry)
                                            <option value="{{ $industry->id }}"
                                                {{ $listing->industry_id == $industry->id ? 'selected' : '' }}>
                                                {{ $industry->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Sub-Industry</label>
                                    <select name="sub_industry_id" id="editSubIndustryDropdown{{ $listing->id }}"
                                        class="form-select" required>
                                        <option value="">Select Sub-Industry</option>
                                        @foreach ($listing->industry->subIndustries ?? [] as $sub)
                                            <option value="{{ $sub->id }}"
                                                {{ $listing->sub_industry_id == $sub->id ? 'selected' : '' }}>
                                                {{ $sub->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Country</label>
                                    <input type="text" name="country" class="form-control"
                                        value="{{ $listing->country }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Revenue Range</label>
                                    <select name="revenue_range" class="form-select" required>
                                        <option value="10k-50k"
                                            {{ $listing->revenue_range == '10k-50k' ? 'selected' : '' }}>
                                            10k–50k</option>
                                        <option value="50k-100k"
                                            {{ $listing->revenue_range == '50k-100k' ? 'selected' : '' }}>50k–100k</option>
                                        <option value="100k-500k"
                                            {{ $listing->revenue_range == '100k-500k' ? 'selected' : '' }}>100k–500k
                                        </option>
                                        <option value="500k-1m"
                                            {{ $listing->revenue_range == '500k-1m' ? 'selected' : '' }}>
                                            500k–1m</option>
                                        <option value="1m-5m" {{ $listing->revenue_range == '1m-5m' ? 'selected' : '' }}>
                                            1m–5m
                                        </option>
                                        <option value="5m-10m"
                                            {{ $listing->revenue_range == '5m-10m' ? 'selected' : '' }}>
                                            5m–10m</option>
                                        <option value="10m+" {{ $listing->revenue_range == '10m+' ? 'selected' : '' }}>
                                            10m+
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">EBITDA Range</label>
                                    <select name="ebitda_range" class="form-select" required>
                                        <option value="Negative"
                                            {{ $listing->ebitda_range == 'Negative' ? 'selected' : '' }}>
                                            Negative</option>
                                        <option value="0-250k" {{ $listing->ebitda_range == '0-250k' ? 'selected' : '' }}>
                                            0–250k</option>
                                        <option value="250k-500k"
                                            {{ $listing->ebitda_range == '250k-500k' ? 'selected' : '' }}>250k–500k
                                        </option>
                                        <option value="500k-1m"
                                            {{ $listing->ebitda_range == '500k-1m' ? 'selected' : '' }}>
                                            500k–1m</option>
                                        <option value="1m-2.5m"
                                            {{ $listing->ebitda_range == '1m-2.5m' ? 'selected' : '' }}>1m–2.5m</option>
                                        <option value="2.5m-5m"
                                            {{ $listing->ebitda_range == '2.5m-5m' ? 'selected' : '' }}>2.5m–5m</option>
                                        <option value="5m+" {{ $listing->ebitda_range == '5m+' ? 'selected' : '' }}>5m+
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Headcount Range</label>
                                    <select name="employee_range" class="form-select" required>
                                        <option value="1-5" {{ $listing->employee_range == '1-5' ? 'selected' : '' }}>
                                            1–5
                                        </option>
                                        <option value="6-10" {{ $listing->employee_range == '6-10' ? 'selected' : '' }}>
                                            6–10
                                        </option>
                                        <option value="11-20"
                                            {{ $listing->employee_range == '11-20' ? 'selected' : '' }}>
                                            11–20</option>
                                        <option value="21-50"
                                            {{ $listing->employee_range == '21-50' ? 'selected' : '' }}>
                                            21–50</option>
                                        <option value="51-100"
                                            {{ $listing->employee_range == '51-100' ? 'selected' : '' }}>
                                            51–100</option>
                                        <option value="101-250"
                                            {{ $listing->employee_range == '101-250' ? 'selected' : '' }}>101–250</option>
                                        <option value="250+" {{ $listing->employee_range == '250+' ? 'selected' : '' }}>
                                            250+
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Reason for Sale</label>
                                    <select name="reason_for_sale" class="form-select">
                                        <option value="">Select reason</option>
                                        <option value="Succession / retirement"
                                            {{ $listing->reason_for_sale == 'Succession / retirement' ? 'selected' : '' }}>
                                            Succession / retirement</option>
                                        <option value="Shareholder exit"
                                            {{ $listing->reason_for_sale == 'Shareholder exit' ? 'selected' : '' }}>
                                            Shareholder exit</option>
                                        <option value="Strategic sale"
                                            {{ $listing->reason_for_sale == 'Strategic sale' ? 'selected' : '' }}>Strategic
                                            sale</option>
                                        <option value="Burnout"
                                            {{ $listing->reason_for_sale == 'Burnout' ? 'selected' : '' }}>
                                            Burnout</option>
                                        <option value="Carve-out"
                                            {{ $listing->reason_for_sale == 'Carve-out' ? 'selected' : '' }}>Carve-out
                                        </option>
                                        <option value="Other"
                                            {{ $listing->reason_for_sale == 'Other' ? 'selected' : '' }}>
                                            Other</option>
                                    </select>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea name="description" class="form-control" rows="4" required>{{ $listing->description }}</textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Teaser</label>
                                    <input type="file" name="teaser_path" class="form-control">
                                    @if ($listing->teaser_path)
                                        <small>Current: <a href="{{ asset('storage/' . $listing->teaser_path) }}"
                                                target="_blank">View</a></small>
                                    @endif
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">IM (optional)</label>
                                    <input type="file" name="im_path" class="form-control">
                                    @if ($listing->im_path)
                                        <small>Current: <a href="{{ asset('storage/' . $listing->im_path) }}"
                                                target="_blank">View</a></small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn text-white" style="background:#CCAA57">Update
                                Business</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- View Business Modal -->
        <div class="modal fade" id="viewListing{{ $listing->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Business Details - {{ $listing->deal_type }}
                            ({{ $listing->country }})
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <!-- Modal Body -->
                    <div class="modal-body">
                        <div class="row g-3">

                            <!-- Left Column -->
                            <div class="col-lg-6">
                                <label class="form-label">Deal Type</label>
                                <input type="text" class="form-control mb-2" value="{{ $listing->deal_type }}"
                                    readonly>

                                <label class="form-label">Industry</label>
                                <input type="text" class="form-control mb-2"
                                    value="{{ $listing->industry->name ?? 'N/A' }}" readonly>

                                <label class="form-label">Sub-Industry</label>
                                <input type="text" class="form-control mb-2"
                                    value="{{ $listing->subIndustry->name ?? 'N/A' }}" readonly>

                                <label class="form-label">Country</label>
                                <input type="text" class="form-control mb-2" value="{{ $listing->country }}"
                                    readonly>

                                <label class="form-label">Revenue Range</label>
                                <input type="text" class="form-control mb-2" value="{{ $listing->revenue_range }}"
                                    readonly>

                                <label class="form-label">EBITDA Range</label>
                                <input type="text" class="form-control mb-2" value="{{ $listing->ebitda_range }}"
                                    readonly>
                            </div>

                            <!-- Right Column -->
                            <div class="col-lg-6">
                                <label class="form-label">Reason for Sale</label>
                                <input type="text" class="form-control mb-2"
                                    value="{{ $listing->reason_for_sale ?? 'N/A' }}" readonly>

                                <label class="form-label">Status</label>
                                <input type="text" class="form-control mb-2"
                                    value="{{ $listing->is_active ? 'Active' : 'Paused' }}" readonly>

                                <label class="form-label">Created By</label>
                                <input type="text" class="form-control mb-2"
                                    value="{{ $listing->user->name ?? 'N/A' }}" readonly>

                                <label class="form-label">Created At</label>
                                <input type="text" class="form-control mb-2"
                                    value="{{ $listing->created_at->format('d M Y, h:i A') }}" readonly>
                                <label class="form-label">Headcount Range</label>
                                <input type="text" class="form-control mb-2" value="{{ $listing->employee_range }}"
                                    readonly>
                            </div>

                            <!-- Full Width Description -->
                            <div class="col-12">
                                <label class="form-label">Description</label>
                                <textarea class="form-control mb-3" rows="5" readonly>{{ $listing->description ?? 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.' }}</textarea>
                            </div>

                            <!-- Documents / Files -->
                            <div class="col-12">
                                <h6 class="fw-bold mb-2">Documents / Files</h6>
                                <div class="d-flex flex-wrap gap-2">
                                    <div class="d-flex flex-wrap gap-3 align-items-center">
                                        <!-- Teaser -->
                                        <div>
                                            <label class="form-label mb-1">Teaser:</label><br>
                                            @if ($listing->teaser_path)
                                                @php
                                                    $teaserExt = pathinfo($listing->teaser_path, PATHINFO_EXTENSION);
                                                    $isImage = in_array(strtolower($teaserExt), [
                                                        'jpg',
                                                        'jpeg',
                                                        'png',
                                                        'gif',
                                                        'webp',
                                                        'pdf',
                                                    ]);
                                                @endphp

                                                @if ($isImage)
                                                    <a href="{{ 'storage/app/public/' . $listing->teaser_path }}"
                                                        target="_blank">
                                                        <img src="{{ 'storage/app/public/' . $listing->teaser_path }}"
                                                            alt="Teaser Image" class="img-thumbnail"
                                                            style="width:120px;height:auto;">
                                                    </a>
                                                @else
                                                    <a href="{{ asset('storage/' . $listing->teaser_path) }}"
                                                        target="_blank" class="btn btn-sm btn-primary">
                                                        <i class="ti ti-file-text me-1"></i> View
                                                    </a>
                                                @endif
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </div>

                                        <!-- IM -->
                                        <div>
                                            <label class="form-label mb-1">IM:</label><br>
                                            @if ($listing->im_path)
                                                @php
                                                    $imExt = pathinfo($listing->im_path, PATHINFO_EXTENSION);
                                                    $isImage = in_array(strtolower($imExt), [
                                                        'jpg',
                                                        'jpeg',
                                                        'png',
                                                        'gif',
                                                        'webp',
                                                        'pdf',
                                                    ]);
                                                @endphp

                                                @if ($isImage)
                                                    <a href="{{ 'storage/app/public/' . $listing->im_path }}"
                                                        target="_blank">
                                                        <img src="{{ 'storage/app/public/' . $listing->im_path }}"
                                                            alt="IM Image" class="img-thumbnail"
                                                            style="width:120px;height:auto;">
                                                    </a>
                                                @else
                                                    <a href="{{ asset('storage/' . $listing->im_path) }}"
                                                        target="_blank" class="btn btn-sm btn-info">
                                                        <i class="ti ti-file-text me-1"></i> View
                                                    </a>
                                                @endif
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Additional Files -->
                                    @if ($listing->documents && count($listing->documents) > 0)
                                        @foreach ($listing->documents as $doc)
                                            <div>
                                                <a href="{{ asset('storage/' . $doc->path) }}" target="_blank"
                                                    class="btn btn-sm btn-secondary">
                                                    <i class="ti ti-file-text me-1"></i> {{ $doc->name ?? 'Document' }}
                                                </a>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Buyer Enquiry Modal -->
        @if (auth()->user()->role === 'Buyer')
            <div class="modal fade" id="enquiryModal{{ $listing->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Apply / Enquire</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form method="POST" action="{{ route('enquiry.store') }}" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="listing_id" value="{{ $listing->id }}">
                            <div class="modal-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label>Name *</label>
                                        <input type="text" name="name" class="form-control"
                                            placeholder="Enter Name" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Email *</label>
                                        <input type="email" name="email" class="form-control"
                                            placeholder="Enter Email" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Phone</label>
                                        <input type="text" name="phone" class="form-control"
                                            placeholder="Enter Phone">
                                    </div>
                                    <div class="col-md-6">
                                        <label>Company</label>
                                        <input type="text" name="company" class="form-control"
                                            placeholder="Enter Company">
                                    </div>
                                    <div class="col-md-6">
                                        <label>Position</label>
                                        <input type="text" name="position" class="form-control"
                                            placeholder="Enter Position">
                                    </div>
                                    <div class="col-md-6">
                                        <label>Type of Interest *</label>
                                        <select name="interest_type" class="form-select" required>
                                            <option value="">-- Select Type of Interest --</option>
                                            <option value="buy">Buy Full Business</option>
                                            <option value="partner">Partner / Joint Venture</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Budget / Offer</label>
                                        <input type="text" name="budget" class="form-control"
                                            placeholder="Enter Budget / Offer">
                                    </div>
                                    <div class="col-md-6">
                                        <label>Timeline</label>
                                        <select name="timeline" class="form-select">
                                            <option value="">Select</option>
                                            <option value="1_month">Within 1 Month</option>
                                            <option value="3_months">Within 3 Months</option>
                                            <option value="6_months">Within 6 Months</option>
                                            <option value="flexible">Flexible</option>
                                        </select>
                                    </div>

                                    <div class="col-12">
                                        <label>Message *</label>
                                        <textarea name="message" class="form-control" rows="4" placeholder="Enter Message" required></textarea>
                                    </div>

                                    <div class="col-12">
                                        <label>Attachments</label>
                                        <input type="file" name="attachments[]" class="form-control" multiple>
                                    </div>

                                    <!-- ✅ NDA checkbox (FIX) -->
                                    <div class="col-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="nda_required"
                                                value="1" id="ndaReq{{ $listing->id }}" required>
                                            <label class="form-check-label" for="ndaReq{{ $listing->id }}">
                                                Please Cheak Box
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn w-100" style="background: #CCAA57; color: white;">Send
                                    Enquiry</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        <!-- Seller View Enquiries Modal -->
        @if ((auth()->user()->role === 'Seller' && auth()->id() === $listing->user_id) || auth()->user()->role === 'Admin')
            <div class="modal fade" id="sellerEnquiriesModal{{ $listing->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h5 class="modal-title fw-bold">
                                Enquiries for {{ $listing->deal_type }} - {{ $listing->country }}
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <!-- Modal Body -->
                        <div class="modal-body">
                            @if ($listing->enquiries->count() > 0)
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead class="table">
                                            <tr>
                                                <th>#</th>
                                                <th>Buyer Name</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>Company</th>
                                                <th>Interest Type</th>
                                                <th>Budget</th>
                                                <th>Message</th>
                                                <th>Applied At</th>
                                                <th>Status</th>
                                                <th>View Details</th>
                                                @if (auth()->user()->role === 'Seller')
                                                    <th class="text-center">Update Status</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($listing->enquiries as $index => $enquiry)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td class="fw-medium">{{ $enquiry->name }}</td>
                                                    <td>{{ $enquiry->email }}</td>
                                                    <td>{{ $enquiry->phone ?? 'N/A' }}</td>
                                                    <td>{{ $enquiry->company ?? 'N/A' }}</td>
                                                    <td class="text-capitalize">{{ $enquiry->interest_type }}</td>
                                                    <td>{{ $enquiry->budget ?? 'N/A' }}</td>
                                                    <td>{{ \Illuminate\Support\Str::limit($enquiry->message, 50) }}</td>
                                                    <td>{{ $enquiry->created_at->format('d M Y') }}</td>
                                                    <td>
                                                        @php
                                                            $statusColors = [
                                                                'pending' => 'bg-warning text-dark',
                                                                'approved' => 'bg-success text-white',
                                                                'rejected' => 'bg-danger text-white',
                                                            ];
                                                            $badgeClass =
                                                                $statusColors[$enquiry->status] ??
                                                                'bg-secondary text-white';
                                                        @endphp
                                                        <span class="badge {{ $badgeClass }}">
                                                            {{ ucfirst($enquiry->status) }}
                                                        </span>
                                                    </td>
                                                    <!-- View Button -->
                                                    <td>
                                                        <button class="btn btn-sm"
                                                            style="background:#CCAA57; color:white; border:none;"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#viewEnquiryModal{{ $enquiry->id }}">
                                                            <i class="ti ti-eye me-1"></i> View
                                                        </button>
                                                    </td>
                                                    @if (auth()->user()->role === 'Seller')
                                                        <!-- Status Update Dropdown -->
                                                        <td class="text-center">
                                                            <div class="btn-group">
                                                                <button class="btn btn-sm" type="button"
                                                                    data-bs-toggle="dropdown">
                                                                    <i class="ti ti-dots-vertical"></i>
                                                                </button>

                                                                <ul class="dropdown-menu">
                                                                    <li>
                                                                        <a class="dropdown-item" href="#"
                                                                            onclick="updateStatus({{ $enquiry->id }}, 'approved')">
                                                                            <i class="ti ti-check text-success me-1"></i>
                                                                            Approve
                                                                        </a>
                                                                    </li>

                                                                    <li>
                                                                        <a class="dropdown-item" href="#"
                                                                            onclick="updateStatus({{ $enquiry->id }}, 'rejected')">
                                                                            <i class="ti ti-x text-danger me-1"></i> Reject
                                                                        </a>
                                                                    </li>

                                                                    <li>
                                                                        <a class="dropdown-item" href="#"
                                                                            onclick="updateStatus({{ $enquiry->id }}, 'pending')">
                                                                            <i class="ti ti-clock text-warning me-1"></i>
                                                                            Pending
                                                                        </a>
                                                                    </li>

                                                                    {{-- NDA option --}}
                                                                    @if ($enquiry->nda_required)
                                                                        <li>
                                                                            <hr class="dropdown-divider">
                                                                        </li>
                                                                        <li>
                                                                            <a class="dropdown-item" href="#"
                                                                                onclick="sendNda({{ $enquiry->id }})">
                                                                                <i
                                                                                    class="ti ti-file text-primary me-1"></i>
                                                                                Send NDA
                                                                            </a>
                                                                        </li>
                                                                    @endif
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="ti ti-inbox fs-1 text-muted"></i>
                                    <h5 class="mt-3">No Enquiries Yet</h5>
                                    <p class="text-muted">No buyers have applied for this business yet.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Per-enquiry Modals -->
            @foreach ($listing->enquiries as $enquiry)
                <div class="modal fade" id="viewEnquiryModal{{ $enquiry->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title fw-bold">Enquiry Details - {{ $enquiry->name }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                                <div class="row mb-2">
                                    <div class="col-md-6"><strong>Name:</strong> {{ $enquiry->name }}</div>
                                    <div class="col-md-6"><strong>Email:</strong> {{ $enquiry->email }}</div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-md-6"><strong>Phone:</strong> {{ $enquiry->phone ?? 'N/A' }}</div>
                                    <div class="col-md-6"><strong>Company:</strong> {{ $enquiry->company ?? 'N/A' }}
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-md-6"><strong>Interest Type:</strong>
                                        {{ ucfirst($enquiry->interest_type) }}</div>
                                    <div class="col-md-6"><strong>Budget:</strong> {{ $enquiry->budget ?? 'N/A' }}</div>
                                </div>

                                {{-- ✅ NEW: NDA Status + Download links (seller can see too) --}}
                                @if ($enquiry->nda_required)
                                    <div class="mb-2">
                                        <strong>NDA Status:</strong>

                                        @php
                                            $ndaBadgeClass = match ($enquiry->nda_status) {
                                                'sent' => 'bg-primary',
                                                'signed' => 'bg-success',
                                                default => 'bg-secondary',
                                            };
                                            $ndaLabel = strtoupper(
                                                str_replace('_', ' ', $enquiry->nda_status ?? 'NOT_SENT'),
                                            );
                                        @endphp

                                        <span class="badge {{ $ndaBadgeClass }} ms-1">
                                            {{ $ndaLabel }}
                                        </span>

                                        @if ($enquiry->nda_status === 'sent' || $enquiry->nda_status === 'signed')
                                            <a class="btn btn-sm btn-outline-primary ms-2"
                                                href="{{ route('enquiries.downloadNda', $enquiry->id) }}">
                                                Download NDA
                                            </a>
                                        @endif

                                        @if ($enquiry->nda_status === 'signed')
                                            <a class="btn btn-sm btn-outline-success ms-2"
                                                href="{{ route('enquiries.downloadSignedNda', $enquiry->id) }}">
                                                Download Signed NDA
                                            </a>
                                        @endif
                                    </div>

                                    @php
                                        $sigUrl = $enquiry->buyer_signature_path
                                            ? url('storage/app/public/' . ltrim($enquiry->buyer_signature_path, '/'))
                                            : null;
                                    @endphp

                                    @if ($sigUrl)
                                        <div class="mb-3">
                                            <label class="form-label">Buyer Signature</label><br>
                                            <a href="{{ $sigUrl }}" target="_blank">
                                                <img src="{{ $sigUrl }}"
                                                    style="height:60px; border:1px solid #eee; border-radius:8px; padding:6px; background:#fff;">
                                            </a>
                                        </div>
                                    @endif
                                @endif

                                <div class="mb-2">
                                    <strong>Message:</strong>
                                    <p>{{ $enquiry->message }}</p>
                                </div>

                                <div class="mb-2">
                                    <strong>Applied At:</strong> {{ $enquiry->created_at->format('d M Y, h:i A') }}
                                </div>

                                <div class="mb-2">
                                    <strong>Status:</strong>
                                    <span
                                        class="badge
                        {{ $enquiry->status == 'pending' ? 'bg-warning text-dark' : '' }}
                        {{ $enquiry->status == 'approved' ? 'bg-success text-white' : '' }}
                        {{ $enquiry->status == 'rejected' ? 'bg-danger text-white' : '' }}">
                                        {{ ucfirst($enquiry->status) }}
                                    </span>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    @endforeach

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // NOTE: businessTable id aap ke markup me nahi hai, is liye ye harmless rahega.
            // Agar aap table add karo to id="businessTable" rakh dena.
            $('#businessTable').DataTable({
                "pageLength": 10,
                "order": [
                    [0, 'desc']
                ],
                "language": {
                    "search": "Search:",
                    "lengthMenu": "Show _MENU_ entries",
                    "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                    "paginate": {
                        "first": "First",
                        "last": "Last",
                        "next": "Next",
                        "previous": "Previous"
                    }
                }
            });

            // Dynamic Sub-Industry for Add Modal
            const industries = @json($industries);
            const industryDropdown = document.getElementById('industryDropdown');
            const subIndustryDropdown = document.getElementById('subIndustryDropdown');

            industryDropdown?.addEventListener('change', function() {
                const selected = industries.find(i => i.id == this.value);
                subIndustryDropdown.innerHTML = '<option value="">Select Sub-Industry</option>';
                if (selected) {
                    selected.sub_industries.forEach(sub => {
                        const option = document.createElement('option');
                        option.value = sub.id;
                        option.textContent = sub.name;
                        subIndustryDropdown.appendChild(option);
                    });
                }
            });

            // Dynamic Sub-Industry for Edit Modals
            @foreach ($listings as $listing)
                const editIndustryDropdown{{ $listing->id }} = document.getElementById(
                    'editIndustryDropdown{{ $listing->id }}');
                const editSubIndustryDropdown{{ $listing->id }} = document.getElementById(
                    'editSubIndustryDropdown{{ $listing->id }}');

                if (editIndustryDropdown{{ $listing->id }}) {
                    editIndustryDropdown{{ $listing->id }}.addEventListener('change', function() {
                        const selected = industries.find(i => i.id == this.value);
                        editSubIndustryDropdown{{ $listing->id }}.innerHTML =
                            '<option value="">Select Sub-Industry</option>';
                        if (selected) {
                            selected.sub_industries.forEach(sub => {
                                const option = document.createElement('option');
                                option.value = sub.id;
                                option.textContent = sub.name;
                                editSubIndustryDropdown{{ $listing->id }}.appendChild(option);
                            });
                        }
                    });
                }
            @endforeach
        });

        function confirmDelete(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This Business will be permanently deleted!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#CCAA57',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }

        // SweetAlert Messages
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: "{{ session('success') }}",
                confirmButtonColor: '#3085d6',
                timer: 3000
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: "{{ session('error') }}",
                confirmButtonColor: '#d33'
            });
        @endif

        @if ($errors->any())
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                html: `
                <ul style="text-align:left;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            `,
                confirmButtonColor: '#f39c12'
            });
        @endif

        // ✅ SINGLE updateStatus + sendNda (No duplicates)
        async function updateStatus(enquiryId, status) {
            try {
                const res = await fetch("{{ url('/enquiries') }}/" + enquiryId + "/status", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Accept": "application/json"
                    },
                    body: JSON.stringify({
                        status
                    })
                });

                const data = await res.json();
                if (!res.ok || data.success === false) throw (data.message || "Status update failed");

                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: data.message || 'Status updated successfully!',
                    confirmButtonColor: '#3085d6'
                }).then(() => location.reload());

            } catch (e) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: (typeof e === 'string' ? e : 'An error occurred'),
                    confirmButtonColor: '#d33'
                });
            }
        }

        async function sendNda(enquiryId) {
            const confirmSend = await Swal.fire({
                title: 'Send NDA?',
                text: 'This will send NDA to buyer.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, send it'
            });

            if (!confirmSend.isConfirmed) return;

            try {
                const res = await fetch("{{ url('/enquiries') }}/" + enquiryId + "/send-nda", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Accept": "application/json"
                    }
                });

                const ct = res.headers.get("content-type") || "";
                if (!ct.includes("application/json")) {
                    throw "Server returned HTML (login/redirect/route error). Make sure user is logged in + route is correct.";
                }

                const data = await res.json();
                if (!res.ok || data.ok === false) throw (data.message || "Failed to send NDA");

                Swal.fire({
                    icon: 'success',
                    title: 'NDA Sent!',
                    text: data.message || 'NDA sent successfully.',
                    confirmButtonColor: '#3085d6'
                }).then(() => location.reload());

            } catch (e) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: (typeof e === 'string' ? e : 'Failed to send NDA'),
                    confirmButtonColor: '#d33'
                });
            }
        }
    </script>

    <!-- Hidden Delete Forms -->
    @foreach ($listings as $listing)
        <form id="delete-form-{{ $listing->id }}" action="{{ route('listings.destroy', $listing->id) }}"
            method="POST" style="display:none;">
            @csrf
            @method('DELETE')
        </form>
    @endforeach
@endsection
