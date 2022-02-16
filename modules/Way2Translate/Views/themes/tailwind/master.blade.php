@extends('way2translate::master')

@section('way2translate')
    <div class="min-h-screen bg-gray-100">
        <main class="container | py-6">
            <h1 class="text-2xl leading-8 font-medium">
                {{ config('app.name') }}
            </h1>

            <div class="bg-white overflow-hidden sm:rounded-lg sm:shadow | mt-6">
                <div class="bg-white px-4 py-5 border-b border-gray-200 sm:px-6">
                    <div class="-ml-4 -mt-2 flex items-center justify-between flex-wrap sm:flex-nowrap">
                        <div class="ml-4 mt-2">
                            <h3 class="text-lg leading-6 font-medium">
                                @yield('heading')
                            </h3>
                        </div>

                        @if (view()->hasSection('actions'))
                            <div class="ml-4 mt-2 flex-shrink-0 | space-x-2">
                                @yield('actions')
                            </div>
                        @endif
                    </div>
                </div>
                <div class="px-4 py-5 sm:px-6">
                    @yield('body')
                </div>
            </div>
        </main>
    </div>
@endsection






