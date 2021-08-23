<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name') }}</title>

{{--        <!-- Fonts -->--}}
{{--        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">--}}

        <!-- Styles -->
        <link href="{{ asset('normalize.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>
    <body class="antialiased">
        <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center py-4 sm:pt-0">
            @if (Route::has('login'))
                <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
                    @auth
                        <a href="{{ url('/home') }}" class="text-sm text-gray-700 underline">Home</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm text-gray-700 underline">Log in</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 underline">Register</a>
                        @endif
                    @endauth
                </div>
            @endif

            <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
                <section class="w-full w-100 flex d-flex flex-wrap justify-between justify-content-between">
                    <h1 class="text-gray-200 uppercase w-full w-100">Trending</h1>
                    @foreach($trending_games as $game)
    {{--                    @php dump($game); @endphp--}}
                        <x-game-card :game="$game" />
                    @endforeach
                </section>

                <h1 class="text-gray-200 uppercase">Online</h1>
                @foreach($online_games as $game)
                    @php dump($game); @endphp
                @endforeach

                <h1 class="text-gray-200 uppercase">Offline</h1>
                @foreach($offline_games as $game)
                    @php dump($game); @endphp
                @endforeach
            </div>
        </div>
    </body>
</html>
