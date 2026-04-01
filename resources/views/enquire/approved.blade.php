@extends('dashboard')

@section('title')
<title>Enquire Approved | Mergersales</title>
@endsection

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Approved Enquiries</h4>
        </div>
        <div class="card-body">
            @if (count($enquiries) > 0)
            <div class="table">
                <table class="table" id="myTable2">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Listing</th>
                            <th>Buyer Name</th>
                            <th>Message</th>
                            <th>Status</th>
                            <th>Document</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($enquiries as $enquiry)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $enquiry->listing->deal_type ?? 'N/A' }}</td>
                            <td>{{ $enquiry->user->name ?? 'N/A' }}</td>
                            <td>{{ \Illuminate\Support\Str::words($enquiry->message, 10, '...') }}</td>
                            <td>
                                @php
                                $statusColors = [
                                'pending' => 'bg-warning text-dark',
                                'approved' => 'bg-success text-white',
                                'rejected' => 'bg-danger text-white',
                                ];
                                $badgeClass =
                                $statusColors[$enquiry->status] ?? 'bg-secondary text-white';
                                @endphp
                                <span class="badge {{ $badgeClass }}">
                                    {{ ucfirst($enquiry->status) }}
                                </span>
                            </td>
                            <td>
                                @php
                                // attachments safe decode
                                $files = is_array($enquiry->attachments)
                                ? $enquiry->attachments
                                : (json_decode($enquiry->attachments, true) ?:
                                []);

                                $file = $files[0] ?? null;

                                // ✅ absolute public URL (NO asset, NO buyer/enquiries issue)
                                $fileUrl = $file
                                ? url('storage/app/public/' . ltrim($file, '/'))
                                : null;
                                @endphp

                                @if ($fileUrl)
                                <a href="{{ $fileUrl }}" target="_blank" class="text-decoration-none">
                                    <i class="bx bxs-file-pdf text-danger"></i> View Document
                                </a>
                                @else
                                <span class="text-muted">N/A</span>
                                @endif
                            </td>


                            <td class="text-center">
                                <div class="btn-group">
                                    <!-- Dropdown Toggle -->
                                    <button type="button" class="btn btn-sm" data-bs-toggle="dropdown"
                                        aria-expanded="false" style="color:black; border:none;">
                                        <i class="ti ti-dots-vertical"></i>
                                    </button>

                                    <!-- Dropdown Menu -->
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <!-- View Enquiry -->
                                        <li>
                                            <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                data-bs-target="#viewEnquiryModal{{ $enquiry->id }}">
                                                <i class="ti ti-eye me-1"></i> View
                                            </a>
                                        </li>

                                        <!-- Delete Enquiry -->
                                        <li>
                                            <a class="dropdown-item text-danger" href="#"
                                                onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this enquiry?')) { document.getElementById('delete-form-{{ $enquiry->id }}').submit(); }">
                                                <i class="ti ti-trash me-1"></i> Delete
                                            </a>
                                            <form id="delete-form-{{ $enquiry->id }}"
                                                action="{{ route('enquiries.destroy', e_id($enquiry->id)) }}"
                                                method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-5">
                <i class="ti ti-inbox fs-1 text-muted"></i>
                <h5 class="mt-3">No approved enquiries found</h5>
                <p class="text-muted">All enquiries have been processed.</p>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modals for each enquiry -->
@foreach ($enquiries as $enquiry)
@php

// attachments safe decode
$files = is_array($enquiry->attachments)
? $enquiry->attachments
: (json_decode($enquiry->attachments, true) ?:
[]);

$file = $files[0] ?? null;

// ✅ absolute public URL (NO asset, NO buyer/enquiries issue)
$fileUrl = $file ? url('http://localhost/mergersales/storage/app/public/' . ltrim($file, '/')) : null;

$statusColors = [
'pending' => 'bg-warning text-dark',
'approved' => 'bg-success text-white',
'rejected' => 'bg-danger text-white',
];
$badgeClass = $statusColors[$enquiry->status] ?? 'bg-secondary text-white';
@endphp

