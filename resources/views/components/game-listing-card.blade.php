<div class="game-card-small flex flex-nowrap">
    <a href="{{ route('games.show', $game['slug']) }}"
       class="boxart
       w-1/3"
    >
        <img src="{{ $game['cover_url'] }}"
             alt="{{ $game['name'] }} Cover Art"
             class="w-full transition ease-in-out duration-150
                hover:opacity-75 "
        >
    </a>
    
    <div class="flex flex-col w-2/3 pl-2">
        <a href="{{ route('games.show', $game['slug']) }}"
           class="hover:text-purple-300"
        >
            {{ $game['name'] }}
        </a>
        
        @if(!empty($game['first_release_date']))
            <p class="text-gray-400 text-sm italic mt-1">
                {{ $game['first_release_date'] }}
            </p>
        @endif
        
        @if(!empty($game['platforms']))
            <div class="text-gray-400 mt-1">
                {!! $game['platforms'] !!}
            </div>
        @endif
    </div>
</div> <!-- .game -->