<?php

namespace App\Livewire;

use App\Formatters\Formatter;
use App\Formatters\GameFormatter;
use App\Models\Game;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class SearchBox extends Component
{
    // these are set and/or returned to the livewire component's view
    public $search = '';
    public $search_results = [];
    protected $formatter;

    public function render()
    {
        // don't make a request until we have 3 or more letters typed
        if ( strlen( $this->search ) >= 3 ) {

            // do search request with livewire data from view
            $raw_search_results = Game::searchFor($this->search);

            $this->search_results = $this->formatForView( $raw_search_results )->toArray();
        }

        return view( 'livewire.search-box' );
    }

    private function formatForView( $games )
    {
        // method binding not working with mount()
        $this->formatter = $this->formatter ?? new GameFormatter();

        return collect( $games )->map( function ( $game ) {
            $game->setFormatter($this->formatter);
            return $game->formatter->format();
        });
    }
}
