<div class="game-card-small">
    <a href="{{ route('games.show', $game['slug']) }}"
       class="boxart"
    >
        <img src="{{ $game['cover_url'] }}"
             alt="{{ $game['name'] }} Cover Art"
             class="w-16 transition ease-in-out duration-150
                hover:opacity-75 "
        >
    </a>
    <div class="ml-4">
        <a href="{{ route('games.show', $game['slug']) }}"
           class="hover:text-purple-300"
        >
            {{ $game['name'] }}
        </a>
        <p class="text-gray-400 text-sm mt-1">
            {{ $game['first_release_date'] }}
        </p>
    </div>
</div> <!-- .game -->