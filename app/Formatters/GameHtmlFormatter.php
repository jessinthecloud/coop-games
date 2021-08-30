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
            'aggregated_rating' => $this->criticRating(),
            'platforms' => $this->platforms(),
            'first_release_date' => $this->date($this->game->first_release_date),
            'num_players' => $this->numPlayers(),
            'coop-types' => $this->coopTypes(),
            'genres' => $this->genres(),
            'companies' => $this->companies(),
            'similar_games' => $this->similarGames(),
            'screenshots' => $this->screenshots(),
            'trailer' => $this->trailer(),
            'stores' => $this->stores(),
            'websites' => $this->websites(),
            'website' => $this->officialWebsite(),
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

    public function cover($url=null): string
    {
        $cover = $url ?? $this->game['cover']['url'];

        return parent::cover($cover);
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

    public function rating($score=null): string
    {
        $rating = $score ?? $this->game->rating;
        return parent::rating($rating);

        // TODO: Implement rating() method.
    }

    public function criticRating($score=null): string
    {
        $rating = $score ?? $this->game->aggregated_rating;
        return parent::criticRating($rating);

        // TODO: Implement rating() method.
    }

    public function platforms($platforms=null): string
    {
        $platforms = $platforms ?? $this->game->platforms;

        return !empty($platforms) ? collect($platforms)->map(function ($platform) {

            /*if(!empty($platform['platform_logo'])){
                return '<a 
                    href="' . route('platforms.show', ['slug' => $platform['slug']]) . '" 
                    class="text-gray-400 underline transition ease-in-out duration-150 
                        hover:text-gray-300 hover:no-underline">
                    <img src="' . $platform['platform_logo']['url'] . '" alt="' . $platform['name'] . '" >
                </a>';
            }*/

            return (!empty($platform['abbreviation'])
                ? '<a 
                    href="'.route('platforms.show', ['slug' => $platform['slug']]).'" 
                    class="text-gray-400 underline transition ease-in-out duration-150 
                        hover:text-gray-300 hover:no-underline">'.
                    (!empty($platform['abbreviation']) ? $platform['abbreviation'] : $platform['name']).
                '</a>' : '');
        })->implode(', ') : '';

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

    public function genres($genres=null): string
    {
        $genres = $genres ?? $this->game->genres;
        parent::genres($genres);

        // TODO: Implement genres() method.

        return !empty($genres) ? collect($genres)->map(function ($genre) {
            return (!empty($genre['slug'])
                ? '<a href="'.route('genres.show', ['slug' => $genre['slug']]).'" class="text-purple-500 underline transition ease-in-out duration-150 
                    hover:text-purple-300 hover:no-underline">'.
                (!empty($genre['name']) ? $genre['name'] : $genre['name']).
                '</a>' : '');
            })->implode(', ') : false;
    }

    public function companies()
    {
        parent::companies();

        $devs = !empty($this->game->involved_companies) ? collect($this->game->involved_companies)
            ->filter(function ($company) {
                return $company['developer'] === true;
            }) : collect();

        $pubs = !empty($this->game->involved_companies) ? collect($this->game->involved_companies)
            ->filter(function ($company) {
                return $company['publisher'] === true;
            }) : collect();

        $devs = $devs->map(function ($company) {
             return (!empty($company['company']['slug'])
                ? '<a 
                    href="'.route('platforms.show', ['slug' => $company['company']['slug']]).'" 
                    class="text-purple-500 underline transition ease-in-out duration-150 
                        hover:text-purple-300 hover:no-underline">'.
                    $company['company']['name'].
                '</a>' : '');
        })->implode(', ');

        $pubs = $pubs->map(function ($company) {
            return (!empty($company['company']['slug'])
                ? '<a 
                    href="'.route('platforms.show', ['slug' => $company['company']['slug']]).'" 
                    class="text-purple-500 underline transition ease-in-out duration-150 
                        hover:text-purple-300 hover:no-underline">'.
                $company['company']['name'].
                '</a>' : '');
        })->implode(', ');

        return ['devs' => $devs, 'pubs'=>$pubs];
    }

    public function similarGames()
    {
        parent::similarGames();

        // TODO: Implement similarGames() method

            return !empty($this->game['similar_games'])
                ? collect($this->game['similar_games'])->map(function ($game) {
                    return collect($game)->merge([
                        'cover_url' => isset($game['cover']['url']) ? $this->cover($game['cover']['url']) : 'https://via.placeholder.com/264x352',
                        'platforms' => isset($game['platforms']) ? $this->platforms($game['platforms']) : '',
                        'rating' => isset($game['rating']) ? $this->rating($game['rating']) : null,
                        'first_release_date' => isset($game['first_release_date']) ? $this->date($game['first_release_date']) : null,
                    ]);
                })->take(7) : [];
    }

    public function screenshots()
    {
        parent::screenshots();

        // TODO: Implement screenshots() method

        return !empty($this->game['screenshots']) ? (collect($this->game['screenshots'])->map(
            function ($screenshot) {
                return [
                    'huge' => \Str::replaceFirst('thumb', 'screenshot_huge', $screenshot['url']),
                    'big' => \Str::replaceFirst('thumb', 'screenshot_big', $screenshot['url'])
                ];
                // if there are lots and lots of screenshots in the collection, limit our return to just 9
            })->take(9)
        ) : [];
    }

    public function trailer()
    {
        parent::trailer();

        // 'trailer' => !empty($this->game['videos'][0]['video_id']) ? 'https://youtube.com/watch/'.$this->game['videos'][0]['video_id'] : '',
        // switch from watch to embed so we can use with modal
        return !empty($this->game['videos'][0]['video_id']) ? 'https://youtube.com/embed/'.$this->game['videos'][0]['video_id'] : '';

    }

    public function stores()
    {
        parent::stores();

        // TODO: Implement stores() method

        // game store links
        // can be external games or websites
        // websites: store categories
        // 13 steam, 16 epic, 17 gog
        /*!empty($this->game['screenshots']) ? (collect($this->game['screenshots'])->map(
            function ($screenshot) {
                return [
        'store_links' => [
            'steam' => [
                'url' => !empty($game['websites']) ? collect($game['websites'])->filter(function ($website) {
                    // filter runs the passed function (Closure?) on the items of the array
                    // $website is the currently iterated array item
                    return \Str::contains($website['url'], 'steam');
                })->pluck('url')->first() :
                    (!empty($game['external_games']) ? collect($game['external_games'])->filter(function ($game) {
                        return (!empty($game['category']) && $game['category'] == 1);
                    })->pluck('url')->first() : ''),

                'icon' => '<i class="fab fa-2x fa-steam"></i>'
            ],

            'epic' => [
                'url' => !empty($game['websites']) ? collect($game['websites'])->filter(function ($website) {
                    // filter runs the passed function (Closure?) on the items of the array
                    // $website is the currently iterated array item
                    return \Str::contains($website['url'], 'epic');
                })->pluck('url')->first() : '',

                'icon' => config('services.svg.epic')

            ],

            'gog' => [
                'url' => !empty($game['websites']) ? collect($game['websites'])->filter(function ($website) {
                    // filter runs the passed function (Closure?) on the items of the array
                    // $website is the currently iterated array item
                    return \Str::contains($website['url'], 'gog');
                })->pluck('url')->first() : '',

                'icon' => config('services.svg.gog'),
            ]
        ] */
    }

    public function officialWebsite()
    {
        parent::officialWebsite();

        // TODO: Implement officialWebsite() method
        return !empty($this->game['websites']) ? (collect($this->game['websites'])->filter(function($website, $key){
            return ((int)$website['category'] === 1);
        }))->pluck('url')->first() : '';
    }

    public function websites()
    {
        parent::websites();

        // TODO: Implement websites() method

        // TODO: add check to make sure first website is not one of the social media sites we want
        /* !empty($this->game['websites']) ? (collect($this->game['websites'])->map(
            function (website) {
                return [
        'social' => [

            'website' => !empty($game['websites']) ? collect($game['websites'])->first() : '',

            // return the first item found by the filter
            'facebook' => !empty($game['websites']) ? collect($game['websites'])->filter(function ($website) {
                // filter runs the passed function (Closure?) on the items of the array
                // $website is the currently iterated array item
                return \Str::contains($website['url'], 'facebook');
            })->first() : '',

            'twitter' => !empty($game['websites']) ? collect($game['websites'])->filter(function ($website) {
                // filter runs the passed function (Closure?) on the items of the array
                // $website is the currently iterated array item
                return \Str::contains($website['url'], 'twitter');
            })->first() : '',

            'instagram' => !empty($game['websites']) ? collect($game['websites'])->filter(function ($website) {
                // filter runs the passed function (Closure?) on the items of the array
                // $website is the currently iterated array item
                return \Str::contains($website['url'], 'instagram');
            })->first() : '',
        ], // end social*/
    }
}