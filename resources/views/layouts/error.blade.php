<!DOCTYPE html>
<html lang="nl" class="block | w-full h-full | bg-gray-100">
  <head>
    <meta charset="utf-8">
    <title>@yield('title') - {{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link href="{{ mix("css/main.css", 'dist/admin') }}" rel="stylesheet">
  </head>
  <body class="flex items-center justify-center | w-full h-full | mx-2 | text-center">
      <div class="text-3xl text-gray-500 | font-semibold | antialiased">
        @yield('content')
      </div>
  </body>
</html>
