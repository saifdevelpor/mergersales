@extends('dashboard')

@section('title')
    <title>Closed Tickets | Mergersales</title>
@endsection

@section('content')
    @include('tickets._table', ['title' => 'Closed Tickets'])
@endsection
