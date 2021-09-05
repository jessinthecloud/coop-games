<?php

namespace App\Formatters;

use App\Enums\MultiplayerMode;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use MarcReichel\IGDBLaravel\Models\Game;

abstract class GameFormatter
{
    /**
     * @var Game
     */
    protected Game $game;

    /**
     * @param Game $game
     */
    protected function setGame(Game $game)
    {
        $this->game = $game;
    }

    protected function date($date, string $format='M d, Y')
    {
        // date:
        // accepts $date string

        // TODO: accept Carbon or DateTime
        return !empty($this->game->first_release_date) ? Carbon::parse($this->game->first_release_date)->format($format) : null;
    }

    protected function dates()
    {
        // TODO: implement dates()
    }

    protected function rating($score=null)
    {
        $rating = $score ?? $this->game->rating;
        return !empty($rating) ? round($rating) : '';
    }

    protected function criticRating($score=null)
    {
        $rating = $score ?? $this->game->aggregated_rating;
        return !empty($rating) ? round($rating) : '';
    }

    protected function platforms($platforms=null)
    {
        $platforms = $platforms ?? $this->game->platforms;
        return !empty($platforms) ? collect($platforms)->pluck('abbreviation')->all() : [];
    }

    protected function numPlayers($mode='onlinecoop', $limiter='max')
    {
        // num players and multiplayer_mode are relative to platform
        return collect($this->game->multiplayer_modes)->map(function($mode, $key){
            // onlinecoopmax, offlinecoopmax, per platform
            // TODO: finish implementing
        });
    }

    protected function coopTypes()
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
        return collect($this->game->multiplayer_modes)->map(function($mode, $key){

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

    protected function cover($url=null): string
    {
        $cover = $url ?? $this->game['cover']['url'];

        return !empty($cover) ? Str::replaceFirst('thumb', 'cover_big', $cover) : 'https://via.placeholder.com/264x352';
    }

    protected function genres()
    {
        // TODO: Implement genres() method.

        /*return !empty($this->game->genres) ? collect($this->game->genres)->map(function ($genre) {
            $slug = (!empty($genre['slug'])
                ? '<a href="'.route('games.platform', ['slug' => $genre['slug']]).'" class="text-purple-500 underline transition ease-in-out duration-150 hover:text-purple-300 hover:no-underline">'.
                    (!empty($genre['name']) ? $genre['name'] : $genre['name']).
                '</a>' : '');
            return ;
        })->implode(', ') : false;*/
    }

    protected function companies()
    {
        // TODO: Implement companies() method
    }

    protected function similarGames()
    {
        // TODO: Implement similarGames() method
    }

    protected function screenshots()
    {
        // TODO: Implement screenshots() method
    }

    protected function trailer()
    {
        // TODO: Implement trailer() method
    }

    protected function stores()
    {
        // TODO: Implement stores() method
    }

    protected function officialWebsite()
    {
        // TODO: Implement officialWebsite() method
    }

    protected function websites()
    {
        // TODO: Implement websites() method
    }
}