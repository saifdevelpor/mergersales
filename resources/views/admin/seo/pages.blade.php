@extends('dashboard')

@section('title')
<title>Page Builder | Mergersales</title>
@endsection

@section('content')
<div class="row g-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h1 style="font-size:1.5rem;font-weight:700;">SEO Page Builder</h1>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header border-bottom">
                <h2 style="font-size:1.1rem;font-weight:700;" class="mb-1">Single Page Create</h2>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.seo.pages.store') }}" class="row g-3"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="col-md-6">
                        <label class="form-label">Page Name</label>
                        <input type="text" name="name" class="form-control" placeholder="UK Business Buyers" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Slug</label>
                        <input type="text" name="slug" class="form-control" placeholder="uk-business-buyers" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Heading</label>
                        <input type="text" name="heading" class="form-control"
                            placeholder="Buy Profitable Businesses in the UK">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="5"
                            placeholder="Write page body, SEO-focused intro, market details, CTA and supporting content."></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Keywords</label>
                        <textarea name="keywords" class="form-control" rows="3"
                            placeholder="business for sale uk, buy business uk, uk acquisition opportunities"></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Meta Title</label>
                        <input type="text" name="meta_title" class="form-control" maxlength="255">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Canonical URL</label>
                        <input type="text" name="canonical_url" class="form-control"
                            placeholder="https://example.com/pages/uk-business-buyers">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Meta Description</label>
                        <textarea name="meta_description" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">OG Title</label>
                        <input type="text" name="og_title" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">OG Image</label>
                        <input type="file" name="og_image" class="form-control" accept=".jpg,.jpeg,.png,.webp">
                    </div>
                    <div class="col-12">
                        <label class="form-label">OG Description</label>
                        <textarea name="og_description" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Schema Type</label>
                        <input type="text" name="schema_type" class="form-control" value="WebPage">
                    </div>
                    <div class="col-md-4 d-flex align-items-center">
                        <div class="form-check mt-4">
                            <input type="hidden" name="robots_index" value="0">
                            <input class="form-check-input" type="checkbox" name="robots_index" value="1" checked>
                            <label class="form-check-label">Allow indexing</label>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex align-items-center">
                        <div class="form-check mt-4">
                            <input type="hidden" name="robots_follow" value="0">
                            <input class="form-check-input" type="checkbox" name="robots_follow" value="1" checked>
                            <label class="form-check-label">Allow follow</label>
                        </div>
                    </div>
                    <div class="col-12 d-flex justify-content-end">
                        <button type="submit" class="btn" style="background:#CCAA57;color:#fff;">Create Page</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header border-bottom">
                <h2 style="font-size:1.1rem;font-weight:700;" class="mb-1">Bulk Template Generator</h2>
            </div>
            <div class="card-body">
                <div class="alert border-0" style="background:#fff8e8;color:#7a5a16;">
                    Placeholder use karein: <strong>{name}</strong>, <strong>{slug}</strong>,
                    <strong>{country}</strong>, <strong>{keywords}</strong>, <strong>{heading}</strong>
                </div>

                <form method="POST" action="{{ route('admin.seo.pages.bulk-store') }}" class="row g-3">
                    @csrf
                    <div class="col-md-6">
                        <label class="form-label">Template Name</label>
                        <input type="text" name="template_name" class="form-control"
                            value="Business Buyers in {country}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Template Heading</label>
                        <input type="text" name="template_heading" class="form-control"
                            value="Buy Profitable Businesses in {country}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Meta Title Template</label>
                        <input type="text" name="template_meta_title" class="form-control"
                            value="{heading} | Mergersales">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Meta Description Template</label>
                        <input type="text" name="template_meta_description" class="form-control"
                            value="Explore verified opportunities in {country}. Keywords: {keywords}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">OG Title Template</label>
                        <input type="text" name="template_og_title" class="form-control" value="{heading}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">OG Description Template</label>
                        <input type="text" name="template_og_description" class="form-control"
                            value="Mergersales helps you discover acquisition opportunities in {country}.">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Keywords Template</label>
                        <input type="text" name="template_keywords" class="form-control" value="{keywords}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Schema Type</label>
                        <input type="text" name="template_schema_type" class="form-control" value="WebPage">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Description Template</label>
                        <textarea name="template_description" class="form-control" rows="6"
                            required>{heading} page created for users looking to invest in {country}. This page targets {keywords} and helps buyers understand market opportunities, valuation expectations, and the best way to find acquisition-ready businesses through Mergersales.</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Bulk Rows</label>
                        <textarea name="bulk_rows" class="form-control" rows="8"
                            placeholder="Format: Name | Slug | Country | Keywords | Heading&#10;UK Buyers | uk-business-buyers | United Kingdom | business for sale uk, buy business uk | Buy Businesses in the UK&#10;Dubai Investors | dubai-business-investors | Dubai | business for sale dubai, dubai acquisition deals |"></textarea>

                    </div>
                    <div class="col-12 d-flex justify-content-end">
                        <button type="submit" class="btn" style="background:#CCAA57;color:#fff;">Generate Bulk
                            Pages</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header border-bottom">
                <h2 style="font-size:1.1rem;font-weight:700;" class="mb-1">Existing Pages</h2>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.seo.pages.bulk-destroy') }}" id="bulkDeletePagesForm">
                    @csrf
                    @method('DELETE')
                    <div id="bulkDeleteToolbar"
                        class="d-flex d-none align-items-center justify-content-between flex-wrap gap-3 mb-3 p-3 rounded"
                        style="background:#fff8e8;border:1px solid rgba(204,170,87,.25);">
                        <div class="fw-semibold" style="color:#7a5a16;">
                            <span id="selectedPagesCount">0</span> page(s) selected
                        </div>
                        <button type="submit" class="btn btn-sm" id="bulkDeleteBtn"
                            style="background: #CCAA57; color: white">
                            Delete Selected
                        </button>
                    </div>
                </form>

                @forelse ($pages as $page)
                @if ($loop->first)
                <div class="table-responsive">
                    <table class="table align-middle" id="myTable1">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:48px;">
                                    <input type="checkbox" class="form-check-input" id="selectAllPages">
                                </th>
                                <th>Page Name</th>
                                <th>Slug</th>
                                <th>Heading</th>
                                <th>Schema</th>
                                <th>Public URL</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @endif
                            <tr>
                                <td class="text-center">
                                    <input type="checkbox" class="form-check-input page-row-checkbox"
                                        data-page-id="{{ $page->id }}" value="{{ $page->id }}">
                                </td>
                                <td class="fw-semibold">{{ $page->name }}</td>
                                <td>{{ $page->slug }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($page->heading, 40, '...') }}</td>
                                <td>
                                    <span class="badge" style="background:#CCAA57;color:#fff;">
                                        {{ $page->schema_type ?: 'WebPage' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('seo.pages.show', $page->slug) }}" target="_blank"
                                        class="text-decoration-none">
                                        {{ \Illuminate\Support\Str::limit(route('seo.pages.show', $page->slug), 42, '...') }}
                                    </a>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-outline-dark dropdown-toggle"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            Action
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a class="dropdown-item"
                                                    href="{{ route('seo.pages.show', $page->slug) }}" target="_blank">
                                                    View
                                                </a>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item" data-bs-toggle="modal"
                                                    data-bs-target="#pageSeo{{ $page->id }}">
                                                    Edit
                                                </button>
                                            </li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <form method="POST"
                                                    action="{{ route('admin.seo.pages.destroy', $page) }}"
                                                    onsubmit="return confirm('Are you sure you want to delete this page?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">
                                                        Delete
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @if ($loop->last)
                        </tbody>
                    </table>
                </div>
                @endif

                <div class="modal fade" id="pageSeo{{ $page->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-xl modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <div>
                                    <h5 class="modal-title mb-1">Edit Page SEO</h5>
                                    <div class="text-muted small">{{ $page->name }}</div>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form method="POST" action="{{ route('admin.seo.pages.update', $page) }}"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="modal-body">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="form-label">Page Name</label>
                                            <input type="text" name="name" class="form-control"
                                                value="{{ $page->name }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Slug</label>
                                            <input type="text" name="slug" class="form-control"
                                                value="{{ $page->slug }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Route Name</label>
                                            <input type="text" name="route_name" class="form-control"
                                                value="{{ $page->route_name }}"
                                                placeholder="Optional for existing static route">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Heading</label>
                                            <input type="text" name="heading" class="form-control"
                                                value="{{ $page->heading }}">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Page Description</label>
                                            <textarea name="description" class="form-control"
                                                rows="5">{{ $page->description }}</textarea>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Keywords</label>
                                            <textarea name="keywords" class="form-control"
                                                rows="3">{{ $page->keywords }}</textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Meta Title</label>
                                            <input type="text" name="meta_title" class="form-control" maxlength="255"
                                                value="{{ $page->meta_title }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Canonical URL</label>
                                            <input type="text" name="canonical_url" class="form-control"
                                                value="{{ $page->canonical_url }}">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Meta Description</label>
                                            <textarea name="meta_description" class="form-control"
                                                rows="3">{{ $page->meta_description }}</textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">OG Title</label>
                                            <input type="text" name="og_title" class="form-control" maxlength="255"
                                                value="{{ $page->og_title }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">OG Image</label>
                                            <input type="file" name="og_image" class="form-control"
                                                accept=".jpg,.jpeg,.png,.webp">
                                            @if ($page->og_image)
                                            <div class="form-text mb-2">Current image:</div>
                                            <img src="{{ \App\Helpers\SeoHelper::imageUrl($page->og_image) }}"
                                                alt="Current OG image" class="img-fluid rounded border"
                                                style="max-height:120px;">
                                            @endif
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">OG Description</label>
                                            <textarea name="og_description" class="form-control"
                                                rows="3">{{ $page->og_description }}</textarea>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Schema Type</label>
                                            <input type="text" name="schema_type" class="form-control"
                                                value="{{ $page->schema_type }}">
                                        </div>
                                        <div class="col-md-4 d-flex align-items-center">
                                            <div class="form-check mt-4">
                                                <input type="hidden" name="robots_index" value="0">
                                                <input class="form-check-input" type="checkbox" name="robots_index"
                                                    value="1" {{ $page->robots_index ? 'checked' : '' }}>
                                                <label class="form-check-label">Allow indexing</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4 d-flex align-items-center">
                                            <div class="form-check mt-4">
                                                <input type="hidden" name="robots_follow" value="0">
                                                <input class="form-check-input" type="checkbox" name="robots_follow"
                                                    value="1" {{ $page->robots_follow ? 'checked' : '' }}>
                                                <label class="form-check-label">Allow follow</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn" style="background:#CCAA57;color:#fff;">Update
                                        Page</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-muted">Abhi tak koi custom page create nahi hua.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let attempts = 0;

    function initBulkDeleteSelection() {
        if (!window.jQuery) {
            attempts += 1;

            if (attempts < 30) {
                window.setTimeout(initBulkDeleteSelection, 200);
            }

            return;
        }

        const $ = window.jQuery;
        const $bulkForm = $('#bulkDeletePagesForm');
        const $toolbar = $('#bulkDeleteToolbar');
        const $count = $('#selectedPagesCount');
        const $selectAll = $('#selectAllPages');

        if (!$bulkForm.length || !$selectAll.length) {
            return;
        }

        if ($bulkForm.data('bulk-selection-ready')) {
            return;
        }

        $bulkForm.data('bulk-selection-ready', true);

        const selectedPageIds = new Set();

        function getRowCheckboxes() {
            return $('.page-row-checkbox');
        }

        function syncCheckboxesFromSelection() {
            getRowCheckboxes().each(function() {
                const pageId = String($(this).data('page-id'));
                $(this).prop('checked', selectedPageIds.has(pageId));
            });
        }

        function updateBulkDeleteState() {
            const $allCheckboxes = getRowCheckboxes();
            const selectedCount = selectedPageIds.size;
            const totalCount = $allCheckboxes.length;
            const totalSelectedCount = $allCheckboxes.filter(function() {
                return selectedPageIds.has(String($(this).data('page-id')));
            }).length;

            $count.text(selectedCount);
            $toolbar.toggleClass('d-none', selectedCount === 0);
            $toolbar.css('display', selectedCount === 0 ? 'none' : 'flex');

            $selectAll.prop('checked', totalCount > 0 && totalSelectedCount === totalCount);
            $selectAll.prop('indeterminate', totalSelectedCount > 0 && totalSelectedCount < totalCount);
        }

        $(document).on('change.bulkPages', '.page-row-checkbox', function() {
            const pageId = String($(this).data('page-id'));

            if ($(this).is(':checked')) {
                selectedPageIds.add(pageId);
            } else {
                selectedPageIds.delete(pageId);
            }

            updateBulkDeleteState();
        });

        $(document).on('change.bulkPages', '#selectAllPages', function() {
            const shouldCheck = $(this).is(':checked');

            getRowCheckboxes().each(function() {
                const pageId = String($(this).data('page-id'));

                if (shouldCheck) {
                    selectedPageIds.add(pageId);
                } else {
                    selectedPageIds.delete(pageId);
                }
            });

            syncCheckboxesFromSelection();
            updateBulkDeleteState();
        });

        const waitForDataTable = window.setInterval(function() {
            const dataTable = $.fn.DataTable && $.fn.DataTable.isDataTable('#myTable1') ?
                $('#myTable1').DataTable() :
                null;

            if (!dataTable) {
                return;
            }

            window.clearInterval(waitForDataTable);

            dataTable.on('draw', function() {
                syncCheckboxesFromSelection();
                updateBulkDeleteState();
            });

            syncCheckboxesFromSelection();
            updateBulkDeleteState();
        }, 200);

        $bulkForm.on('submit.bulkPages', function(e) {
            e.preventDefault();

            const selectedCount = selectedPageIds.size;

            if (!selectedCount) {
                return;
            }

            Swal.fire({
                icon: 'warning',
                title: 'Delete selected pages?',
                text: `You are about to delete ${selectedCount} selected page(s). This action cannot be undone.`,
                showCancelButton: true,
                confirmButtonColor: '#CCAA57',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (!result.isConfirmed) {
                    return;
                }

                $bulkForm.find('input[name="page_ids[]"]').remove();

                Array.from(selectedPageIds).forEach((pageId) => {
                    $('<input>')
                        .attr('type', 'hidden')
                        .attr('name', 'page_ids[]')
                        .val(pageId)
                        .appendTo($bulkForm);
                });

                $bulkForm.off('submit.bulkPages');
                $bulkForm.trigger('submit');
            });
        });

        syncCheckboxesFromSelection();
        updateBulkDeleteState();
    }

    initBulkDeleteSelection();
});
</script>

@endsection