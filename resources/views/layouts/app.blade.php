<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ mix('/css/app.css') }}" rel="stylesheet">

    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
        
        window.codetube = {
            url: '{{ config('app.url') }}',
            user: {
                id: {{ Auth::check() ? Auth::user()->id : 'null' }},
                authenticated: {{ Auth::check() ? 'true' : 'false' }},
            }
        };
    </script>
    
    <style>
        @yield('css')
    </style>
</head>
<body>
    <div id="app">
        @include('layouts.partials._navigation')

        @yield('content')
    </div>

    <!-- Scripts -->
<!--    <script src="/js/manifest.js"></script>-->
<!--    <script src="/js/vendor.js"></script>-->
    <script src="{{ mix('/js/app.js') }}"></script>

<!--    <script>document.write('<script src="http://localhost:35729/livereload.js"></' + 'script>')</script>-->
</body>
</html>
