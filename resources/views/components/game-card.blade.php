<div class="game mt-8 md:space-x-1.5">
    <div class="relative inline-block">
    {{-- @if(!empty($game['follows'])) {{ $game['follows'] }} Follows @else No Follows @endif --}}
    <!-- boxart -->
        {{-- cover endpoint returns thumb by default, but the API reference tells us what the name should be, so we use that instead https://api-docs.igdb.com/#images--}}
        <a href="{{ route('games.show', $game->slug) }}">
            <img src="https:{{ $game->cover_url }}" alt="{{ $game->name }} Cover Art" class="w-full hover:opacity-75 transition ease-in-out duration-150">
        </a>
        <!-- rating -->
        {{--@if($game['rating'])
            <div id="{{ $game->slug }}" class="absolute -bottom-4 -right-4 w-16 h-16 bg-gray-800 rounded-full">
                --}}{{-- doesn't HAVE to go here, just more convenient --}}{{--
                @push('scripts')
                    --}}{{-- blade partial file for snippets --}}{{--
                    @include('partials._rating', [
                        'slug' => $game->slug,
                        'rating' => $game['rating'],
                        'event' => null,
                    ])
                @endpush
            </div>
        @endif--}}
    </div>
    <!-- title -->
    <a href="{{ route('games.show', $game->slug) }}" class="block text-base font-semibold leading-tight hover:text-purple-400 mt-8">
        {{ $game->name }}
    </a>
    {{--@if(!empty($game['platforms']))
        <div class="text-gray-400 mt-1">
            {{ $game['platforms'] }}
        </div>
    @endif--}}
    @if(!empty($game->first_release_date))
        <div class="text-gray-400 mt-1"> {{ $game->first_release_date }} </div>
    @endif

    {{--@if(!empty($game['num_players']))
        <div class="text-gray-400 mt-1"> Up to {{ $game['num_players'] }} players </div>
    @endif--}}
</div> <!-- end game -->

