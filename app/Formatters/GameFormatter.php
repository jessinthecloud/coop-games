<?php

namespace App\Formatters;

use App\Enums\MultiplayerMode;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use MarcReichel\IGDBLaravel\Models\Game;

class GameFormatter implements Formatter
{
    use FormatsToHtml;
    
    public function format($item)
    {
        return collect($item)->merge([
           'cover_url' => $this->cover($item),
           'rating' => $this->rating($item),
           'aggregated_rating' => $this->criticRating($item),
           'platforms' => $this->platforms($item),
           'first_release_date' => $this->date($item, $item->first_release_date),
           'num_players' => $this->numPlayers($item),
           'multiplayer_modes' => $this->coopTypes($item),
           'genres' => $this->genres($item),
           'companies' => $this->companies($item),
//            'similar_games' => $this->similarGames($item),
           'screenshots' => $this->screenshots($item),
           'trailer' => $this->trailer($item),
           'stores' => $this->stores($item),
           'websites' => $this->websites($item),
           'website' => $this->officialWebsite($item),
       ])->toArray();

    }

    public function formatGames($games)
    {
        // return a Collection of $games
        // run the Closure function on each item of the games collection
        return collect($games)->map(function($game){
            return $this->format($game);
        })->toArray();
    }

    public function cover($game,$url=null): string
    {
        $cover = $url ?? (isset($game->cover) ? $game->cover['url'] : null);

        return !empty($cover) ? Str::replaceFirst('thumb', 'cover_big', $cover) : 'https://via.placeholder.com/264x352';
    }

    public function date($game, $date, string $format='M d, Y')
    {
        // date:
        // accepts $date string

        // TODO: accept Carbon or DateTime
        return !empty($game->first_release_date) ? Carbon::parse($game->first_release_date)->format($format) : null;
    }

    public function dates($game)
    {
        // TODO: implement dates()
    }

    public function rating($game,$score=null)
    {
        $rating = $score ?? $game->rating;
        return !empty($rating) ? round($rating) : '';
    }

    public function criticRating($game,$score=null)
    {
        $rating = $score ?? $game->aggregated_rating;
        return !empty($rating) ? round($rating) : '';
    }

    public function platforms($game,$platforms=null)
    {
        $platforms = $platforms ?? $game->platforms;

        return $this->platformsHtml($game);
    }

    public function numPlayers($game,$mode='onlinecoop', $limiter='max')
    {
        // num players and multiplayer_mode are relative to platform
        return collect($game->multiplayer_modes)->map(function($mode, $key){
            // onlinecoopmax, offlinecoopmax, per platform
            // TODO: finish implementing
        });
    }

    public function coopTypes($game)
    {
        /*'online_multi_num' => !empty($game['multiplayer_modes']) ? collect($game['multiplayer_modes'])->pluck('onlinemax')->unique()->flatten()[0] : [],
        'offline_multi_num' => !empty($game['multiplayer_modes']) ? collect($game['multiplayer_modes'])->pluck('offlinemax')->unique()->flatten()[0] : [],
        'online_coop_num' => !empty($game['multiplayer_modes']) ? collect($game['multiplayer_modes'])->pluck('onlinecoopmax')->unique()->flatten()[0] : [],
        'offline_coop_num' => !empty($game['multiplayer_modes']) ? collect($game['multiplayer_modes'])->pluck('offlinecoopmax')->unique()->flatten()[0] : [],*/

        /*'coop_info' => !empty($game['multiplayer_modes']) ? collect($game['multiplayer_modes'])->map(function ($game) {
            return collect($game)->merge([
                 'types' => [
                     'online'      => [
                         'label'=> 'Online',
                         'value'=> !empty($game['onlinecoop']) ? $game['onlinecoop'] : false
                     ],
                     'offline'     => [
                         'label'=> 'Offline',
                         'value'=> !empty($game['offlinecoop']) ? $game['offlinecoop'] : false
                     ],
                     'campaign'    => [
                         'label'=> 'Campaign',
                         'value'=> !empty($game['campaigncoop']) ? $game['campaigncoop'] : false
                     ],
                     'lan'         => [
                         'label'=> 'LAN',
                         'value'=> !empty($game['lancoop']) ? $game['lancoop'] : false
                     ],
                 ],
                 'onlinemax' => !empty($game['online_coop_num']) ? $game['online_coop_num'] : 0,
                 'offlinemax' => !empty($game['offline_coop_num']) ? $game['offline_coop_num'] : 0,

            ]);
        })->toArray() : [],*/

    /*'multi_info' => !empty($game['multiplayer_modes']) ? collect($game['multiplayer_modes'])->map(function ($game) {
        return collect($game)->merge([
             'online'  => [
                 'label'=> 'Online',
                 'value'=> !empty($game['onlinemax']) ? $game['onlinemax'] : 0
             ],
             'offline' => [
                 'label'=> 'Offline',
                 'value'=> !empty($game['offlinemax']) ? $game['offlinemax'] : 0
             ],
             'splitscreen'  => [
                 'label'=> 'Local Splitscreen',
                 'value'=> !empty($game['multiplayer_modes']['splitscreen']) ? $game['multiplayer_modes']['splitscreen'] : false
             ],
             'split_online' => [
                 'label'=> 'Online Splitscreen',
                 'value'=> !empty($game['multiplayer_modes']['split_online']) ? $game['multiplayer_modes']['split_online'] : false
             ],
        ]);
    })->toArray() : [],*/

        // num players and multiplayer_mode are relative to platform
        // multiplayer_modes already split by platform
        return collect($game->multiplayer_modes)->map(function($mode, $key){

            $types = [];

            if(isset($mode['campaigncoop']) && $mode['campaigncoop']){
                $types [$key]['label']= MultiplayerMode::CAMPAIGN;
            }
            if(isset($mode['lancoop']) && $mode['lancoop']){
                $types [$key]['label']= MultiplayerMode::LAN;
            }
            if(isset($mode['offlinecoop']) && $mode['offlinecoop']){
                $types [$key]['label']= MultiplayerMode::OFFLINE;
                $types [$key]['max']= !empty($game['offlinemax']) ? $game['offlinemax'] : null;
            }
            if(isset($mode['onlinecoop']) && $mode['onlinecoop']){
                $types [$key]['label']= MultiplayerMode::ONLINE;
                $types [$key]['max']= !empty($game['onlinemax']) ? $game['onlinemax'] : null;
            }
            if(isset($mode['splitscreen']) && $mode['splitscreen']){
                $types [$key]['label']= MultiplayerMode::COUCH;
            }
            if(isset($mode['splitscreenonline']) && $mode['splitscreenonline']){
                $types [$key]['label']= MultiplayerMode::SPLITONLINE;
            }

            return collect($mode)->merge([
                'coop-types' => $types,
            ]);
        }); // end map
    }

