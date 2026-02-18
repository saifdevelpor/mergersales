@extends('dashboard')

@section('title')
    <title>{{ $pageTitle }} | Mergersales</title>
@endsection

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="container-fluid mt-3">
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">{{ $pageTitle }}</h5>
                    <small class="text-muted">Your listings par aane wali {{ $status }} enquiries</small>
                </div>
            </div>

            <div class="card-body">
                @if ($enquiries->count() > 0)
                    <div class="table-responsive">
                        <table class="table" id="myTable3">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Listing</th>
                                    <th>Buyer</th>
                                    <th>Company</th>
                                    <th>Budget</th>
                                    <th>Status</th>
                                    <th>NDA</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($enquiries as $index => $enquiry)
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

                                        $downloadNdaUrl = route('enquiries.downloadNda', $enquiry->id);
                                        $downloadSignedNdaUrl = route('enquiries.downloadSignedNda', $enquiry->id);

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
                                        <td>{{ $enquiries->firstItem() + $index }}</td>
                                        <td>
                                            <div class="fw-semibold">
                                                {{ optional($enquiry->listing)->business_name ?? 'Listing Deleted' }}</div>
                                            <div class="text-muted" style="font-size:12px;">
                                                {{ optional($enquiry->listing)->deal_type }} -
                                                {{ optional($enquiry->listing)->country }}
                                            </div>
                                        </td>

                                        <td class="fw-medium">
                                            {{ $enquiry->name }}
                                            <div class="text-muted" style="font-size:12px;">{{ $enquiry->email }}</div>
                                        </td>

                                        <td>{{ $enquiry->company ?? 'N/A' }}</td>
                                        <td>{{ $enquiry->budget ?? 'N/A' }}</td>

                                        <td><span class="badge {{ $badgeClass }}">{{ ucfirst($enquiry->status) }}</span>
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

                                                <button class="btn btn-sm btn-outline-dark dropdown-toggle" type="button"
                                                    data-bs-toggle="dropdown">
                                                    Actions
                                                </button>

                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a class="dropdown-item" href="#"
                                                            onclick="updateStatus({{ $enquiry->id }}, 'approved')">✅
                                                            Approve</a></li>
                                                    <li><a class="dropdown-item" href="#"
                                                            onclick="updateStatus({{ $enquiry->id }}, 'rejected')">❌
                                                            Reject</a></li>
                                                    <li><a class="dropdown-item" href="#"
                                                            onclick="updateStatus({{ $enquiry->id }}, 'pending')">⏳
                                                            Pending</a></li>

                                                    @if ($enquiry->nda_required)
                                                        <li>
                                                            <hr class="dropdown-divider">
                                                        </li>
                                                        <li><a class="dropdown-item" href="#"
                                                                onclick="sendNda({{ $enquiry->id }})">📄 Send NDA</a></li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>

                    <div class="mt-3">{{ $enquiries->links() }}</div>
                @else
                    <div class="text-center py-5">
                        <h5>No {{ ucfirst($status) }} Enquiries</h5>
                        <p class="text-muted">Abhi koi enquiry is status me nahi hai.</p>
                    </div>
                @endif

            </div>
        </div>
    </div>
    {{-- ✅ Modal: aap apna existing modal yahan paste kar dein --}}
    {{-- (same enquiryDetailsModal + same JS that reads data-enquiry) --}}

    <script>
        // ✅ yahan apna exact modal JS + updateStatus + sendNda functions paste kar dein
    </script>
@endsection
