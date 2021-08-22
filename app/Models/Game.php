<?php


namespace App\Models;

use Illuminate\Support\Carbon;
use MarcReichel\IGDBLaravel\Models\Game as IgdbGame;
use Throwable;

class Game extends IgdbGame
{

    protected $formatter;

    public function __construct(array $properties = [], $formatter=null)
    {
        parent::__construct($properties);

        // TODO: create formatter class to format data for view
        // $this->formatter = $formatter;
    }

    protected static function querySetup(
        $listing=true,
        ?array $fieldsArg=null,
        ?array $withArg=null/*,
        ?int $cache=null*/
    )
    {
        $fields = $fieldsArg ?? [
            'name',
            'slug',
            'first_release_date',
            'total_rating',
            'total_rating_count',
            'version_title',
            'storyline',
        ];

        $fields = ($fieldsArg !== null && $listing === false) ? array_merge($fields, [
            'summary',
            'rating',
            'aggregated_rating',
            'url',
            'follows',
            'hypes',
            'status',
        ]) : $fields;

        $with = $withArg ?? [
            'cover' => ['url', 'image_id'],
            'platforms' => ['id', 'name', 'abbreviation'],
            'multiplayer_modes',
            'genres',
            'collection',
        ];

        $with = ($withArg !== null && $listing === false) ? array_merge($with, [
            'age_ratings',
            'involved_companies',
            'player_perspectives',
            'parent_game',
            'release_dates',
            'screenshots',
            'similar_games',
            'summary',
            'version_parent',
            'category',
            'websites',
        ]) : $with;

        return IgdbGame::/*cache(0)->*/select(
            $fields
        )
            ->with(
                $with
            )
            ->where(
                function ($query) {
                    $query->where('multiplayer_modes.onlinecoop', '=', true)
                        ->orWhere('multiplayer_modes.offlinecoop', '=', true);
                }
            );
    }

    protected static function queryExecute(
        $query,
        ?int $limit=15,
        ?array $order=['first_release_date', 'desc'],
        ?array $sort=null
    )
    {
        try {
            return $query
                /**
                 * can only have one sort field for IGDB API
                 *
                 * Must keep in mind what your main sort field is because the limit will
                 * mess with proper ordering
                 */
                ->orderBy($order[0], $order[1])
                ->limit($limit)
                ->get()
                ->sortBy($sort);
        } catch (Throwable $e) {
            ddd($e);
        }
    }

// --------------------------------------------------------------------------------

    /**
     * Get released games with online co-op
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
     * Games released within the previous 3 months or next 1 months
     * that have the most total ratings
     *
     * @param array|null $fields
     * @param array|null $with
     * @param int|null   $limit
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public static function trending(
        ?array $fields=null,
        ?array $with=null,
        ?int $limit=15/*,
        ?int $cache=null*/
    )
    {
        // order by first release date desc
        // sort by total rating count

        $after = Carbon::now()->subYears()->timestamp;
        $before= Carbon::now()->addMonths(3)->timestamp;

        $query = self::querySetup($fields, $with);

        $query = $query
            ->whereBetween('first_release_date', $after, $before)
            ->whereNotNull('first_release_date')
            ->where('total_rating_count', '>=', 5)
            ->where(function ($query) {
                $query->where('follows', '>=', 1)
                    ->orWhere('total_rating_count', '>=', 5)/*
                    ->orWhere('hypes', '>=', 5)*/
                ;
            })
            ->where(function ($query) {
                $query->whereNotNull('follows')
                    ->orWhereNotNull('total_rating_count')/*
                    ->orWhereNotNull('hypes')*/
                ;
            })
            ;
//dump($query);
        return self::queryExecute($query, $limit, ['total_rating_count', 'desc'], [
            ['total_rating_count', 'desc'],
            ['first_release_date', 'desc']
        ]);
    }

    /**
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
}