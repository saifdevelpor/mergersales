@extends('dashboard')

@section('title')
    <title>Enquiries | Mergersales</title>
@endsection

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="container-fluid mt-3">
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">Enquiries</h5>
                    <small class="text-muted">
                        {{ $listing->deal_type }} - {{ $listing->country }} | {{ $listing->business_name }}
                    </small>
                </div>

                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm">
                    Back
                </a>
            </div>

            <div class="card-body">
                @if ($listing->enquiries->count() > 0)
                    <div class="table">
                        <table class="table" id="myTable3">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Buyer</th>
                                    <th>Company</th>
                                    <th>Budget</th>
                                    <th>Status</th>
                                    <th>NDA</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($listing->enquiries as $index => $enquiry)
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-warning text-dark',
                                            'approved' => 'bg-success text-white',
                                            'rejected' => 'bg-danger text-white',
                                        ];
                                        $badgeClass = $statusColors[$enquiry->status] ?? 'bg-secondary text-white';

                                        $ndaBadgeClass = match ($enquiry->nda_status) {
                                            'sent' => 'bg-primary',
                                            'signed' => 'bg-success',
                                            default => 'bg-secondary',
                                        };
                                        $ndaLabel = strtoupper(
                                            str_replace('_', ' ', $enquiry->nda_status ?? 'NOT_SENT'),
                                        );

                                        $sigUrl = $enquiry->buyer_signature_path
                                            ? url('storage/app/public/' . ltrim($enquiry->buyer_signature_path, '/'))
                                            : null;

                                        // ✅ URLs for downloads (only show if your routes exist)
                                        $downloadNdaUrl = route('enquiries.downloadNda', $enquiry->id);
                                        $downloadSignedNdaUrl = route('enquiries.downloadSignedNda', $enquiry->id);

                                        // ✅ pack enquiry data for modal
                                        $payload = [
                                            'id' => $enquiry->id,
                                            'name' => $enquiry->name,
                                            'email' => $enquiry->email,
                                            'phone' => $enquiry->phone ?? 'N/A',
                                            'company' => $enquiry->company ?? 'N/A',
                                            'position' => $enquiry->position ?? 'N/A',
                                            'interest_type' => ucfirst($enquiry->interest_type),
                                            'budget' => $enquiry->budget ?? 'N/A',
                                            'timeline' => $enquiry->timeline ?? 'N/A',
                                            'applied_at' => $enquiry->created_at->format('d M Y, h:i A'),
                                            'status' => ucfirst($enquiry->status),
                                            'status_badge' => $badgeClass,
                                            'nda_required' => (bool) $enquiry->nda_required,
                                            'nda_status' => $ndaLabel,
                                            'nda_badge' => $ndaBadgeClass,
                                            'download_nda_url' => $downloadNdaUrl,
                                            'download_signed_nda_url' => $downloadSignedNdaUrl,
                                            'can_download_nda' => in_array($enquiry->nda_status, ['sent', 'signed']),
                                            'can_download_signed' => $enquiry->nda_status === 'signed',
                                            'signature_url' => $sigUrl,
                                            'message' => $enquiry->message ?? '',
                                        ];
                                    @endphp

                                    <tr>
                                        <td>{{ $index + 1 }}</td>

                                        <td class="fw-medium">
                                            {{ $enquiry->name }}
                                            <div class="text-muted" style="font-size:12px;">
                                                {{ $enquiry->email }}
                                            </div>
                                        </td>

                                        <td>{{ $enquiry->company ?? 'N/A' }}</td>

                                        <td>{{ $enquiry->budget ?? 'N/A' }}</td>

                                        <td>
                                            <span class="badge {{ $badgeClass }}">{{ ucfirst($enquiry->status) }}</span>
                                        </td>

                                        <td>
                                            @if ($enquiry->nda_required)
                                                <span class="badge {{ $ndaBadgeClass }}">{{ $ndaLabel }}</span>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>

                                        <td class="text-center">
                                            <div class="btn-group">
                                                <!-- ✅ View opens modal on same page -->
                                                <button type="button" class="btn btn-sm"
                                                    style="background:#CCAA57; color:white; border:none;"
                                                    data-bs-toggle="modal" data-bs-target="#enquiryDetailsModal"
                                                    data-enquiry='@json($payload)'>
                                                    <i class="ti ti-eye me-1"></i> View
                                                </button>

                                                <!-- ✅ Status + NDA actions -->
                                                <button class="btn btn-sm btn-outline-dark dropdown-toggle" type="button"
                                                    data-bs-toggle="dropdown">
                                                    Actions
                                                </button>

                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a class="dropdown-item" href="#"
                                                            onclick="updateStatus({{ $enquiry->id }}, 'approved')">
                                                            ✅ Approve
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="#"
                                                            onclick="updateStatus({{ $enquiry->id }}, 'rejected')">
                                                            ❌ Reject
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="#"
                                                            onclick="updateStatus({{ $enquiry->id }}, 'pending')">
                                                            ⏳ Pending
                                                        </a>
                                                    </li>

                                                    @if ($enquiry->nda_required)
                                                        <li>
                                                            <hr class="dropdown-divider">
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="#"
                                                                onclick="sendNda({{ $enquiry->id }})">
                                                                📄 Send NDA
                                                            </a>
                                                        </li>
                                                    @endif
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
                        <h5>No Enquiries Yet</h5>
                        <p class="text-muted">No buyers have applied for this business yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- ✅ SINGLE reusable modal -->
    <div class="modal fade" id="enquiryDetailsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg rounded-4">

                <!-- HEADER -->
                <div class="modal-header border-0 pb-0">
                    <div>
                        <h5 class="modal-title fw-bold mb-1" id="m_title">Enquiry Details</h5>
                        <small class="text-muted">Buyer information & enquiry summary</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <!-- BODY -->
                <div class="modal-body pt-3">

                    <!-- BASIC INFO -->
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-body">
                            <h6 class="fw-bold mb-3 text-primary">Buyer Information</h6>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="info-box">
                                        <span>Name</span>
                                        <p id="m_name"></p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-box">
                                        <span>Email</span>
                                        <p id="m_email"></p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-box">
                                        <span>Phone</span>
                                        <p id="m_phone"></p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-box">
                                        <span>Company</span>
                                        <p id="m_company"></p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-box">
                                        <span>Position</span>
                                        <p id="m_position"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- DEAL INFO -->
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-body">
                            <h6 class="fw-bold mb-3 text-primary">Deal Details</h6>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="info-box">
                                        <span>Interest Type</span>
                                        <p id="m_interest"></p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-box">
                                        <span>Budget</span>
                                        <p id="m_budget"></p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-box">
                                        <span>Timeline</span>
                                        <p id="m_timeline"></p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-box">
                                        <span>Applied At</span>
                                        <p id="m_applied"></p>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3">
                                <span class="me-2">Status:</span>
                                <span id="m_status_badge" class="badge"></span>
                            </div>
                        </div>
                    </div>

                    <!-- NDA SECTION -->
                    <div class="card border-0 shadow-sm mb-3" id="m_nda_wrap" style="display:none;">
                        <div class="card-body">
                            <h6 class="fw-bold mb-3 text-primary">NDA Information</h6>

                            <div class="d-flex align-items-center flex-wrap gap-2 mb-3">
                                <span>NDA Status:</span>
                                <span id="m_nda_badge" class="badge"></span>
                            </div>

                            <div class="d-flex gap-2 flex-wrap">
                                <a id="m_download_nda" class="btn btn-outline-primary btn-sm" href="#"
                                    style="display:none;">
                                    Download NDA
                                </a>

                                <a id="m_download_signed" class="btn btn-outline-success btn-sm" href="#"
                                    style="display:none;">
                                    Download Signed NDA
                                </a>
                            </div>

                            <!-- Signature -->
                            <div class="mt-3" id="m_sig_wrap" style="display:none;">
                                <label class="form-label fw-semibold">Buyer Signature</label><br>
                                <a id="m_sig_link" href="#" target="_blank">
                                    <img id="m_sig_img"
                                        style="height:70px; border:1px solid #ddd; border-radius:10px; padding:8px; background:#fff;">
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- MESSAGE -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h6 class="fw-bold mb-2 text-primary">Buyer Message</h6>
                            <p id="m_message" class="mb-0 text-muted"></p>
                        </div>
                    </div>

                </div>

                <!-- FOOTER -->
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                        Close
                    </button>
                </div>
                <style>
                    .info-box span {
                        font-size: 12px;
                        color: #6c757d;
                    }

                    .info-box p {
                        margin: 0;
                        font-weight: 600;
                        color: #212529;
                    }
                </style>

            </div>
        </div>
    </div>

    <script>
        // ✅ populate modal from data-enquiry JSON
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('enquiryDetailsModal');

            modal.addEventListener('show.bs.modal', function(event) {
                const btn = event.relatedTarget;
                const data = JSON.parse(btn.getAttribute('data-enquiry'));

                document.getElementById('m_title').innerText = 'Enquiry Details - ' + (data.name || '');
                document.getElementById('m_name').innerText = data.name || '';
                document.getElementById('m_email').innerText = data.email || '';
                document.getElementById('m_phone').innerText = data.phone || '';
                document.getElementById('m_company').innerText = data.company || '';
                document.getElementById('m_position').innerText = data.position || '';
                document.getElementById('m_interest').innerText = data.interest_type || '';
                document.getElementById('m_budget').innerText = data.budget || '';
                document.getElementById('m_timeline').innerText = data.timeline || '';
                document.getElementById('m_applied').innerText = data.applied_at || '';
                document.getElementById('m_message').innerText = data.message || '';

                // status badge
                const statusBadge = document.getElementById('m_status_badge');
                statusBadge.className = 'badge ' + (data.status_badge || 'bg-secondary');
                statusBadge.innerText = data.status || '';

                // NDA block
                const ndaWrap = document.getElementById('m_nda_wrap');
                const ndaBadge = document.getElementById('m_nda_badge');
                const dlNda = document.getElementById('m_download_nda');
                const dlSigned = document.getElementById('m_download_signed');

                if (data.nda_required) {
                    ndaWrap.style.display = 'block';
                    ndaBadge.className = 'badge ' + (data.nda_badge || 'bg-secondary') + ' ms-1';
                    ndaBadge.innerText = data.nda_status || '';

                    if (data.can_download_nda) {
                        dlNda.style.display = 'inline-block';
                        dlNda.href = data.download_nda_url;
                    } else {
                        dlNda.style.display = 'none';
                        dlNda.href = '#';
                    }

                    if (data.can_download_signed) {
                        dlSigned.style.display = 'inline-block';
                        dlSigned.href = data.download_signed_nda_url;
                    } else {
                        dlSigned.style.display = 'none';
                        dlSigned.href = '#';
                    }
                } else {
                    ndaWrap.style.display = 'none';
                }

                // Signature
                const sigWrap = document.getElementById('m_sig_wrap');
                const sigLink = document.getElementById('m_sig_link');
                const sigImg = document.getElementById('m_sig_img');

                if (data.signature_url) {
                    sigWrap.style.display = 'block';
                    sigLink.href = data.signature_url;
                    sigImg.src = data.signature_url;
                } else {
                    sigWrap.style.display = 'none';
                    sigLink.href = '#';
                    sigImg.src = '';
                }
            });
        });

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
                    throw "Server returned HTML (login/redirect/route error).";
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
@endsection
