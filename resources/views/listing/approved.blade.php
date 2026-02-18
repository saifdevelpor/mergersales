@extends('dashboard')

@section('title')
    <title>Approved Business | Mergersales</title>
@endsection

@section('content')
<div class="container">
    <h4 class="mb-3">Approved Businesses</h4>

    @include('listing.partial.table', ['listings' => $listings])
</div>
@endsection
