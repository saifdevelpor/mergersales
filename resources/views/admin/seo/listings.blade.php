@extends('dashboard')

@section('title')
    <title>Listing SEO | Mergersales</title>
@endsection

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header border-bottom">
            <h1 style="font-size:1.4rem;font-weight:700;">Manage Listing SEO</h1>
            <p class="text-muted mb-0">Slugs, SEO titles, descriptions, focus keywords, OG image aur custom schema yahan update karein.</p>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table" id="myTable1">
                    <thead>
                        <tr>
                            <th>Business</th>
                            <th>Slug</th>
                            <th>SEO Title</th>
                            <th>Keyword</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($listings as $listing)
                            <tr>
                                <td>{{ $listing->business_name }}</td>
                                <td>{{ $listing->slug }}</td>
                                <td>{{ $listing->seo_title ?: '—' }}</td>
                                <td>{{ $listing->focus_keyword ?: '—' }}</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-dark" data-bs-toggle="modal" data-bs-target="#listingSeo{{ $listing->id }}">
                                        Edit SEO
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $listings->links() }}
        </div>
    </div>

    @foreach ($listings as $listing)
        <div class="modal fade" id="listingSeo{{ $listing->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form method="POST" action="{{ route('admin.seo.listings.update', $listing) }}" class="modal-content" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">SEO: {{ $listing->business_name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">SEO Title</label>
                                <input type="text" name="seo_title" class="form-control" value="{{ $listing->seo_title }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Slug</label>
                                <input type="text" name="slug" class="form-control" value="{{ $listing->slug }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Focus Keyword</label>
                                <input type="text" name="focus_keyword" class="form-control" value="{{ $listing->focus_keyword }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">OG Image</label>
                                <input type="file" name="og_image" class="form-control" accept=".jpg,.jpeg,.png,.webp">
                                @if ($listing->og_image)
                                    <div class="form-text mb-2">Current image:</div>
                                    <img src="{{ \App\Helpers\SeoHelper::imageUrl($listing->og_image) }}" alt="Current OG image" class="img-fluid rounded border" style="max-height:120px;">
                                @endif
                            </div>
                            <div class="col-12">
                                <label class="form-label">SEO Description</label>
                                <textarea name="seo_description" class="form-control" rows="3">{{ $listing->seo_description }}</textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Schema JSON</label>
                                <textarea name="schema_json" class="form-control" rows="8">{{ $listing->schema_json }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn" style="background:#CCAA57;color:#fff;">Save Listing SEO</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach
@endsection
