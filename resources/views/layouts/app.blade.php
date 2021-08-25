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
    <body class="antialiased w-full bg-gray-900">
        <div class="container mx-auto flex flex-col justify-center min-h-screen sm:items-center py-4 sm:pt-0">
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

            <div class="w-full sm:px-6 lg:my-8">
                <div class="w-full">
                    <h1 class="text-2xl font-semibold uppercase w-full text-gray-200">
                        Trending
                    </h1>
                    <section class="w-full flex flex-wrap justify-between space-x-1.5">
                        @foreach($trending_games as $game)
        {{-- @php dump($game); @endphp --}}
                            <x-game-card :game="$game" />
                        @endforeach
                    </section>
                </div>

                <h1 class="text-2xl font-semibold uppercase w-full text-gray-200">Online</h1>
                @foreach($online_games as $game)
                    @php dump($game); @endphp
                @endforeach

                <h1 class="text-2xl font-semibold uppercase w-full text-gray-200">Offline</h1>
                @foreach($offline_games as $game)
                    @php dump($game); @endphp
                @endforeach
            </div>
        </div>
    </body>
</html>
