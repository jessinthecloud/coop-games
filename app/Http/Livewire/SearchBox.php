<?php

namespace App\Http\Livewire;

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
    
    /* NOT WORKING WITH METHOD BINDING 
    // instead of __construct()
    public function mount(Formatter $formatter)
    {
        $this->formatter = $formatter ?? new GameFormatter();
    }*/

    public function render()
    {
        // don't make a request until we have 3 or more letters typed
        if ( strlen( $this->search ) >= 3 ) {

            // do search request with livewire data from view
            $raw_search_results = Game::searchFor($this->search);
//            dump($this->search,$raw_search_results);
            
            $this->search_results = $this->formatForView( $raw_search_results )->toArray();
//            ddd($this->search_results);

            /*if ( !empty( $raw_search_results ) ) {
                // get games returned as a flattened collection, convert to array, then filter out empty items,
                // then implode into string with commas
                $game_ids = collect( $raw_search_results )->pluck( 'game' )->toArray();
                $game_ids = implode( ", ", array_filter( $game_ids ) );

                // get game info for each result
                $results = Http::withHeaders( config( 'services.igdb' ) )
                    ->withBody(
                        "fields name, slug, cover.url;
		            	where id = ($game_ids);",
                        "text/plain"
                    )->post( 'https://api.igdb.com/v4/games' )
                    ->json();

                 dump($results);

                $this->search_results = $this->formatForView( $results );
                 ddd($this->search_results);
            } // endif empty search result*/
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
        
        // return a Collection of $games
        // run the Closure function on each item of the games collection
        /*return collect( $games )->map( function ( $game ) {
            // run this function on an item

            // merge the array into the currently iterated item of the collection
            // can use these to overwrite existing values or create new ones
            return collect( $game )->merge( [
                'cover_url' => !empty( $game['cover']['url'] ) ? \Str::replaceFirst(
                    'thumb',
                    'cover_small',
                    $game['cover']['url']
                ) : 'https://via.placeholder.com/264x352',
            ] );
            // convert to array because that is the data type of the class property we are setting it to
        } )->toArray();*/
    }
}