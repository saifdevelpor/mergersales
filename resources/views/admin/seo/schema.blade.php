@extends('dashboard')

@section('title')
    <title>Schema Manager | Mergersales</title>
@endsection

@section('content')
    <div class="row g-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header"><h2 style="font-size:1.1rem;font-weight:700;">Pages</h2></div>
                <div class="card-body">
                    @foreach ($pages as $page)
                        <div class="border rounded p-2 mb-2">{{ $page->name }} - {{ $page->schema_type ?: 'Not set' }}</div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header"><h2 style="font-size:1.1rem;font-weight:700;">Listings</h2></div>
                <div class="card-body">
                    @foreach ($listings as $listing)
                        <div class="border rounded p-2 mb-2">{{ $listing->business_name }} - {{ $listing->schema_json ? 'Custom schema' : 'Default Offer schema' }}</div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
