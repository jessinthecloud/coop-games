<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name') }}</title>

{{--        <!-- Fonts -->--}}
{{--        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">--}}

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link href="{{ asset('css/common.css') }}" rel="stylesheet">

        <script src="{{ asset('js/app.js') }}" defer></script>
    </head>
    <body class="antialiased w-full flex flex-col text-gray-200">

        <x-navigation></x-navigation>

        <div class="container min-h-screen flex flex-column justify-center items-start mx-auto
        ">
            <div id="content-wrapper">
                @yield('content')
            </div>
            <!-- #content-wrapper -->
        </div>

        <script src="https://cdn.rawgit.com/kimmobrunfeldt/progressbar.js/master/dist/progressbar.min.js"></script>

        {{-- allows other views to also add JS --}}
        @stack('scripts')
    </body>
</html>
