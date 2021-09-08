@extends('layouts.app')

@section('title')

@endsection

@section('content')
    <div class="flex flex-wrap justify-center items-start mx-auto">
        <div id="trending-wrapper">
            <h1 class="subtitle text-2xl font-semibold uppercase w-full text-gray-200">
                Co-op Games
            </h1>
            <section class="w-full flex flex-wrap justify-between">
                @foreach($games as $i => $game)
                    {{-- @php dump($game); @endphp --}}
                    <x-game-card :game="$game" :count="$i" />
                @endforeach
            </section>
        </div>
       
    </div>
@endsection