<?php

namespace App\Traits;

trait HasGameSorts
{
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