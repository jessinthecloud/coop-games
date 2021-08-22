<?php


namespace App\Filters;

use Illuminate\Support\Collection;

class GameFilterer
{

    protected Collection $games;

    public function __construct(Collection $games)
    {
        $this->games = $games;
    }

    protected function multiplayerMode($type)
    {
        return $this->games->filter(function ($item, $index) use ($type){
            return collect($item->multiplayer_modes)->contains($type, true);
        });
    }

    protected function maxPlayerNumber(int $number, $mode='online')
    {
        return $this->games->filter(function($game, $key) use ($number, $mode){
            return (collect($game->multiplayer_modes)->pluck($mode.'max')->first() <= $number);
        });
    }

    protected function minPlayerNumber(int $number, $mode='online')
    {
        return $this->games->filter(function($game, $key) use ($number, $mode){
            return (collect($game->multiplayer_modes)->pluck($mode.'max')->first() >= $number);
        });
    }

    public function couch()
    {
        return $this->multiplayerMode('splitscreen');
    }

    // --------------------------

    public function offlineMax(int $number)
    {
        return $this->maxPlayerNumber($number, 'offline');
    }

    public function onlineMax(int $number)
    {
        return $this->maxPlayerNumber($number);
    }

    public function offlineMin(int $number)
    {
        return $this->minPlayerNumber($number, 'offline');
    }

    public function onlineMin(int $number)
    {
        return $this->minPlayerNumber($number);
    }
}