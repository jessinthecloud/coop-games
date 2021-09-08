<?php


namespace App\Models;

use App\Formatters\Formatter;
use App\Formatters\GameHtmlFormatter;
use Illuminate\Support\Carbon;
use MarcReichel\IGDBLaravel\Models\Game as IgdbGame;
use Throwable;

class Game extends IgdbGame
{
    use QuerySetup;

    protected static $fields = [
        'name',
        'slug',
        'first_release_date',
        'rating',
        'total_rating',
        'total_rating_count',
        'version_title',
        'storyline',
    ];

    protected static $detail_fields = [
        'summary',
        'rating',
        'aggregated_rating',
        'url',
        'follows',
        'hypes',
        'category',
        'status',
    ];

    protected static $with = [
        'cover' => ['url', 'image_id'],
        'platforms' => ['id', 'name', 'abbreviation', 'slug'],
        'multiplayer_modes',
        'genres'=> ['id', 'name', 'slug'],
        'collection',
    ];

    protected static $detail_with = [
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

    /**
     * @var \App\Formatters\GameHtmlFormatter 
     */
    public ?Formatter $formatter;

    public function __construct(array $properties = [], Formatter $formatter=null)
    {
        parent::__construct($properties);
        
        // only allow null and set because of static method calls
        // TODO: find a way to inject to constructor even when using __callStatic()
        $this->formatter = $formatter ?? null;
        if(isset($this->formatter) && !$this->formatter->hasGame() ){
            $this->formatter->setGame($this);
        }
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
        ?int $limit=6/*,
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
    public static function mostAnticipated(
        ?array $fields=null,
        ?array $with=null,
        ?int $limit=15/*,
        ?int $cache=null*/
    )
    {
        $query = self::querySetup($fields, $with)
            ->where('first_release_date', '>', now())
            ->orWhereNull('first_release_date')
             ->where(function ($query) {
                $query->where('follows', '>=', 3)
                    ->orWhere('hypes', '>=', 3);
             })
        ;
        return self::queryExecute($query, $limit, ['hypes', 'desc'], [
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
    public static function comingSoon(
        ?array $fields=null,
        ?array $with=null,
        ?int $limit=15/*,
        ?int $cache=null*/
    )
    {
        $after = Carbon::now()->addMonths(6)->timestamp;

        $query = self::querySetup($fields, $with)
            ->whereBetween('first_release_date', now(), $after)
            ->whereNotNull('first_release_date')
        ;
//        dump($query);
        return self::queryExecute($query, $limit, ['first_release_date', 'asc']);
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