@extends('way2translate::master')

@section('way2translate')
    <div class="container-fluid">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">@yield('heading')</h3>
            </div>
            <div class="panel-body">
                @yield('body')
            </div>

            @if (view()->hasSection('actions'))
                <div class="panel-footer">
                    @yield('actions')
                </div>
            @endif
        </div>
    </div>
@endsection
