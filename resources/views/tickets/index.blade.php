@extends('dashboard')

@section('title')
    <title>My Tickets | Mergersales</title>
@endsection

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 style="margin:0;">My Tickets</h3>

            <button class="btn" style="color:white; background:#CCAA57; padding:10px 20px; border-radius:5px; border:none;"
                data-bs-toggle="modal" data-bs-target="#createTicket">
                Create Ticket
            </button>
        </div>

        <div class="card-body">
            {{-- @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <div class="table">
            <table class="table" id="myTable1">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Ticket</th>
                        <th>Subject</th>
                        <th>Status</th>
                        <th>Files</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tickets as $i => $t)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $t->ticket_no }}</td>
                            <td>{{ $t->subject }}</td>
                            @php
                                $statusClass = [
                                    'open' => 'bg-primary',
                                    'under_review' => 'bg-warning text-dark',
                                    'completed' => 'bg-success',
                                    'rejected' => 'bg-danger',
                                    'closed' => 'bg-secondary',
                                ];
                            @endphp

                            <td>
                                <span class="badge {{ $statusClass[$t->status] ?? 'bg-secondary' }}">
                                    {{ strtoupper(str_replace('_', ' ', $t->status)) }}
                                </span>
                            </td>

                            <td>{{ $t->attachments_count }}</td>
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

    {{-- CREATE MODAL --}}
    <div class="modal fade" id="createTicket" tabindex="-1">
        <div class="modal-dialog modal-lg">

            <form method="POST" action="{{ route('tickets.store') }}" enctype="multipart/form-data" class="modal-content">

                @csrf

                {{-- HEADER --}}
                <div class="modal-header">
                    <h5 class="modal-title fw-semibold">
                        <i class="ti ti-ticket me-2"></i>
                        Create Support Ticket
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>


                {{-- BODY --}}
                <div class="modal-body">

                    {{-- SUBJECT --}}
                    <div class="mb-3">
                        <label class="form-label fw-medium">
                            Ticket Subject <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="subject" class="form-control" placeholder="Enter Subject" required>
                    </div>


                    {{-- CATEGORY + PRIORITY --}}
                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">
                                Category <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="category" class="form-control" placeholder="Enter Some Category"
                                required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">
                                Priority
                            </label>
                            <select name="priority" class="form-control">
                                <option value="">Select Priority</option>
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>

                    </div>


                    {{-- MESSAGE --}}
                    <div class="mb-3">
                        <label class="form-label fw-medium">
                            Describe Your Problem <span class="text-danger">*</span>
                        </label>
                        <textarea name="message" rows="5" class="form-control"
                            placeholder="Explain the issue in detail so our admin can help you faster..." required></textarea>
                    </div>


                    {{-- ATTACHMENTS --}}
                    <div class="mb-3">
                        <label class="form-label fw-medium">
                            Upload Screenshot / Files
                        </label>

                        <input type="file" name="attachments[]" class="form-control" multiple
                            accept=".jpg,.jpeg,.png,.webp,.pdf">

                        <small class="text-muted">
                            You can upload Multipal files (JPG, PNG, WEBP, PDF — max 5MB each)
                        </small>
                    </div>


                    {{-- FOOTER BUTTONS --}}
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                            Cancel
                        </button>

                        <button type="submit" class="btn" style="background:#CCAA57;color:white;padding:8px 25px;">
                            <i class="ti ti-send me-1"></i>
                            Submit Ticket
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Ticket Created!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#3A6EF2'
            });
        </script>
    @endif


@endsection
