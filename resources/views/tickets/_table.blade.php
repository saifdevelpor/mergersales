@php
    $statusColors = [
        'open' => '#3498DB',
        'under_review' => '#FF8D1A',
        'completed' => '#28A745',
        'rejected' => '#E74C3C',
        'closed' => '#6C757D',
    ];
@endphp

<div class="card">
    <div class="card-header border-bottom d-flex justify-content-between align-items-center">
        <h3 class="mb-0">{{ $title ?? 'Tickets' }}</h3>

        {{-- <div class="d-flex gap-2">
            <a href="{{ route('tickets.open') }}" class="btn btn-sm btn-light">Open</a>
            <a href="{{ route('tickets.under_review') }}" class="btn btn-sm btn-light">Under Review</a>
            <a href="{{ route('tickets.completed') }}" class="btn btn-sm btn-light">Completed</a>
            <a href="{{ route('tickets.closed') }}" class="btn btn-sm btn-light">Closed</a>
        </div> --}}
    </div>

    <div class="table">
        <table class="table" id="myTable1">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Ticket No</th>
                    <th>User</th>
                    <th>Subject</th>
                    <th>Status</th>
                    <th>Files</th>
                    <th>View</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tickets as $i => $t)
                    @php $color = $statusColors[$t->status] ?? '#6C757D'; @endphp
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $t->ticket_no }}</td>
                        <td>
                            <div class="fw-semibold">{{ $t->user?->name }}</div>
                            <div class="small text-muted">{{ $t->user?->email }}</div>
                        </td>
                        <td style="max-width:320px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                            {{ $t->subject }}
                        </td>
                        <td>
                            <span class="badge" style="background:{{ $color }}; color:#fff;">
                                {{ strtoupper(str_replace('_', ' ', $t->status)) }}
                            </span>
                        </td>
                        <td>{{ $t->attachments_count ?? 0 }}</td>
                        <td>
                            <a href="{{ route('tickets.show', $t->id) }}" class="btn btn-sm"
                                style="background:#CCAA57;color:white;">
                                Open
                            </a>
                        </td>
                    </tr>
                @empty
                @endforelse
            </tbody>
        </table>
    </div>
</div>
