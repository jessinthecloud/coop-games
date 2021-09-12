<?php


namespace App\Models;

use MarcReichel\IGDBLaravel\Builder;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use MarcReichel\IGDBLaravel\Exceptions\MissingEndpointException;

class GameBuilder extends Builder implements BuilderInterface
{
    use HasFields, SetsUpQuery;
    
    public function __construct(Game $model, Collection $query=null) 
    {
        parent::__construct($model);
    }
    
    /**
     * Get games with a high following that are
     * upcoming in the next 6 months
     *
     * @param array|null $fields
     * @param array|null $with
     * @param int|null   $limit
     *
     * @return mixed|string
     *
     * @throws \Exception
     */
    public function mostAnticipated(?int $limit=5)
    {
        $this->where('first_release_date', '>', now())
            ->orWhereNull('first_release_date')
            ->where(function ($query) {
                $query->where('follows', '>=', 3)
                    ->orWhere('hypes', '>=', 3);
            })
        ;
        return $this->executeQuery($this, $limit, ['hypes', 'desc'], [
            ['follows', 'desc'],
        ]);
    }

    /**
     * Get games upcoming in the next 6 months
     *
     * @param array|null $fields
     * @param array|null $with
     * @param int|null   $limit
     *
     * @return mixed|string
     *
     * @throws \Exception
     */
    public function comingSoon(
        ?int $limit=15/*,
        ?int $cache=null*/
    )
    {
        $after = Carbon::now()->addMonths(6)->timestamp;

        $this->whereBetween('first_release_date', now(), $after)
            ->whereNotNull('first_release_date')
        ;
//        dump($query);
        return $this->executeQuery($this, $limit, ['first_release_date', 'asc']);
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
    public function trending(
        ?int $limit=6/*,
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

        return $this->executeQuery($this, $limit, ['total_rating_count', 'desc'], [
            ['total_rating_count', 'desc'],
            ['first_release_date', 'desc']
        ]);
    }

    /**
     * Get games released in the last 3 months
     *
     * @param int|null $limit
     *
     * @return mixed|string
     *
     * @throws \JsonException
     * @throws \ReflectionException
     */
    public function listing(
        ?int $limit=30/*,
        ?int $cache=null*/
    )
    {
        return $this->executeQuery($this, $limit, ['name', 'asc']);
    }

    /**
     * Get specific game by slug (for detail page)
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
    public function bySlug(
        $slug,
        ?int $limit=1/*,
        ?int $cache=null*/
    )
    {
        $this->where('slug', 'like', $slug)
            /*->where(function($query){
                $query->where('similar_games.multiplayer_modes.onlinecoop', '=', true)
                    ->orWhere('similar_games.multiplayer_modes.offlinecoop', '=', true)
                    ;
            })
            ->whereNotNull('similar_games.multiplayer_modes')*/
        ;

//    dump($query, $slug);

        return $this->executeQuery($this, $limit, ['first_release_date', 'desc'], null, false);
    } // bySlug()
    
    // ##################################

    // online -- boolean
    // offline -- boolean
    // campaign -- boolean
    // splitscreen -- boolean
    // drop-in -- boolean
    // LAN -- boolean
    // platform -- Platform
    // release year -- value (date)
    // max players -- value (int)
    
   
    // ###############################
    // deprecated methods?     

    protected function multiplayerMode($type)
    {
        return $this->games->filter(function ($item, $index) use ($type){
            return collect($item->multiplayer_modes)->contains($type, true);
        });
    }

    protected function maxPlayerNumber(int $number, $mode='onlinecoop')
    {
        return $this->games->filter(function($game, $key) use ($number, $mode){
            return (collect($game->multiplayer_modes)->pluck($mode.'max')->first() <= $number);
        });
    }

    protected function minPlayerNumber(int $number, $mode='onlinecoop')
    {
        return $this->games->filter(function($game, $key) use ($number, $mode){
            return (collect($game->multiplayer_modes)->pluck($mode.'max')->first() >= $number);
        });
    }

    // --------------------------

    public function couch()
    {
        return $this->multiplayerMode('splitscreen');
    }

    public function offlineMax(int $number)
    {
        return $this->maxPlayerNumber($number, 'offline');
    }

    public function onlineMax(int $number)
    {
        return $this->maxPlayerNumber($number);
    }

    public function offlineMin(int $number)
    {
        return $this->minPlayerNumber($number, 'offline');
    }

    public function onlineMin(int $number)
    {
        return $this->minPlayerNumber($number);
    }
}