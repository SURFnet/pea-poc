@extends('layouts.error')

@section('title', $message)

@section('content')
    <div>{{ $message }}</div>

    @if (!empty($reason))
        <div class="text-xl | font-normal">{{ $reason }}</div>
    @endif
@endsection
