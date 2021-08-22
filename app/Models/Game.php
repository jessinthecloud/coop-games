<?php


namespace App\Models;

use Illuminate\Support\Carbon;
use \MarcReichel\IGDBLaravel\Models\Game as IgdbGame;

class Game extends IgdbGame
{
    protected static function querySetup(
        ?array $fields=null,
        ?array $with=null/*,
        ?int $cache=null*/
    )
    {
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


        return IgdbGame::select(
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
        ?array $sort=[['total_rating_count', 'desc']]
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

    /**
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

        $before = Carbon::now()->subMonths(2)->timestamp;
        $after = Carbon::now()->addMonths(2)->timestamp;

        $query = self::querySetup($fields, $with);

        $query = $query
            ->where('first_release_date', '<', $after)
            ->where('total_rating_count', '>=', 2)
            ;

        return self::queryExecute($query, $limit);
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
            ->whereNotNull('total_rating_count');

        return self::queryExecute($query, $limit, ['total_rating_count', 'desc'], null);

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

        return self::queryExecute($query, $limit, ['first_release_date', 'desc'], null);

    }
}