<?php


namespace App\Models;

use App\Formatters\Formatter;
use Illuminate\Support\Carbon;
use MarcReichel\IGDBLaravel\Models\Game as IgdbGame;
use Throwable;

class Game extends IgdbGame
{
    /**
     * @deprecated
     */
    use QuerySetup;
    
    use HasFields;

    /**
     * @var \App\Formatters\Formatter|null
     */
    public ?Formatter $formatter;

    public function __construct(
        array $properties = [], 
        Formatter $formatter=null, 
        BuilderInterface $builder=null
    )
    {
        parent::__construct($properties);

        $this->builder = $builder;
        $this->formatter = $formatter;
        
    dump('formatter: ', $formatter, 'builder: ', $builder);

        /*// only allow null and set because of static method calls
        // TODO: find a way to inject to constructor even when using __callStatic()
        $this->formatter = $formatter ?? null;*/
        if(isset($this->formatter) && !$this->formatter->hasGame() ){
            $this->formatter->setGame($this);
        }
    }

    /**
     * Inject builder
     *
     * @param \App\Builders\Builder $builder
     */
    public function setBuilder(Builder $builder)
    {
        $this->builder = $builder;
//        $this->builder->setGame($this);

        return $this;
    }

    /**
     * Inject formatter (if created model statically)
     * 
     * @param \App\Formatters\Formatter $formatter
     */
    public function setFormatter(Formatter $formatter)
    {
        $this->formatter = $formatter;
        $this->formatter->setGame($this);
        
        return $this;
    }

    /**
     * @deprecated
     *            
     * Setup fields and filters for every game query
     *
     * @param array|null $fieldsArg
     * @param array|null $withArg
     * @param bool       $listing
     *
     * @return \MarcReichel\IGDBLaravel\Builder
     * @throws \JsonException
     * @throws \ReflectionException
     */
    protected static function querySetup(
        ?array $fieldsArg=null,
        ?array $withArg=null,
        $listing=true/*,
        ?int $cache=null*/
    )
    {
        $fields = self::querySetupFields($fieldsArg, $listing);
        $with = self::querySetupWith($withArg, $listing);

        return Game::/*cache(0)->*/select(
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
            )
            ->whereNotNull('slug')
            ;
    }

// -------------------------------------------------------------

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
    public static function bySlug(
        $slug,
        ?array $fields=null,
        ?array $with=null,
        ?int $limit=1/*,
        ?int $cache=null*/
    )
    {
        $query = self::querySetup($fields, $with, false);
        $query = $query->where('slug', 'like', $slug)
            /*->where(function($query){
                $query->where('similar_games.multiplayer_modes.onlinecoop', '=', true)
                    ->orWhere('similar_games.multiplayer_modes.offlinecoop', '=', true)
                    ;
            })
            ->whereNotNull('similar_games.multiplayer_modes')*/
        ;

//    dump($query, $slug);

        return self::queryExecute($query, $limit);
    } // bySlug()

    /**
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
    public static function listing(
        ?array $fields=null,
        ?array $with=null,
        ?int $limit=30/*,
        ?int $cache=null*/
    )
    {
        $query = self::querySetup($fields, $with, true);

        return self::queryExecute($query, $limit, ['name', 'asc']);
    }

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