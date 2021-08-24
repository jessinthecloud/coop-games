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

    protected function rating()
    {
        return !empty($this->game->rating) ? round($this->game->rating) : '';
    }

    protected function platforms()
    {
        return !empty($this->game->platforms) ? collect($this->game->platforms)->pluck('abbreviation')->all() : [];
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

    protected function cover(): string
    {
        return !empty($this->game['cover']['url']) ? Str::replaceFirst('thumb', 'cover_big', $this->game['cover']['url']) : '';
    }
}