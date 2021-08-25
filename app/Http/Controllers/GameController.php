<?php

namespace App\Http\Controllers;

use App\Filters\GameFilterer;
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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(GameFilterer $filterer, GameHtmlFormatter $formatter)
    {
        $trending_games = Game::trending();

        $formatter->setGames($trending_games);
        $trending_games = $formatter->format();

        $online_games = []; //Game::online();
        $offline_games = []; //Game::offline();
//        dump($games);

//        $filterer->setGamesCollection($games);
//        dump($filterer->couch());
//        ddd($filterer->onlineMin(3));

        return view('games.index', compact('trending_games', 'online_games', 'offline_games'));
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
    public function show(Request $request, string $slug, GameHtmlFormatter $formatter)
    {
        $formatter->setGame(Game::bySlug($slug)->first());
        $game = $formatter->format();

        dump($game);

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
