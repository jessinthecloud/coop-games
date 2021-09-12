<?php


namespace App\Models;

use App\Models\BuilderInterface;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use MarcReichel\IGDBLaravel\Builder;
use MarcReichel\IGDBLaravel\Exceptions\MissingEndpointException;

class GameBuilder extends Builder implements BuilderInterface
{
    use HasFields, SetsUpQuery;
    
    public Collection $query;
        
    public function __construct($model = null, Collection $query=null) 
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

    // online -- boolean
    // offline -- boolean
    // campaign -- boolean
    // splitscreen -- boolean
    // drop-in -- boolean
    // LAN -- boolean
    // platform -- Platform
    // release year -- value (date)
    // max players -- value (int)

    /**
     * @param int $limit
     *
     * @return Paginator
     * @throws MissingEndpointException
     */
    public function paginate(int $limit = 10): Paginator
    {
        $page = optional(request())->get('page', 1);

        $data = $this->forPage($page, $limit)->get();

        return new Paginator($data, $limit);
    }
    
    
    
    
    

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