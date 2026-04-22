@extends('dashboard')

@section('title')
    <title>Blogs | Mergersales</title>
@endsection

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

@section('content')

    {{-- ✅ VALIDATION ERRORS SHOW (IMPORTANT) --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-header border-bottom d-flex justify-content-between align-items-center">
            <h1 style="font-size:1.5rem;font-weight:600;">Blogs</h1>

            <button class="btn-employee"
                style="color:white; background:#CCAA57; padding:10px 20px; border-radius:5px; border: none;"
                data-bs-toggle="modal" data-bs-target="#createBlogModal">
                <i class="ti ti-plus"></i> Create Blog
            </button>
        </div>

        {{-- TABLE --}}
        <div class="card-datatable table-responsive text-nowrap">
            <table class="table" id="myTable1">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Slug</th>
                        <th>SEO</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($blogs as $index => $blog)
                        <tr>
                            <td>{{ $index + 1 }}</td>

                            {{-- IMAGE --}}
                            <td>
                                @if ($blog->image)
                                    <img src="{{ asset($blog->image) }}"
                                        style="width:55px;height:55px;object-fit:cover;border-radius:8px;">
                                @else
                                    <img src="{{ asset('assets/img/placeholder.png') }}"
                                        style="width:55px;height:55px;object-fit:cover;border-radius:8px;opacity:.6;">
                                @endif
                            </td>

                            <td style="max-width:240px; white-space:normal;">
                                <div style="font-weight:600;">{{ $blog->title ?: 'Untitled Blog' }}</div>
                                <div style="color:#6b7280; font-size:13px;">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($blog->details), 70) }}
                                </div>
                            </td>

                            <td>
                                <code>{{ $blog->slug ?: 'auto-generated' }}</code>
                            </td>

                            <td style="max-width:280px; white-space:normal;">
                                <div><strong>SEO Title:</strong> {{ $blog->seo_title ?: 'Default title' }}</div>
                                <div><strong>Meta:</strong> {{ \Illuminate\Support\Str::limit($blog->seo_description ?: 'Default description', 70) }}</div>
                            </td>

                            <td>{{ $blog->created_at?->format('d M Y') }}</td>

                            {{-- ACTIONS --}}
                            <td>
                                <div class="dropdown">
                                    <button class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="ti ti-dots-vertical"></i>
                                    </button>

                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" data-bs-toggle="modal"
                                            data-bs-target="#viewBlog{{ $blog->id }}">
                                            <i class="ti ti-eye me-1"></i> View
                                        </a>

                                        <a class="dropdown-item" data-bs-toggle="modal"
                                            data-bs-target="#editBlog{{ $blog->id }}">
                                            <i class="ti ti-pencil me-1"></i> Edit
                                        </a>

                                        <a class="dropdown-item text-danger" onclick="confirmDelete({{ $blog->id }})">
                                            <i class="ti ti-trash me-1"></i> Delete
                                        </a>

                                        <form id="delete-form-{{ $blog->id }}"
                                            action="{{ route('blogs.destroy', e_id($blog->id)) }}" method="POST"
                                            style="display:none">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>

    {{-- VIEW MODAL --}}
    @foreach ($blogs as $blog)
        <div class="modal fade" id="viewBlog{{ $blog->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">View Blog</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="text-center mt-2">
                            @if ($blog->image)
                                <a href="{{ asset($blog->image) }}" target="_blank" title="Open Image">
                                    <img src="{{ asset($blog->image) }}" class="rounded shadow-sm"
                                        style="max-width:100%; height:auto; cursor:pointer;">
                                </a>
                            @else
                                <img src="{{ asset('assets/img/placeholder.png') }}" style="width:120px;opacity:.6;">
                            @endif
                        </div>

                        <hr>

                        <div class="p-2" style="line-height:1.9;">
                            {!! $blog->details !!}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    @endforeach

    {{-- EDIT MODALS --}}
    @foreach ($blogs as $blog)
        <div class="modal fade" id="editBlog{{ $blog->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form method="POST" action="{{ route('blogs.update', e_id($blog->id)) }}" enctype="multipart/form-data"
                    class="modal-content ckeditor-form">
                    @csrf
                    {{-- ✅ Add method spoofing (recommended) --}}
                    @method('POST')

                    <div class="modal-header">
                        <h5 class="modal-title">Edit Blog</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Blog Title</label>
                                <input type="text" class="form-control" name="title"
                                    value="{{ old('title', $blog->title) }}" placeholder="Enter blog title">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Slug</label>
                                <input type="text" class="form-control" name="slug"
                                    value="{{ old('slug', $blog->slug) }}" placeholder="auto-generated-from-title">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Image</label>
                            <input type="file" class="form-control" name="image">

                            @if ($blog->image)
                                <div class="mt-2">
                                    <img src="{{ asset($blog->image) }}" class="rounded"
                                        style="width:90px;height:90px;object-fit:cover;">
                                </div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label>Contact / Body:</label>
                            <textarea name="details" class="form-control ckeditor" rows="10">{{ $blog->details }}</textarea>
                        </div>

                        <hr>

                        <h6 style="font-weight:600;">SEO Fields</h6>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">SEO Title</label>
                                <input type="text" class="form-control" name="seo_title"
                                    value="{{ old('seo_title', $blog->seo_title) }}" maxlength="255">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Featured Image Alt</label>
                                <input type="text" class="form-control" name="featured_image_alt"
                                    value="{{ old('featured_image_alt', $blog->featured_image_alt) }}" maxlength="255">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">SEO Description</label>
                            <textarea class="form-control" name="seo_description" rows="3"
                                maxlength="1000">{{ old('seo_description', $blog->seo_description) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">OG Image URL/Path</label>
                            <input type="text" class="form-control" name="og_image"
                                value="{{ old('og_image', $blog->og_image) }}"
                                placeholder="uploads/blogs/example.webp or https://...">
                        </div>

                        <div class="d-grid mt-3">
                            <button type="submit" class="btn" style="background:#CCAA57; color:white;">
                                Update Blog
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    @endforeach

    {{-- CREATE MODAL --}}
    <div class="modal fade" id="createBlogModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form method="POST" action="{{ route('blogs.store') }}" enctype="multipart/form-data"
                class="modal-content ckeditor-form">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Create Blog</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Blog Title</label>
                            <input type="text" class="form-control" name="title"
                                value="{{ old('title') }}" placeholder="Enter blog title">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Slug</label>
                            <input type="text" class="form-control" name="slug"
                                value="{{ old('slug') }}" placeholder="auto-generated-from-title">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Image *</label>
                        <input type="file" class="form-control" name="image" required>
                    </div>

                    <div class="mb-3">
                        <label>Contact / Details</label>
                        <textarea name="details" class="form-control ckeditor" rows="50"></textarea>
                    </div>

                    <hr>

                    <h6 style="font-weight:600;">SEO Fields</h6>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">SEO Title</label>
                            <input type="text" class="form-control" name="seo_title"
                                value="{{ old('seo_title') }}" maxlength="255">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Featured Image Alt</label>
                            <input type="text" class="form-control" name="featured_image_alt"
                                value="{{ old('featured_image_alt') }}" maxlength="255">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">SEO Description</label>
                        <textarea class="form-control" name="seo_description" rows="3" maxlength="1000">{{ old('seo_description') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">OG Image URL/Path</label>
                        <input type="text" class="form-control" name="og_image"
                            value="{{ old('og_image') }}" placeholder="uploads/blogs/example.webp or https://...">
                    </div>

                    <div class="d-grid mt-3">
                        <button type="submit" class="btn" style="background:#CCAA57; color:white; font-weight:500;">
                            Create Blog
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>

    {{-- SWEETALERT --}}
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    @if (session('success'))
        <script>
            swal({
                title: "Success!",
                text: "{{ session('success') }}",
                icon: "success",
                button: "OK",
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            swal({
                title: "Error!",
                text: "{{ session('error') }}",
                icon: "error",
                button: "OK",
            });
        </script>
    @endif

    <script>
        function confirmDelete(id) {
            swal({
                title: "Are you sure?",
                text: "This blog will be deleted permanently",
                icon: "warning",
                buttons: true,
                dangerMode: true
            }).then((willDelete) => {
                if (willDelete) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>

    {{-- ✅ CKEditor (Single Clean Init) --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            function slugify(value) {
                return (value || '')
                    .toString()
                    .toLowerCase()
                    .trim()
                    .replace(/[^a-z0-9\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-');
            }

            document.querySelectorAll('form').forEach(function(form) {
                const titleInput = form.querySelector('input[name="title"]');
                const slugInput = form.querySelector('input[name="slug"]');

                if (!titleInput || !slugInput) {
                    return;
                }

                titleInput.addEventListener('input', function() {
                    if (!slugInput.dataset.touched || slugInput.value.trim() === '') {
                        slugInput.value = slugify(titleInput.value);
                    }
                });

                slugInput.addEventListener('input', function() {
                    slugInput.dataset.touched = 'true';
                });
            });
        });
    </script>

    <script src="https://cdn.ckeditor.com/ckeditor5/38.1.0/classic/ckeditor.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let editors = {};

            // Har textarea ke liye CKEditor init karo
            document.querySelectorAll('.ckeditor').forEach((textarea) => {
                ClassicEditor.create(textarea).then(editor => {
                    editors[textarea.name] = editor;
                }).catch(error => console.error(error));
            });

            // Form submit hone se pehle editor ka data textarea me daalna
            document.querySelectorAll(".ckeditor-form").forEach(form => {
                form.addEventListener("submit", function(e) {
                    for (const name in editors) {
                        const editor = editors[name];
                        if (editor) {
                            // Important: editor.sourceElement = wo original textarea hai
                            editor.sourceElement.value = editor.getData();
                        }
                    }
                });
            });
        });
    </script>

@endsection