<!-- Modal for Enquiry Details -->
<div class="modal fade" id="viewEnquiryModal{{ $enquiry->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Enquiry Details - {{ $enquiry->user->name ?? 'N/A' }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong class="d-block mb-1">Listing:</strong>
                        <span>{{ $enquiry->listing->deal_type ?? 'N/A' }}</span>
                    </div>
                    <div class="col-md-6">
                        <strong class="d-block mb-1">Buyer Name:</strong>
                        <span>{{ $enquiry->user->name ?? 'N/A' }}</span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong class="d-block mb-1">Email:</strong>
                        <span>{{ $enquiry->email }}</span>
                    </div>
                    <div class="col-md-6">
                        <strong class="d-block mb-1">Phone:</strong>
                        <span>{{ $enquiry->phone ?? 'N/A' }}</span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong class="d-block mb-1">Company:</strong>
                        <span>{{ $enquiry->company ?? 'N/A' }}</span>
                    </div>
                    <div class="col-md-6">
                        <strong class="d-block mb-1">Interest Type:</strong>
                        <span>{{ ucfirst($enquiry->interest_type) }}</span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong class="d-block mb-1">Budget:</strong>
                        <span> {{ $enquiry->budget ?? 'N/A' }}</span>
                    </div>
                    <div class="col-md-6">
                        <strong class="d-block mb-1">Applied At:</strong>
                        <span>{{ $enquiry->created_at->format('d M Y, h:i A') }}</span>
                    </div>
                </div>
                <div class="mb-3">
                    <strong class="d-block mb-1">Message:</strong>
                    <div class="border rounded p-3 bg-light">
                        {{ $enquiry->message }}
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong class="d-block mb-1">Status:</strong>
                        <span class="badge {{ $badgeClass }}">
                            {{ ucfirst($enquiry->status) }}
                        </span>
                    </div>
                    <div class="col-md-6">
                        <strong class="d-block mb-1">Document:</strong>
                        @if ($file)
                        <a href="{{ $fileUrl }}" target="_blank" class="text-decoration-none">
                            <i class="bx bxs-file-pdf text-danger"></i> View Document
                        </a>
                        @else
                        <span class="text-muted">N/A</span>
                        @endif
                    </div>

                    @if ($enquiry->seller_response)
                    <div class="col-12 mb-3">
                        <label class="form-label">Seller's Response</label>
                        <textarea class="form-control" rows="4" readonly>{{ $enquiry->seller_response }}</textarea>
                    </div>
                    @endif

                    @if ($enquiry->nda_required)
                    <div class="col-12 mb-3">
                        <label class="form-label">NDA Status</label>

                        <div class="d-flex gap-2 align-items-center flex-wrap">
                            <span class="badge
                                                    {{ $enquiry->nda_status == 'not_sent' ? 'bg-secondary' : '' }}
                                                    {{ $enquiry->nda_status == 'sent' ? 'bg-primary' : '' }}
                                                    {{ $enquiry->nda_status == 'signed' ? 'bg-success' : '' }}">
                                {{ strtoupper(str_replace('_', ' ', $enquiry->nda_status)) }}
                            </span>

                            @if ($enquiry->nda_status === 'sent' || $enquiry->nda_status === 'signed')
                            <a class="btn btn-sm btn-outline-primary"
                                href="{{ route('enquiries.downloadNda', e_id($enquiry->id)) }}">
                                Download NDA
                            </a>
                            @endif

                            @if ($enquiry->nda_status === 'signed')
                            <a class="btn btn-sm btn-outline-success"
                                href="{{ route('enquiries.downloadSignedNda', e_id($enquiry->id)) }}">
                                Download Signed NDA
                            </a>
                            @endif
                        </div>
                    </div>
                    @php
                    $sigUrl = $enquiry->buyer_signature_path
                    ? url('http://localhost/mergersales/storage/app/public/' . ltrim($enquiry->buyer_signature_path,
                    '/'))
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

                    @if ($enquiry->nda_status === 'sent')
                    <div class="col-12 mb-3">
                        <label class="form-label">NDA Preview</label>

                        <div class="border rounded overflow-hidden" style="height:520px;">
                            <iframe src="{{ route('enquiries.previewNda', e_id($enquiry->id)) }}"
                                style="width:100%; height:100%; border:0;"></iframe>
                        </div>
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label">Sign Here</label>

                        <div class="border rounded p-2 bg-white">
                            <canvas id="sigCanvas{{ $enquiry->id }}" style="width:100%; height:180px;"></canvas>

                            <div class="d-flex gap-2 mt-2 flex-wrap">
                                <button type="button" class="btn btn-sm btn-outline-secondary"
                                    onclick="clearSig{{ $enquiry->id }}()">
                                    Clear
                                </button>

                                <form method="POST" action="{{ route('enquiries.signNda', e_id($enquiry->id)) }}"
                                    onsubmit="return submitSig{{ $enquiry->id }}(this)">
                                    @csrf
                                    <input type="hidden" name="signature_data" id="sigData{{ $enquiry->id }}">
                                    <button class="btn btn-success btn-sm">
                                        Submit Signed NDA
                                    </button>
                                </form>
                            </div>

                            <small class="text-muted d-block mt-2">
                                Draw your signature above and submit. System will generate a signed PDF
                                automatically.
                            </small>
                        </div>
                    </div>
                    <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const modalEl = document.getElementById('viewEnquiryModal{{ $enquiry->id }}');
                        const canvas = document.getElementById('sigCanvas{{ $enquiry->id }}');
                        const input = document.getElementById('sigData{{ $enquiry->id }}');

                        if (!modalEl || !canvas) return;

                        let sigPad = null;

                        function resizeCanvas() {
                            const ratio = Math.max(window.devicePixelRatio || 1, 1);
                            const rect = canvas.getBoundingClientRect();

                            // important: if hidden, skip
                            if (!rect.width || !rect.height) return;

                            canvas.width = rect.width * ratio;
                            canvas.height = rect.height * ratio;

                            const ctx = canvas.getContext("2d");
                            ctx.setTransform(ratio, 0, 0, ratio, 0, 0);

                            if (sigPad) sigPad.clear();
                        }

                        modalEl.addEventListener('shown.bs.modal', function() {
                            if (!sigPad) {
                                sigPad = new SignaturePad(canvas, {
                                    minWidth: 1,
                                    maxWidth: 3,
                                    penColor: "#111827"
                                });
                            }
                            resizeCanvas();
                        });

                        window.clearSig {
                            {
                                $enquiry - > id
                            }
                        } = function() {
                            if (sigPad) sigPad.clear();
                        }

                        window.submitSig {
                            {
                                $enquiry - > id
                            }
                        } = function() {
                            if (!sigPad || sigPad.isEmpty()) {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Signature required',
                                    text: 'Please add your signature first.'
                                });
                                return false;
                            }
                            input.value = sigPad.toDataURL('image/png');
                            return true;
                        }

                        window.addEventListener('resize', resizeCanvas);
                    });
                    </script>
                    @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- Add DataTables JS if needed -->
