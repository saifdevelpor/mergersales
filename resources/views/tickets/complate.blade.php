@extends('dashboard')

@section('title')
    <title>Completed Tickets | Mergersales</title>
@endsection

@section('content')
    @include('tickets._table', ['title' => 'Completed Tickets'])
@endsection
