<?php

namespace App\Traits;

trait HasGameFields
{
    // fields to always select
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
    // additional fields to select on the game details page
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

    // relations to always get
    protected $with = [
        'cover' => ['url', 'image_id'],
        'platforms' => ['id', 'name', 'abbreviation', 'slug'],
        'multiplayer_modes',
        'genres'=> ['id', 'name', 'slug'],
        'collection',
    ];

    // additional relations to get on the game details page
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