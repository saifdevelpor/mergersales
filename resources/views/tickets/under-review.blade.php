@extends('dashboard')

@section('title')
    <title>Under Review Tickets | Mergersales</title>
@endsection

@section('content')
    @include('tickets._table', ['title' => 'Under Review Tickets'])
@endsection
