@extends($themesPath . '.bootstrap-3.master')

@section('body')
    <div class="row">
        <div class="col-md-8">
            <h3>@yield('translations-heading')</h3>
            
            @yield('translations-table')
        </div>
        <div class="col-md-4">
            <h3>@yield('new-language-heading')</h3>
            
            @yield('new-language-form')
        </div>
    </div>
@endsection