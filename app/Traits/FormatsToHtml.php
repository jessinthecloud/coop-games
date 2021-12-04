<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait FormatsToHtml
{
    public function coverHtml($item,$url=null)
    {
        
    }

    public function dateHtml($item,$date)
    {
        
    }

    public function ratingHtml($item,$score=null)
    {
        
    }

    public function criticRatingHtml($item,$score=null)
    {
    
    }

    public function platformsHtml($item,$platforms=null): string
    {
        $platforms = $platforms ?? $item->platforms;

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
                ? /*'<a 
                    href="'.route('platforms.show', ['slug' => $platform['slug']]).'" 
                    class="text-gray-400 underline transition ease-in-out duration-150 
                        hover:text-gray-300 hover:no-underline">'.*/
                    (!empty($platform['abbreviation']) ? $platform['abbreviation'] : $platform['name'])
                /*.'</a>'*/ : '');
        })->implode(', ') : '';
    }

    public function numPlayersHtml($item,$mode='onlinecoop', $limiter='max')
    {
    
    }

    public function coopTypesHtml($item)
    {
       
    }

    public function genresHtml($item,$genres=null): string
    {
        $genres = $genres ?? $item->genres;

        return !empty($genres) ? collect($genres)->map(function ($genre) {
            return (!empty($genre['slug'])
                ? '<a href="'.route('genres.show', ['slug' => $genre['slug']]).'" class="text-purple-500 underline transition ease-in-out duration-150 
                    hover:text-purple-300 hover:no-underline">'.
                (!empty($genre['name']) ? $genre['name'] : $genre['name']).
                '</a>' : '');
        })->implode(', ') : false;
    }

    public function companiesHtml($item,$devs, $pubs)
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

    public function similarGamesHtml($item)
    {
        
    }

    public function screenshotsHtml($item)
    {
        
    }

    public function trailerHtml($item)
    {
        // 'trailer' => !empty($item['videos'][0]['video_id']) ? 'https://youtube.com/watch/'.$item['videos'][0]['video_id'] : '',
        // switch from watch to embed so we can use with modal
        
    }

    public function storesHtml($item)
    {
        // game store links
        // can be external games or websites
        // websites: store categories
        // 13 steam, 16 epic, 17 gog
        /*!empty($item['screenshots']) ? (collect($item['screenshots'])->map(
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

    public function officialWebsiteHtml($item)
    {
       
    }

    public function websitesHtml($item)
    {
        // TODO: add check to make sure first website is not one of the social media sites we want
        /* !empty($item['websites']) ? (collect($item['websites'])->map(
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