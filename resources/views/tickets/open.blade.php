@extends('dashboard')

@section('title')
    <title>Open Tickets | Mergersales</title>
@endsection

@section('content')
    @include('tickets._table', ['title' => 'Open Tickets'])
@endsection
