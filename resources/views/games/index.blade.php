@extends('layouts.app')

@section('title')
    Listing
@endsection

@section('content')

    <section id="games-listing-wrapper">
        <h1 class="subtitle text-2xl font-semibold uppercase w-full text-gray-200">
            Co-op Games
        </h1>
        
        <div id="filters-wrapper" class="w-full flex flex-wrap justify-center">
            
        </div>
        
        <div id="paging-wrapper" class="w-full flex flex-wrap justify-center mb-12 mx-auto md:w-1/3">
            {{ $games_pager->links() }}
        </div>
        
        @if($games->count() === 0)
            <div id="games-grid" class="min-w-full text-center">
                No more games
            </div>
        @else
            <div id="games-grid" class="w-full grid grid-flow-row grid-cols-2 gap-1 lg:grid-cols-5">
                @foreach($games as $i => $game)
                    <x-game-listing-card :game="$game" :count="$i"/>
                @endforeach
            </div>
            <div id="paging-wrapper" class="w-full flex flex-wrap justify-center my-12 mx-auto md:w-1/3">
                {{ $games_pager->links() }}
            </div>
        @endif
    </section>

@endsection