    public function genres($game)
    {
        return $this->genresHtml($game);

        /*return !empty($game->genres) ? collect($game->genres)->map(function ($genre) {
            $slug = (!empty($genre['slug'])
                ? '<a href="'.route('games.platform', ['slug' => $genre['slug']]).'" class="text-purple-500 underline transition ease-in-out duration-150 hover:text-purple-300 hover:no-underline">'.
                    (!empty($genre['name']) ? $genre['name'] : $genre['name']).
                '</a>' : '');
            return ;
        })->implode(', ') : false;*/
    }

    public function companies($game)
    {
        $devs = !empty($game->involved_companies) ? collect($game->involved_companies)
            ->filter(function ($company) {
                return $company['developer'] === true;
            }) : collect();

        $pubs = !empty($game->involved_companies) ? collect($game->involved_companies)
            ->filter(function ($company) {
                return $company['publisher'] === true;
            }) : collect();
            
        return $this->companiesHtml($game, $devs, $pubs);
    }

    public function similarGames($game)
    {
        return !empty($game['similar_games'])
            ? collect($game['similar_games'])/*->filter(function ($game) {
                return (isset($game['multiplayer_modes'])
                    && (
                        (isset($game['multiplayer_modes']['onlinecoop']) && $game['multiplayer_modes']['onlinecoop'])
                        || (isset($game['multiplayer_modes']['offlinecoop']) && $game['multiplayer_modes']['offlinecoop'])
                    )
                );
            })*/->map(function ($game) {
                return collect($game)->merge([
                     'cover_url' => isset($game['cover']['url']) ? $this->cover($game['cover']['url']) : 'https://via.placeholder.com/264x352',
                     'platforms' => isset($game['platforms']) ? $this->platforms($game['platforms']) : '',
                     'rating' => isset($game['rating']) ? $this->rating($game['rating']) : null,
                     'first_release_date' => isset($game['first_release_date']) ? $this->date($game['first_release_date']) : null,
                 ]);
            })->take(5)
            : [];
    }

    public function screenshots($game)
    {
        return !empty($game['screenshots']) ? (collect($game['screenshots'])->map(
            function ($screenshot) {
                return [
                    'huge' => \Str::replaceFirst('thumb', 'screenshot_huge', $screenshot['url']),
                    'big' => \Str::replaceFirst('thumb', 'screenshot_big', $screenshot['url'])
                ];
                // if there are lots and lots of screenshots in the collection, limit our return to just 9
            })->take(9)
        ) : [];
    }

    public function trailer($game)
    {
        return !empty($game['videos'][0]['video_id']) ? 'https://youtube.com/embed/'.$game['videos'][0]['video_id'] : '';
    }

    public function stores($game)
    {
        // TODO: Implement stores() method
    }

    public function officialWebsite($game)
    {
        return !empty($game['websites']) ? (collect($game['websites'])->filter(function($website, $key){
            return ((int)$website['category'] === 1);
        }))->pluck('url')->first() : '';
    }

    public function websites($game)
    {
        // TODO: Implement websites() method
    }
}