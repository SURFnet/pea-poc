@extends($themesPath . '.tailwind.master')

@section('body')
    <p class="text-red-800">
        <strong>@yield('text')</strong>
    </p>
@endsection
