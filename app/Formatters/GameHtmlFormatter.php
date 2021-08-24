<?php

namespace App\Formatters;

use Illuminate\Support\Str;
use MarcReichel\IGDBLaravel\Models\Game;

class GameHtmlFormatter extends GameFormatter implements Formatter
{
    protected $games;

    public function __construct(Game $games=null)
    {
        $this->games = $games;
    }

    /**
     * @param $games
     */
    public function setGames($games)
    {
        $this->games = $games;
    }

    public function format()
    {
        // return a Collection of $games
        // run the Closure function on each item of the games collection
        return collect($this->games)->map(function($game){
            // run this function on an item

            parent::setGame($game);

            // merge the array into the currently iterated item of the collection
            // can use these to overwrite existing values or create new ones
            return collect($game)->merge([
                'cover_url' => $this->cover(),
                'rating' => $this->rating(),
                'platforms' => implode(', ', $this->platforms()),
                'first_release_date' => $this->date($game->first_release_date),
                'num_players' => $this->numPlayers(),
                'coop-types' => $this->coopTypes(),
            ]);
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

    public function platforms()
    {
        return parent::platforms();

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
}