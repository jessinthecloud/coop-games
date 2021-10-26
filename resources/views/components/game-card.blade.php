@props(['game', 'count' => 0])
<div
    class="game-card round-border
">
<?php //dump($game); ?>
    <div
        class="boxart
            w-full relative">

        <!-- boxart -->
        @if(!empty($game['cover_url']))
            {{-- cover endpoint returns thumb by default, but the API reference tells us what the name should be, so we use that instead https://api-docs.igdb.com/#images--}}
            <a href="{{ route('games.show', $game['slug']) }}"
                class="w-full inline-block
            ">
                <img src="https:{{ $game['cover_url'] }}" alt="{{ $game['name'] }} Cover Art"
                     class="w-full transition ease-in-out duration-150
                     hover:opacity-75
                ">
                {{--            @php dump($game['multiplayer_modes']); @endphp--}}
            </a>
        @endif
        @if($game['rating'])
            <!-- rating -->
            <div id="{{ $game['slug'] }}"
                 class="rating-wrapper
                    absolute -bottom-4 -right-4 w-16 h-16 bg-gray-800 rounded-full">
                {{-- doesn't HAVE to go here, just more convenient --}}
                @push('scripts')
                    {{-- blade partial file for snippets --}}
                    @include('games.partials._rating', [
                        'slug' => $game['slug'],
                        'rating' => $game['rating'],
                        'event' => null,
                        'count' => $count,
                    ])
                @endpush
            </div>
        @endif
    </div>
    <!-- end .boxart -->

    <div class="game-details">
        <!-- title -->
        <a href="{{ route('games.show', $game['slug']) }}"
           class="game-title
            hover:text-gray-200
            focus:text-gray-200
        ">
            {{ $game['name'] }}
        </a>
        @if(!empty($game['platforms']))
            <div class="text-gray-400 mt-1">
                {!! $game['platforms'] !!}
            </div>
        @endif
        @if(!empty($game['first_release_date']))
            <div class="text-gray-400 mt-1 italic"> {{ $game['first_release_date'] }} </div>
        @endif

        {{--@if(!empty($game['num_players']))
            <div class="text-gray-400 mt-1"> Up to {{ $game['num_players'] }} players </div>
        @endif--}}
    </div>
</div> <!-- end game -->