@push('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTable if needed
    $('#myTable2').DataTable({
        pageLength: 10,
        responsive: true,
        order: [
            [0, 'asc']
        ]
    });

    // Check if modal needs to be shown from URL parameter
    const urlParams = new URLSearchParams(window.location.search);
    const showModalId = urlParams.get('show_modal');
    if (showModalId) {
        const modal = new bootstrap.Modal(document.getElementById(showModalId));
        modal.show();
    }
});
</script>
@endpush
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if (session('success'))
<script>
Swal.fire({
    icon: 'success',
    title: 'Success',
    text: "{{ session('success') }}",
    confirmButtonColor: '#0d6efd',
    timer: 3000,
    timerProgressBar: true
});
</script>
@endif

@if (session('error'))
<script>
Swal.fire({
    icon: 'error',
    title: 'Oops!',
    text: "{{ session('error') }}",
    confirmButtonColor: '#dc3545'
});
</script>
@endif
@endsection

@push('styles')
<style>
.table th {
    background-color: #f8f9fa;
    font-weight: 600;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

.badge {
    font-size: 0.75em;
    padding: 0.35em 0.65em;
}

/* Modal fixes */
.modal-backdrop {
    z-index: 1040;
}

.modal {
    z-index: 1050;
}
</style>
@endpush