<?php

namespace App\Http\Controllers;

use App\Formatters\Formatter;
use App\Formatters\GameHtmlFormatter;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use MarcReichel\IGDBLaravel\Exceptions\InvalidParamsException;
use MarcReichel\IGDBLaravel\Exceptions\MissingEndpointException;
use MarcReichel\IGDBLaravel\Models\Platform;

class GameController extends Controller
{
    public Formatter $formatter;

    public function __construct(Formatter $formatter)
    {
        $this->formatter = $formatter;
    }

    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        $trending_games = Game::trending(null, null, 6);

        $trending_games = $trending_games->map(function($game, $key){
            $game->setFormatter($this->formatter);
            return $game->formatter->format();
        });

        $mostAnticipated = Game::mostAnticipated(null, null, 5);
        $mostAnticipated = $mostAnticipated->map(function($game, $key){
            $game->setFormatter($this->formatter);
            return $game->formatter->format();
        });

        $comingSoon = Game::comingSoon(null, null, 5);
        $comingSoon = $comingSoon->map(function($game, $key){
            $game->setFormatter($this->formatter);
            return $game->formatter->format();
        });

        /*$online_games = Game::online(null, null, 5);
        $online_games = $online_games->map(function($game, $key){
            $game->setFormatter($this->formatter);
            return $game->formatter->format();
        });

        $offline_games = Game::offline(null, null, 5);
        $offline_games = $offline_games->map(function($game, $key){
            $game->setFormatter($this->formatter);
            return $game->formatter->format();
        });*/

        return view('games.index', compact(
            'trending_games',
            'comingSoon',
            'mostAnticipated'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     */
    public function show(Request $request, string $slug)
    {
        $game = Game::bySlug($slug)->firstOrFail();
//        $game->similar_games = $game->similarGames();
        $game->setFormatter($this->formatter);
        $game = $game->formatter->format();

        return view('games.show', compact('game'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function edit(Game $game)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Game $game)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function destroy(Game $game)
    {
        //
    }
}
