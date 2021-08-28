@extends('layouts.app')

@section('title')

@endsection

@section('content')
    <div class="w-full">
        <h1 class="subtitle text-2xl font-semibold uppercase w-full text-gray-200">
            Trending
        </h1>
        <section class="w-full flex flex-wrap justify-between">
            @foreach($trending_games as $game)
                {{-- @php dump($game); @endphp --}}
                <x-game-card :game="$game" />
            @endforeach
        </section>
    </div>

    {{--
    <h1 class="subtitle text-2xl font-semibold uppercase w-full text-gray-200">Online</h1>
    @foreach($online_games as $game)
        @php dump($game); @endphp
    @endforeach

    <h1 class="subtitle text-2xl font-semibold uppercase w-full text-gray-200">Offline</h1>
    @foreach($offline_games as $game)
        @php dump($game); @endphp
    @endforeach
    --}}
@endsection