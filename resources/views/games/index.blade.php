@extends('layouts.app')

@section('title')
    {{ $game['name'] }} |
@endsection

@section('content')
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

@endsection