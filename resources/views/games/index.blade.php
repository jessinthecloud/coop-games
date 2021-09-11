@extends('layouts.app')

@section('title')
    Listing
@endsection

@section('content')
    <div class="flex flex-wrap justify-center items-start mx-auto">
        <section id="games-listing-wrapper">
            <h1 class="subtitle text-2xl font-semibold uppercase w-full text-gray-200">
                Co-op Games
            </h1>
            <div id="filters-wrapper" class="w-full flex flex-wrap justify-center">
                
            </div>
            <div id="games-grid" class="w-full grid grid-flow-row grid-cols-2 gap-1 md:grid-cols-5">
                @foreach($games as $i => $game)
                    <x-game-listing-card :game="$game" :count="$i"/>
                @endforeach
            </div>
        </section>
    </div>
@endsection