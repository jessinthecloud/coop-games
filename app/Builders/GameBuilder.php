<?php


namespace App\Builders;

use App\Models\Game;
use App\Traits\HasGameFields;
use App\Traits\HasGameFilters;
use App\Traits\HasGameSorts;
use App\Traits\SetsUpQuery;
use Illuminate\Pagination\LengthAwarePaginator;
use MarcReichel\IGDBLaravel\Builder;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use MarcReichel\IGDBLaravel\Exceptions\MissingEndpointException;

class GameBuilder extends Builder implements BuilderInterface
{
    use HasGameFields, SetsUpQuery, HasGameFilters, HasGameSorts;

    private int $skipped=1;

    public function __construct(Game $model, Collection $query=null) 
    {
        parent::__construct($model);
    }

    /**
     * @param int $perPage
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     * @throws \MarcReichel\IGDBLaravel\Exceptions\MissingEndpointException
     */
    public function longPaginate(int $perPage=30): LengthAwarePaginator
    {
        $page = optional(request())->get('page', 1);

        $data = $this->forPage($page, 500)->get();

        return new LengthAwarePaginator($data->skip(($page - 1) * $perPage)->take($perPage), $data->count(), $perPage, $page, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
            'pageName' => 'page',
        ]);
    }

    /**
     * @param int $perPage
     *
     * @return \Illuminate\Pagination\Paginator
     * @throws \MarcReichel\IGDBLaravel\Exceptions\MissingEndpointException
     */
    public function paginate(int $perPage=30): Paginator
    {
        $page = optional(request())->get('page', 1);

        // in order to paginate, you must have more results to count
        $data = $this->forPage($page, $perPage)->get();

        return new Paginator($data->skip($perPage)->all(), $perPage, $page, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => 'page',
        ]);
    }

    /**
     * Set the limit and offset for a given page.
     *
     * @param int $page
     * @param int $perPage
     *
     * @return self
     */
    public function forPage(int $page, int $perPage = 15): self
    {
        // in order to paginate, you must have more results to count
        // shift the offset once per page, but don't limit results        
        return $this->skip(($page-1) * $perPage)->take(500);
    }

    /**
     * Get games with a high following that are
     * upcoming in the next 6 months
     *
     * @param int|null $perPage
     * @param int|null $limit
     *
     * @return \MarcReichel\IGDBLaravel\Builder
     * @throws \JsonException
     * @throws \ReflectionException
     */
    public function mostAnticipated(?int $perPage=30, ?int $limit=5)
    {
        $this->where('first_release_date', '>', now())
            ->orWhereNull('first_release_date')
            ->where(function ($query) {
                $query->where('follows', '>=', 3)
                    ->orWhere('hypes', '>=', 3);
            })
        ;
        return $this->executeQuery($this, $perPage, $limit, ['hypes', 'desc'], [
            ['follows', 'desc'],
        ]);
    }

    /**
     * Get games upcoming in the next 6 months
     *
     * @param int|null $perPage
     * @param int|null $limit
     *
     * @return \MarcReichel\IGDBLaravel\Builder
     * @throws \JsonException
     * @throws \ReflectionException
     */
    public function comingSoon(
        ?int $perPage=30,
        ?int $limit=15/*,
        ?int $cache=null*/
    )
    {
        $after = Carbon::now()->addMonths(6)->timestamp;

        $this->whereBetween('first_release_date', now(), $after)
            ->whereNotNull('first_release_date')
        ;
//        dump($query);
        return $this->executeQuery($this, $perPage, $limit, ['first_release_date', 'asc']);
    }

    /**
     * Games released within the previous 3 months or next 1 months
     * that have the most total ratings
     *
     * @param int|null $perPage
     * @param int|null $limit
     *
     * @return \MarcReichel\IGDBLaravel\Builder
     * @throws \JsonException
     * @throws \ReflectionException
     */
    public function trending(
        ?int $perPage=30,
        ?int $limit=10/*,
        ?int $cache=null*/
    )
    {
        // order by first release date desc
        // sort by total rating count

        $after = Carbon::now()->subYears()->timestamp;
        $before= Carbon::now()->addMonths(3)->timestamp;

        $this->whereBetween('first_release_date', $after, $before)
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

        return $this->executeQuery($this, $perPage, $limit, ['total_rating_count', 'desc'], [
            ['total_rating_count', 'desc'],
            ['first_release_date', 'desc']
        ]);
    }

    /**
     * Get games released in the last 3 months
     *
     * @param int|null $perPage
     * @param int|null $limit
     *
     * @return \MarcReichel\IGDBLaravel\Builder
     * @throws \JsonException
     * @throws \ReflectionException
     */
    public function listing(
        ?int $perPage=30,
        ?int $limit=null/*,
        ?int $cache=null*/
    )
    {
        return $this->executeQuery($this, $perPage, $limit, ['first_release_date', 'desc'], ['name', 'asc']);
    }

    /**
     * Get specific game by slug (for detail page)
     *
     * @param          $slug
     * @param int|null $perPage
     * @param int|null $limit
     *
     * @return \MarcReichel\IGDBLaravel\Builder
     *
     * @throws \JsonException
     * @throws \ReflectionException
     */
    public function bySlug(
        $slug,
        ?int $perPage=30,
        ?int $limit=1/*,
        ?int $cache=null*/
    )
    {
        $this->where('slug', 'like', $slug)
        // must get similar games only where they have coop
            /*->where(function($query){
                $query->where('similar_games.multiplayer_modes.onlinecoop', '=', true)
                    ->orWhere('similar_games.multiplayer_modes.offlinecoop', '=', true)
                    ;
            })
            ->whereNotNull('similar_games.multiplayer_modes')*/
        ;

//    dump($query, $slug);

        return $this->executeQuery($this, $perPage, $limit, ['first_release_date', 'desc'], null, false);
    } // bySlug()
    
}