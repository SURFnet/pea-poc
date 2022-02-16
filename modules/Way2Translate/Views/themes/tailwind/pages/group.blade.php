@extends($themesPath . '.tailwind.master')

@section('body')
    <div class="flex justify-between">
        <div class="w-1/6">
            @yield('groups')

            <h3 class="text-md leading-6 font-medium | mt-6">
                @yield('progress-bar-heading')
            </h3>

            @yield('progress-bar')
        </div>
        <div class="w-4/6">
            @yield('translations-table')
        </div>
    </div>
@endsection

@section('actions')
    @yield('back-button')

    @yield('save-button')
@endsection
