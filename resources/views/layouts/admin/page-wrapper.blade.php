<!DOCTYPE html>
<html lang="{{ Lang::locale() }}" class="js-language">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title') - {{ config('app.name') }}</title>
    <link href="{{ mix("css/main.css", 'dist/admin') }}" rel="stylesheet">
</head>
<body>
    @include('layouts.shared.piwik.script')
    <div class="js-vue">
        @yield('page-body')
    </div>

    @routes('admin')

    @if (config('sentry.dsn'))
        <script>
            window.SENTRY_DSN_PUBLIC = '{{ config('sentry.dsn') }}';
            window.SENTRY_ENVIRONMENT = '{{ config('sentry.environment') }}';
        </script>
    @endif

    <script src="{{ $langJsUrl }}"></script>
    <script src="{{ mix("js/manifest.js", 'dist/admin') }}"></script>
    <script src="{{ mix("js/vendor.js", 'dist/admin') }}"></script>
    <script src="{{ mix("js/main.js", 'dist/admin') }}"></script>
</body>
</html>
