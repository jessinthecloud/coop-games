<?php

namespace App\Traits;

trait HasGameFilters
{
    public function online()
    {
        // online -- boolean
        return $this->where('multiplayer_modes.onlinecoop', '=', true);
    }

    // offline -- boolean
    public function offline()
    {
        return $this->where('multiplayer_modes.offlinecoop', '=', true);
    }

    // campaign -- boolean
    public function campaign()
    {
        return $this->where('multiplayer_modes.campaigncoop', '=', true);
    }

    // splitscreen -- boolean
    public function splitscreen()
    {
        return $this->where(function($query){
            return $query->where('multiplayer_modes.splitscreen', '=', true)
                ->orWhere('multiplayer_modes.splitscreenonline', '=', true)
            ;
        });
    }

    public function splitscreenLocal()
    {
        return $this->where('multiplayer_modes.splitscreen', '=', true);
    }

    public function splitscreenOnline()
    {
        return $this->where('multiplayer_modes.splitscreenonline', '=', true);
    }

    // drop-in -- boolean
    public function dropIn()
    {
        return $this->where('multiplayer_modes.dropin', '=', true);
    }

    // LAN -- boolean
    public function lan()
    {
        return $this->where('multiplayer_modes.lancoop', '=', true);
    }

    // platform -- Platform
    public function platforms(array $platforms)
    {
        return $this->whereIn('platforms', $platforms);
    }

    // TODO: platform exclusive (only to on platform family?
    public function platformExclusive($platform)
    {
        return $this->where('platform', $platform);
    }

    // release year -- value (int/string)
    public function releaseYear($year)
    {
        return $this->whereYear('first_release_date', $year);
    }
    
    // max players -- value (int)
    public function maxPlayers($num)
    {
        return $this->where(function($query) use($num) {
            return $query->where('multiplayer_modes.offlinecoopmax',  '<=', $num)
                ->orWhere('multiplayer_modes.onlinecoopmax',  '<=', $num)
            ;
        });
    }

    public function minPlayers($num)
    {
        return $this->where(function($query) use($num) {
            return $query->where('multiplayer_modes.offlinecoopmax',  '>=', $num)
                ->orWhere('multiplayer_modes.onlinecoopmax',  '>=', $num)
                ;
        });
    }

    public function maxPlayersOnline($num)
    {
        return $this->where('multiplayer_modes.onlinecoopmax', '<=', $num);
    }

    public function minPlayersOnline($num)
    {
        return $this->where('multiplayer_modes.onlinecoopmax', '>=', $num);
    }

    public function maxPlayersOffline($num)
    {
        return $this->where('multiplayer_modes.offlinecoopmax', '<=', $num);
    }

    public function minPlayersOffline($num)
    {
        return $this->where('multiplayer_modes.offlinecoopmax', '>=', $num);
    }

    /**
     * @param array $genres - array of Genre IDs
     *
     * @return \App\Builders\GameBuilder
     */
    public function genres(array $genres)
    {
        return $this->whereIn('genres', $genres);
    }

    /**
     * @param array $companies - array of Involved Company IDs
     *
     * @return \App\Builders\GameBuilder
     */
    public function companies(array $involved_companies)
    {
        return $this->whereIn('involved_companies', $involved_companies);
    }

    /**
     * @param array $age_ratings - array of AgeRatings
     */
    public function ageRating(array $age_ratings)
    {
        return $this->whereIn('age_ratings', $age_ratings);
    }
    
    /**
     * Where year is any of hte release years
     * 
     * @param int|string $year
     *
     * @return \App\Builders\GameBuilder
     * @throws \JsonException
     * @throws \ReflectionException
     */
    public function releaseYears($year)
    {
        return $this->whereYear('release_dates.y', $year);
    }

    /**
     * @param string $franchise
     *
     * @return \App\Builders\GameBuilder
     */
    public function franchise(string $franchise)
    {
        return $this->where('franchise.name', 'like',  $franchise);
    }

    /**
     * @param array $player_perspectives - array of AgeRatings
     */
    public function playerPerspective(array $player_perspectives)
    {
        return $this->whereIn('player_perspectives', $player_perspectives);
    }

    public function popular()
    {
        return $this->where('total_rating_count', '>', 5);
    }
}