<?php

namespace App\Models;

use App\Formatters\Formatter;
use App\Formatters\GameFormatter;
use App\Traits\HasFields;
use Illuminate\Support\Carbon;
use MarcReichel\IGDBLaravel\Models\Game as IgdbGame;
use Throwable;

class Game extends IgdbGame
{
    use HasFields;

// -------------------------------------------------------------

    /**
     * @deprecated
     * Get released games with online co-op
     *
     * @param array|null $fields
     * @param array|null $with
     * @param int|null   $limit
     *
     * @return mixed
     *
     */
    public static function online(
        ?array $fields=null,
        ?array $with=null,
        ?int $limit=15/*,
        ?int $cache=null*/
    )
    {
        $query = self::querySetup($fields, $with);

        $query = $query
            ->where('multiplayer_modes.onlinecoop', '=', true)
//            ->where('first_release_date', '<=', Carbon::now()->timestamp)
            ->whereNotNull('first_release_date')
        ;

        return self::queryExecute($query, $limit);
    }

    /**
     * @deprecated
     * Get released games with offline co-op
     *
     * @param array|null $fields
     * @param array|null $with
     * @param int|null   $limit
     *
     * @return mixed
     *
     * @throws \JsonException
     * @throws \ReflectionException
     */
    public static function offline(
        ?array $fields=null,
        ?array $with=null,
        ?int $limit=15/*,
        ?int $cache=null*/
    )
    {
        $query = self::querySetup($fields, $with);

        $query = $query
                    ->where('multiplayer_modes.offlinecoop', '=', true)
//                    ->where('first_release_date', '<=', Carbon::now()->timestamp)
                    ->whereNotNull('first_release_date')
                ;

        return self::queryExecute($query, $limit);
    }

    /**
     * @deprecated
     * Get released games with couch co-op
     *
     * @param array|null $fields
     * @param array|null $with
     * @param int|null   $limit
     *
     * @return mixed
     *
     * @throws \JsonException
     * @throws \ReflectionException
     */
    public static function couch(
        ?array $fields=null,
        ?array $with=null,
        ?int $limit=15/*,
        ?int $cache=null*/
    )
    {
        $query = self::querySetup($fields, $with);

        $query = $query->where('multiplayer_modes.splitscreen', '=', true)
//                    ->where('first_release_date', '<=', Carbon::now()->timestamp)
                    ->whereNotNull('first_release_date')
                 ;

        return self::queryExecute($query, $limit);
    }

    /**
     * @deprecated
     * Get games that have the most ratings of all time
     *
     * @param array|null $fields
     * @param array|null $with
     * @param int|null   $limit
     *
     * @return mixed|string
     *
     * @throws \Exception
     */
    public static function popular(
        ?array $fields=null,
        ?array $with=null,
        ?int $limit=15/*,
        ?int $cache=null*/
    )
    {
        // all time highest total rating counts desc

        $query = self::querySetup($fields, $with)
            ->whereNotNull('total_rating_count')
            ->where('total_rating_count', '>=', 5)
           /* ->where(function ($query) {
                $query->where('follows', '>=', 5)
                    ->orWhere('total_rating_count', '>=', 5)
                    ->orWhere('hypes', '>=', 5);
            })*/
        ;
        return self::queryExecute($query, $limit, ['total_rating_count', 'desc'], [
//            ['follows', 'desc'],
            ['total_rating_count', 'desc'],
//            ['hypes', 'desc'],
            ['first_release_date', 'desc']
        ]);
    }

    /**
     * @deprecated
     * Get games released in the last 3 months
     *
     * @param array|null $fields
     * @param array|null $with
     * @param int|null   $limit
     *
     * @return mixed|string
     *
     * @throws \Exception
     */
    public static function recentReleases(
        ?array $fields=null,
        ?array $with=null,
        ?int $limit=15/*,
        ?int $cache=null*/
    )
    {
        // order by first release date desc
        // released in the past 3(?) months

        $after = Carbon::now()->subMonths(3)->timestamp;

        $query = self::querySetup($fields, $with)
            ->whereBetween('first_release_date', $after, now())
            ->whereNotNull('first_release_date')
        ;

        return self::queryExecute($query, $limit, ['first_release_date', 'desc']);
    }

    /**
     * @deprecated
     * Search for game
     *
     * @param array|null $fields
     * @param array|null $with
     * @param int|null   $limit
     *
     * @return mixed
     *
     * @throws \JsonException
     * @throws \ReflectionException
     */
    public static function searchFor(
        $text,
        ?array $fields=[
            'name',
            'slug',
            'first_release_date',
            'rating',
        ],
        ?array $with=[
            'cover' => ['url', 'image_id'],
        ],
        ?int $limit=5/*,
        ?int $cache=null*/
    )
    {
        $query = self::querySetup($fields, $with, false)
            ->where(function ($query) use ($text) {
                $query->where('name', 'like', '%'.$text.'%')
                ->orWhere('slug', 'like', '%'.$text.'%');  
            })
        ;

//    dump($query);

        return self::queryExecute($query, $limit, ['name', 'desc']);
    } // search()

    /*
        'similar_games' => [
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
        'similar_games.multiplayer_modes',
        'version_parent',
        'websites',
     */
    /**
     * @deprecated
     *            
     * @return mixed
     */
    public function similarGames()
    {
        $query = self::querySetup(self::$fields, self::$with)
            ->whereIn('id', collect($this->similar_games)->pluck('id')->all())
            /*->where(function($query){
                $query->where('similar_games.multiplayer_modes.onlinecoop', '=', true)
                    ->orWhere('similar_games.multiplayer_modes.offlinecoop', '=', true)
                ;
            })
            ->whereNotNull('similar_games.multiplayer_modes')*/
        ;

        return self::queryExecute($query, 5);
    }
}