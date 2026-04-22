@extends('dashboard')

@section('title')
    <title>Blog SEO | Mergersales</title>
@endsection

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header border-bottom">
            <h1 style="font-size:1.4rem;font-weight:700;">Manage Blog SEO</h1>
            <p class="text-muted mb-0">Blog titles, slug, SEO title, description, OG image aur featured image alt text yahan manage karein.</p>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table" id="myTable2">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Slug</th>
                            <th>SEO Title</th>
                            <th>Image Alt</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($blogs as $blog)
                            <tr>
                                <td>{{ $blog->title ?: 'Untitled' }}</td>
                                <td>{{ $blog->slug }}</td>
                                <td>{{ $blog->seo_title ?: '—' }}</td>
                                <td>{{ $blog->featured_image_alt ?: '—' }}</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-dark" data-bs-toggle="modal" data-bs-target="#blogSeo{{ $blog->id }}">
                                        Edit SEO
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $blogs->links() }}
        </div>
    </div>

    @foreach ($blogs as $blog)
        <div class="modal fade" id="blogSeo{{ $blog->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form method="POST" action="{{ route('admin.seo.blogs.update', $blog) }}" class="modal-content" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">SEO: {{ $blog->title ?: 'Untitled Blog' }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Title</label>
                                <input type="text" name="title" class="form-control" value="{{ $blog->title }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Slug</label>
                                <input type="text" name="slug" class="form-control" value="{{ $blog->slug }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">SEO Title</label>
                                <input type="text" name="seo_title" class="form-control" value="{{ $blog->seo_title }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Featured Image Alt</label>
                                <input type="text" name="featured_image_alt" class="form-control" value="{{ $blog->featured_image_alt }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">OG Image</label>
                                <input type="file" name="og_image" class="form-control" accept=".jpg,.jpeg,.png,.webp">
                                @if ($blog->og_image)
                                    <div class="form-text mb-2">Current image:</div>
                                    <img src="{{ \App\Helpers\SeoHelper::imageUrl($blog->og_image) }}" alt="Current OG image" class="img-fluid rounded border" style="max-height:120px;">
                                @endif
                            </div>
                            <div class="col-12">
                                <label class="form-label">SEO Description</label>
                                <textarea name="seo_description" class="form-control" rows="3">{{ $blog->seo_description }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn" style="background:#CCAA57;color:#fff;">Save Blog SEO</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach
@endsection
