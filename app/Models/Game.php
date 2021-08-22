<?php


namespace App\Models;

use Illuminate\Support\Carbon;
use \MarcReichel\IGDBLaravel\Models\Game as IgdbGame;

class Game extends IgdbGame
{
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

        $before = Carbon::now()->subMonths(2)->timestamp;
        $after = Carbon::now()->addMonths(2)->timestamp;

        try {
            return IgdbGame::select(
                $fields
            )
                ->with(
                    $with
                )
                ->where('first_release_date', '<', $after)
                ->where('total_rating_count', '>=', 2)
                ->where(
                    function ($query) {
                        $query->where('multiplayer_modes.onlinecoop', '=', true)
                            ->orWhere('multiplayer_modes.offlinecoop', '=', true);
                    }
                )
                /**
                 * can only have one sort field for IGDB API
                 *
                 * Must keep in mind what your main sort field is because the limit will
                 * mess with proper ordering
                 */
                ->orderBy('first_release_date', 'desc')
                ->limit($limit)
                ->get()
                ->sortByDesc('total_rating_count');
        } catch (\Throwable $e) {
            ddd($e);
        }
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

        try {
            return IgdbGame::select(
                $fields
            )
                ->with(
                    $with
                )
                ->whereNotNull('total_rating_count')
                ->where(
                    function ($query) {
                        $query->where('multiplayer_modes.onlinecoop', '=', true)
                            ->orWhere('multiplayer_modes.offlinecoop', '=', true);
                    }
                )
                /**
                 * can only have one sort field for IGDB API
                 *
                 * Must keep in mind what your main sort field is because the limit will
                 * mess with proper ordering
                 */
                ->orderBy('total_rating_count', 'desc')
                ->limit($limit)
                ->get();
        } catch (\Throwable $e) {
            ddd($e);
        }
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

        try {
            return IgdbGame::select(
                $fields
            )
                ->with(
                    $with
                )
                ->whereBetween('first_release_date', $after, now())
                ->where(
                    function ($query) {
                        $query->where('multiplayer_modes.onlinecoop', '=', true)
                            ->orWhere('multiplayer_modes.offlinecoop', '=', true);
                    }
                )
                /**
                 * can only have one sort field for IGDB API
                 *
                 * Must keep in mind what your main sort field is because the limit will
                 * mess with proper ordering
                 */
                ->orderBy('first_release_date', 'desc')
                ->limit($limit)
                ->get();
        } catch (\Throwable $e) {
            ddd($e);
        }
    }
}