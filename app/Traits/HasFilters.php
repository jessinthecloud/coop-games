<?php

namespace App\Traits;

trait HasFilters
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
    public function platforms($platforms)
    {
        return $this->whereIn('platform', $platforms);
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

    // TODO: add these secondary query filters:
    // genre - Genre
    // company - Company
    // age rating - AgeRating
    // release years (all)
    // franchise - Franchise
    // player perspective - PlayerPerspective

    public function sortByCriticRating($direction='asc')
    {
        return $this->orderBy('aggregated_rating', $direction);
    }

    public function sortByUserRating($direction='asc')
    {
        return $this->orderBy('rating', $direction);
    }

    public function sortByTotalRating($direction='asc')
    {
        return $this->orderBy('total_rating', $direction);
    }

    public function sortByFirstRelease($direction='asc')
    {
        return $this->orderBy('first_release_date', $direction);
    }

    public function sortByName($direction='asc')
    {
        return $this->orderBy('name', $direction);
    }

    public function sortByPopularity($direction='asc')
    {
        return $this->orderBy('total_rating_count', $direction);
    }

    public function sortByAgeRating($direction='asc')
    {
        return $this->orderBy('age_ratings.rating', $direction);
    }
}