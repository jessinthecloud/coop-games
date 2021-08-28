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
    </head>
    <body class="antialiased w-full flex flex-col text-gray-200">

        <x-navigation></x-navigation>

        <div class="container min-h-screen flex flex-wrap justify-center items-start mx-auto
        ">
            <div id="content-wrapper">
                @yield('content')
            </div>
            <!-- #content-wrapper -->

            @if(isset($online_games))
                <aside id="main-aside" class="min-h-screen flex-grow bg-gray-800 round-border">
                    <h3 class="subtitle">
                        Most Anticipated
                    </h3>
                    @foreach($online_games as $game)
                        {{-- @php dump($game); @endphp --}}
                        <x-game-card-small :game="$game" />
                    @endforeach
                    <h3 class="subtitle">
                        Coming Soon
                    </h3>
                    @foreach($offline_games as $game)
                        {{-- @php dump($game); @endphp --}}
                        <x-game-card-small :game="$game" />
                    @endforeach
                </aside>
            @endif
        </div>
    </body>
</html>
