@extends($themesPath . '.bootstrap-3.master')

@section('body')
    <div class="row">
        <div class="col-md-3">
            @yield('groups')
            
            <h5>@yield('progress-bar-heading')</h5>
            @yield('progress-bar')
        </div>
        <div class="col-md-9">
            @yield('translations-table')
        </div>
    </div>
@endsection

@section('actions')
    @yield('back-button')
    
    @yield('save-button')
@endsection