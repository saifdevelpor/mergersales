@if ($listings->count())
    <div class="row g-3">

        @foreach ($listings as $listing)
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                <div class="card h-100 shadow-sm">

                    {{-- Business Image --}}
                    @if ($listing->business_img)
                        <img src="{{ 'http://localhost/Mergersales/storage/app/public/' . $listing->business_img }}"
                            class="card-img-top" style="height:160px; object-fit:cover;"
                            alt="{{ $listing->business_name }}">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center" style="height:160px;">
                            <span class="text-muted">No Image</span>
                        </div>
                    @endif

                    <div class="card-body d-flex flex-column">

                        {{-- Business Name --}}
                        <h6 class="fw-bold mb-1">
                            {{ $listing->business_name }}
                        </h6>

                        {{-- Industry --}}
                        <small class="text-muted">
                            {{ optional($listing->industry)->name ?? 'N/A' }}
                        </small>

                        {{-- Country --}}
                        <div class="mt-1">
                            <small>
                                <i class="ti ti-map-pin"></i> {{ $listing->country }}
                            </small>
                        </div>

                        {{-- Status Badge --}}
                        <div class="mt-2">
                            @if ($listing->status === 'Approved')
                                <span class="badge bg-success">Approved</span>
                            @elseif ($listing->status === 'Pending')
                                <span class="badge bg-warning">Pending</span>
                            @else
                                <span class="badge bg-danger">Rejected</span>
                            @endif
                        </div>

                        {{-- Spacer --}}
                        <div class="mt-auto"></div>

                        {{-- Actions --}}
                        <div class="d-flex gap-2 mt-3">

                            {{-- View --}}
                            @if ($listing->status === 'Approved' || auth()->user()->role === 'Admin')
                                <a href="{{ route('listings.show', e_id($listing->id)) }}"
                                    class="btn btn-sm btn-outline-primary w-100">
                                    View
                                </a>
                            @endif

                            {{-- Admin Actions --}}
                            @if (auth()->user()->role === 'Admin')
                                @if (in_array($listing->status, ['Pending', 'Rejected']))
                                    <form action="{{ route('listings.approve', e_id($listing->id)) }}" method="POST"
                                        class="w-100">
                                        @csrf
                                        @method('PATCH')
                                        <button class="btn btn-sm btn-success w-100">
                                            Approve
                                        </button>
                                    </form>
                                @endif

                                @if (in_array($listing->status, ['Pending', 'Approved']))
                                    <form action="{{ route('listings.reject', e_id($listing->id)) }}" method="POST"
                                        class="w-100">
                                        @csrf
                                        @method('PATCH')
                                        <button class="btn btn-sm btn-warning w-100">
                                            Reject
                                        </button>
                                    </form>
                                @endif
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        @endforeach

    </div>
@else
    <div class="alert alert-info">
        No businesses found.
    </div>
@endif
