@extends('layouts.app')

@section('title')

@endsection

@section('content')
    <div class="flex flex-wrap justify-center items-start mx-auto">
        <div id="trending-wrapper" class="mb-12">
            <h1 class="subtitle text-2xl font-semibold uppercase w-full text-gray-200">
                Trending
            </h1>
            <section class="w-full flex flex-wrap justify-between">
                @foreach($trending_games as $i => $game)
                    {{-- @php dump($game); @endphp --}}
                    <x-game-card :game="$game" :count="$i" />
                @endforeach
            </section>
        </div>
        <!-- trending-wrapper -->

        <aside class="main-aside min-h-screen w-1/2 flex-grow flex flex-wrap round-border">
            @if(isset($mostAnticipated))
                <h3 class="subtitle w-full">
                    Most Anticipated
                </h3>
                @foreach($mostAnticipated as $game)
                    {{-- @php dump($game); @endphp --}}
                    <x-game-card-small :game="$game" />
                @endforeach
            @endif
        </aside>

        <aside class="main-aside min-h-screen w-1/2 flex-grow flex flex-wrap round-border">
            @if(isset($comingSoon))
                <h3 class="subtitle w-full">
                    Coming Soon
                </h3>
                @foreach($comingSoon as $game)
                    {{-- @php dump($game); @endphp --}}
                    <x-game-card-small :game="$game" />
                @endforeach
            @endif
        </aside>
        <!-- main-aside -->

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
    </div>
@endsection