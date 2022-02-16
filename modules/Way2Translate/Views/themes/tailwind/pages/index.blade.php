@extends($themesPath . '.tailwind.master')

@section('body')
    <div class="flex justify-between">
        <div class="w-3/6">
            <h3 class="text-md leading-6 font-medium">@yield('translations-heading')</h3>

            <div class="mt-4">
                @yield('translations-table')
            </div>
        </div>
        <div class="w-2/6">
            <h3 class="text-md leading-6 font-medium">@yield('new-language-heading')</h3>

            <div class="mt-4">
                @yield('new-language-form')
            </div>
        </div>
    </div>
@endsection
