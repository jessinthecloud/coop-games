<?php


namespace App\Models;

use Illuminate\Support\Carbon;
use \MarcReichel\IGDBLaravel\Models\Game as IgdbGame;

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
        ?array $fields=null,
        ?array $with=null/*,
        ?int $cache=null*/
    )
    {
        $fields = $fields ?? [
            'name',
            'first_release_date',
            'rating',
            'slug',
            'total_rating_count',
            'follows',
            'hypes',
        ];

        $with = $with ?? [
            'cover' => ['url', 'image_id'],
            'platforms' => ['id', 'name', 'abbreviation'],
            'multiplayer_modes',
        ];


        return IgdbGame::cache(0)->select(
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
        } catch (\Throwable $e) {
            ddd($e);
        }
    }

// --------------------------------------------------------------------------------

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
        ;

        return self::queryExecute($query, $limit);
    }

    public static function offline(
        ?array $fields=null,
        ?array $with=null,
        ?int $limit=15/*,
        ?int $cache=null*/
    )
    {
        $query = self::querySetup($fields, $with);

        $query = $query->where('multiplayer_modes.offlinecoop', '=', true);

        return self::queryExecute($query, $limit);
    }

    public static function couch(
        ?array $fields=null,
        ?array $with=null,
        ?int $limit=15/*,
        ?int $cache=null*/
    )
    {
        $query = self::querySetup($fields, $with);

        $query = $query->where('multiplayer_modes.splitscreen', '=', true);

        return self::queryExecute($query, $limit);
    }

    /**
     * Games released within the previous 3 months or next 1 months
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

        $after = Carbon::now()->subYears(1)->timestamp;
        $before= Carbon::now()->addMonths(3)->timestamp;

        $query = self::querySetup($fields, $with);

        $query = $query
            ->whereBetween('first_release_date', $after, $before)
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

        $fields = $fields ?? [
            'name',
            'first_release_date',
            'total_rating_count',
            'rating',
            'slug',
        ];

        $with = $with ?? [
            'cover' => ['url', 'image_id'],
            'platforms' => ['id', 'name', 'abbreviation'],
            'multiplayer_modes',
        ];

        $after = Carbon::now()->subMonths(3)->timestamp;

        $query = self::querySetup($fields, $with)
            ->whereBetween('first_release_date', $after, now());

        return self::queryExecute($query, $limit, ['first_release_date', 'desc']);
    }
}