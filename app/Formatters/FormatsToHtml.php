<?php

namespace App\Formatters;

use Illuminate\Support\Str;
use MarcReichel\IGDBLaravel\Models\Game;

trait FormatsToHtml
{
    public function coverHtml($url=null)
    {
        
    }

    public function dateHtml($date)
    {
        
    }

    public function ratingHtml($score=null)
    {
        
    }

    public function criticRatingHtml($score=null)
    {
    
    }

    public function platformsHtml($platforms=null): string
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
    }

    public function numPlayersHtml($mode='onlinecoop', $limiter='max')
    {
    
    }

    public function coopTypesHtml()
    {
       
    }

    public function genresHtml($genres=null): string
    {
        $genres = $genres ?? $this->game->genres;

        return !empty($genres) ? collect($genres)->map(function ($genre) {
            return (!empty($genre['slug'])
                ? '<a href="'.route('genres.show', ['slug' => $genre['slug']]).'" class="text-purple-500 underline transition ease-in-out duration-150 
                    hover:text-purple-300 hover:no-underline">'.
                (!empty($genre['name']) ? $genre['name'] : $genre['name']).
                '</a>' : '');
        })->implode(', ') : false;
    }

    public function companiesHtml($devs, $pubs)
    {
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

    public function similarGamesHtml()
    {
        
    }

    public function screenshotsHtml()
    {
        
    }

    public function trailerHtml()
    {
        // 'trailer' => !empty($this->game['videos'][0]['video_id']) ? 'https://youtube.com/watch/'.$this->game['videos'][0]['video_id'] : '',
        // switch from watch to embed so we can use with modal
        
    }

    public function storesHtml()
    {
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

    public function officialWebsiteHtml()
    {
       
    }

    public function websitesHtml()
    {
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