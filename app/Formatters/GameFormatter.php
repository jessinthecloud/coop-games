<?php

namespace App\Formatters;

use App\Enums\MultiplayerMode;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use MarcReichel\IGDBLaravel\Models\Game;

abstract class GameFormatter
{
    /**
     * @var \MarcReichel\IGDBLaravel\Models\Game
     */
    protected Game $game;

    /**
     * @param \MarcReichel\IGDBLaravel\Models\Game $game
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
        // num players and multiplayer_mode are relative to platform
        return collect($this->game->multiplayer_modes)->map(function($mode, $key){

            $types = [];

            if(isset($mode['campaigncoop']) && $mode['campaigncoop']){
                $types []= MultiplayerMode::CAMPAIGN;
            }
            if(isset($mode['lancoop']) && $mode['lancoop']){
                $types []= MultiplayerMode::LAN;
            }
            if(isset($mode['offlinecoop']) && $mode['offlinecoop']){
                $types []= MultiplayerMode::OFFLINE;
            }
            if(isset($mode['onlinecoop']) && $mode['onlinecoop']){
                $types []= MultiplayerMode::ONLINE;
            }
            if(isset($mode['splitscreen']) && $mode['splitscreen']){
                $types []= MultiplayerMode::COUCH;
            }
            if(isset($mode['splitscreenonline']) && $mode['splitscreenonline']){
                $types []= MultiplayerMode::SPLITONLINE;
            }

            $platform = $mode['platform'] ?? null;

            return collect($mode)->merge([
                'coopTypes' => [
                    $platform => $types,
                ]
            ]);
        });
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