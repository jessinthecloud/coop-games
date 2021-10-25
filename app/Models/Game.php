<?php

namespace App\Models;

use App\Formatters\Formatter;
use App\Formatters\GameFormatter;
use App\Traits\HasGameFields;
use Illuminate\Support\Carbon;
use MarcReichel\IGDBLaravel\Models\Game as IgdbGame;
use Throwable;

class Game extends IgdbGame
{
    use HasGameFields;

// -------------------------------------------------------------

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