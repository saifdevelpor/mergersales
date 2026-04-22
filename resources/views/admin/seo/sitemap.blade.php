@extends('dashboard')

@section('title')
    <title>Sitemap Manager | Mergersales</title>
@endsection

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header">
            <h1 style="font-size:1.4rem;font-weight:700;">Sitemap Generator</h1>
        </div>
        <div class="card-body">
            <p class="mb-2">Public URL: <a href="{{ route('seo.sitemap') }}" target="_blank">{{ route('seo.sitemap') }}</a></p>
            <p class="text-muted mb-3">
                @if ($exists)
                    Sitemap generated
                @else
                    Sitemap not generated yet
                @endif
            </p>
            <form method="POST" action="{{ route('admin.seo.sitemap.generate') }}">
                @csrf
                <button type="submit" class="btn" style="background:#CCAA57;color:#fff;">Generate Sitemap</button>
            </form>
        </div>
    </div>
@endsection
