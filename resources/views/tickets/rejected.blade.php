@extends('dashboard')

@section('title')
    <title>Rejected Tickets | Mergersales</title>
@endsection

@section('content')
    @include('tickets._table', ['title' => 'Rejected Tickets'])
@endsection
