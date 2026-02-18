@extends('dashboard')

@section('title')
    <title>Details Ticket | Mergersales</title>
@endsection

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h4 style="margin:0;">Ticket: {{ $ticket->ticket_no }}</h4>
                <div class="text-muted">{{ $ticket->subject }}</div>
            </div>
            <a href="{{ url()->previous() }}" class="btn btn-sm" style="background:#CCAA57;color:white;">
                Back
            </a>

        </div>

        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <p><b>Category:</b> {{ $ticket->category }}</p>
            <p><b>Priority:</b> {{ strtoupper($ticket->priority) }}</p>
            <p><b>Status:</b> {{ strtoupper(str_replace('_', ' ', $ticket->status)) }}</p>

            <hr>
            <h6>Message</h6>
            <div class="border rounded p-3" style="background:#fafafa;white-space:pre-wrap;">
                {{ $ticket->message }}
            </div>

            <hr>
            <h6>Attachments</h6>
            @if ($ticket->attachments->count())
                <div class="row">
                    @foreach ($ticket->attachments as $a)
                        @php
                            $url = 'http://localhost/Mergersales/storage/app/public/' . $a->file_path;
                            $isImg = str_starts_with($a->mime ?? '', 'image/');
                        @endphp

                        <div class="col-md-3 mb-3">
                            <div class="border rounded p-2">
                                @if ($isImg)
                                    <a href="{{ $url }}" target="_blank">
                                        <img src="{{ $url }}"
                                            style="width:100%;height:140px;object-fit:cover;border-radius:6px;">
                                    </a>
                                @else
                                    <div class="text-muted small">PDF/File</div>
                                    <a href="{{ $url }}"
                                        target="_blank">{{ $a->original_name ?? 'Download' }}</a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-muted">No attachments uploaded.</div>
            @endif
        </div>
    </div>
@endsection
