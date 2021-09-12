<?php

namespace App\Traits;

use Throwable;

trait HasFields
{
    protected $fields = [
        'name',
        'slug',
        'first_release_date',
        'rating',
        'total_rating',
        'total_rating_count',
        'version_title',
        'storyline',
    ];

    protected $detail_fields = [
        'summary',
        'rating',
        'aggregated_rating',
        'url',
        'follows',
        'hypes',
        'category',
        'status',
    ];

    protected $with = [
        'cover' => ['url', 'image_id'],
        'platforms' => ['id', 'name', 'abbreviation', 'slug'],
        'multiplayer_modes',
        'genres'=> ['id', 'name', 'slug'],
        'collection',
    ];

    protected $detail_with = [
        'age_ratings',
        'involved_companies',
        'involved_companies.company' => [
            'id',
            'name',
            'slug',
            'url',
        ],
        'player_perspectives',
        'parent_game',
        'release_dates',
        'screenshots',
        'videos',
        /*'similar_games' => [
            'id',
            'name',
            'slug',
            'cover',
            'first_release_date',
            'platforms',
            'genres',
            'summary',
            'rating',
            'multiplayer_modes',
        ],
        'similar_games.cover',
        'similar_games.platforms',
        'similar_games.genres',
        'similar_games.multiplayer_modes',*/
        'version_parent',
        'websites',
    ];
}