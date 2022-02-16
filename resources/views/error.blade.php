@extends('layouts.error')

@section('title', $message)

@section('content')
    <div>{{ $message }}</div>
@endsection
