@extends('layouts.admin.page-wrapper')

@section('page-body')
    @if (session()->has('flash_notification'))
        @include('flash::message')
    @endif

    @yield('content')
@endsection
