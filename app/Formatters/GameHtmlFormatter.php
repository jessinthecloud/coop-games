<?php

namespace App\Formatters;

use Illuminate\Support\Str;
use MarcReichel\IGDBLaravel\Models\Game;

class GameHtmlFormatter extends GameFormatter implements Formatter
{
    public function __construct(Game $game=null)
    {
        if(isset($game)){
            $this->game = $game;
        }
    }

    /**
     * @param $game
     */
    public function setGame($game)
    {
        parent::setGame($game);
    }

    public function format()
    {
        if(isset($this->game)){
//        dump($this->games, $this->game);
            return $this->formatGame();
        }

        // merge the array into the currently iterated item of the collection
        // can use these to overwrite existing values or create new ones
        return $this->formatGames();
    }

    public function formatGame()
    {
        return collect($this->game)->merge([
            'cover_url' => $this->cover(),
            'rating' => $this->rating(),
            'platforms' => $this->platforms(),
            'first_release_date' => $this->date($this->game->first_release_date),
            'num_players' => $this->numPlayers(),
            'coop-types' => $this->coopTypes(),
            'genres' => $this->genres(),
            'companies' => $this->companies(),
        ])->toArray();
    }

    public function formatGames()
    {
        // return a Collection of $games
        // run the Closure function on each item of the games collection
        return collect($this->games)->map(function($game){
            // run this function on an item

            parent::setGame($game);

            // merge the array into the currently iterated item of the collection
            // can use these to overwrite existing values or create new ones
            return $this->formatGame();
        })->toArray();
    }

    public function cover(): string
    {
        return parent::cover();
    }

    public function date($date, string $format='M d, Y')
    {
        return parent::date($date, $format);

        // date:
        // accepts string or Carbon or DateTime
    }

    public function dates()
    {
        return parent::dates();

        // TODO: Implement dates() method.
    }

    public function rating()
    {
        return parent::rating();

        // TODO: Implement rating() method.
    }

    public function platforms():string
    {
        return implode(', ', parent::platforms());

        // TODO: Implement platforms() method.
    }

    public function numPlayers($mode='onlinecoop', $limiter='max')
    {
        return parent::numPlayers();

        // TODO: Implement numPlayers() method.
    }

    public function coopTypes()
    {
        return parent::coopTypes();

        // TODO: Implement coopTypes() method.
    }

    public function genres()
    {
        parent::genres();

        // TODO: Implement genres() method.

        return !empty($this->game->genres) ? collect($this->game->genres)->map(function ($genre) {
            return (!empty($genre['slug'])
                ? '<a href="'.route('platforms.show', ['slug' => $genre['slug']]).'" class="text-purple-500 underline transition ease-in-out duration-150 hover:text-purple-300 hover:no-underline">'.
                (!empty($genre['name']) ? $genre['name'] : $genre['name']).
                '</a>' : '');
            })->implode(', ') : false;
    }

    public function companies()
    {
        parent::companies();

        // TODO: Implement companies() method.

        return !empty($this->game->companies) ? collect($this->game->companies)->map(function ($company) {
            return (!empty($company['slug'])
                ? '<a href="'.route('platforms.show', ['slug' => $company['slug']]).'" class="text-purple-500 underline transition ease-in-out duration-150 hover:text-purple-300 hover:no-underline">'.
                (!empty($company['name']) ? $company['name'] : $company['name']).
                '</a>' : '');
        })->implode(', ') : false;
    }
}