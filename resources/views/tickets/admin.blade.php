@extends('dashboard')

@section('title')
    <title>All Tickets | Mergersales</title>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3>All Tickets</h3>
        </div>

        <div class="table">
            <table class="table" id="myTable1">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Ticket</th>
                        <th>Status</th>
                        <th>Files</th>
                        <th>Update Status</th>
                        <th>View</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tickets as $i => $t)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $t->user?->name }}</td>
                            <td>{{ $t->ticket_no }}</td>
                            <td>
                                @php
                                    $status = strtolower($t->status);
                                @endphp

                                @if ($status == 'open')
                                    <span class="badge" style="background:#FFF3CD;color:#7A5B00;">
                                        <i class="ti ti-folder-open me-1"></i> Open
                                    </span>
                                @elseif($status == 'under_review')
                                    <span class="badge" style="background:#E7F1FF;color:#0B5ED7;">
                                        <i class="ti ti-search me-1"></i> Under Review
                                    </span>
                                @elseif($status == 'rejected')
                                    <span class="badge" style="background:#FDECEC;color:#B42318;">
                                        <i class="ti ti-circle-x me-1"></i> Rejected
                                    </span>
                                @elseif($status == 'completed')
                                    <span class="badge" style="background:#E8FFF3;color:#027A48;">
                                        <i class="ti ti-circle-check me-1"></i> Completed
                                    </span>
                                @elseif($status == 'closed')
                                    <span class="badge" style="background:#F2F4F7;color:#344054;">
                                        <i class="ti ti-lock me-1"></i> Closed
                                    </span>
                                @else
                                    <span class="badge bg-light text-dark">
                                        {{ strtoupper(str_replace('_', ' ', $t->status)) }}
                                    </span>
                                @endif
                            </td>

                            <td>{{ $t->attachments_count }}</td>

                            <td>
                                <form method="POST" action="{{ route('ticket.updateStatus', e_id($t->id)) }}">
                                    @csrf
                                    <select name="status" class="form-control">
                                        <option value="open" {{ $t->status == 'open' ? 'selected' : '' }}>Open</option>
                                        <option value="closed" {{ $t->status == 'closed' ? 'selected' : '' }}>Close</option>

                                        <option value="under_review" {{ $t->status == 'under_review' ? 'selected' : '' }}>
                                            Under
                                            Review</option>
                                        <option value="completed" {{ $t->status == 'completed' ? 'selected' : '' }}>
                                            Completed
                                        </option>
                                        <option value="rejected" {{ $t->status == 'rejected' ? 'selected' : '' }}>Rejected
                                        </option>
                                    </select>
                                    <button class="btn btn-sm mt-1" style="background:#CCAA57;color:white;">Update</button>
                                </form>
                            </td>

                            <td>
                                <a class="btn btn-sm" style="color:#CCAA57;" href="{{ route('tickets.show', e_id($t->id)) }}">
                                    <i class="ti ti-eye"></i>
                                </a>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
