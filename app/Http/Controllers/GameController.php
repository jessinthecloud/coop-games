<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use MarcReichel\IGDBLaravel\Exceptions\InvalidParamsException;
use MarcReichel\IGDBLaravel\Exceptions\MissingEndpointException;
use MarcReichel\IGDBLaravel\Models\Game;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $before = Carbon::now()->subMonths(2)->timestamp;
        $after = Carbon::now()->addMonths(2)->timestamp;
        /*
         "fields name, cover.url, first_release_date, total_rating_count, platforms.abbreviation, rating, slug, multiplayer_modes.*;
	                    where platforms = (48,49,130,6,167,169)
	                        & first_release_date < {$after}
                            & total_rating_count >= 2
                            & (multiplayer_modes.onlinecoop = true
                                | multiplayer_modes.offlinecoop = true
                                | multiplayer_modes.lancoop = true
                                | multiplayer_modes.splitscreen = true
                                | multiplayer_modes.splitscreenonline = true
                                | multiplayer_modes.campaigncoop = true);
	                    sort total_rating_count desc;
	                    limit 15;", "text/plain"
         */

        try {
            $games = Game::cache(0)->select(
                [
                    'name',
                    'first_release_date',
                    'total_rating_count',
                    'rating',
                    'slug',
                ]
            )
                ->with(
                    [
                        'cover' => ['url', 'image_id'],
                        'platforms' => ['abbreviation'],
                        'multiplayer_modes' => function($query){
                            $query->where('onlinecoop', true)
                                ->orWhere('offlinecoop', true)
                                ->orWhere('lancoop', true)
                                ->orWhere('splitscreen', true)
                                ->orWhere('splitscreenonline', true)
                                ->orWhere('campaigncoop', true);
                        },
                    ]
                )
                ->whereIn('platforms', [48, 49, 130, 6, 167, 169])
                ->where('first_release_date', '<', $after)
                ->where('total_rating_count', '>=', 2)
                /*->where(
                    function ($query) {
                        return $query->where('onlinecoop', true)
                            ->orWhere('offlinecoop', true)
                            ->orWhere('lancoop', true)
                            ->orWhere('splitscreen', true)
                            ->orWhere('splitscreenonline', true)
                            ->orWhere('campaigncoop', true);
                    }
                )*/
                ->orderBy('total_rating_count', 'desc')
                ->limit(15)
                ->get();
            ddd($games);
        } catch (\Throwable $e) {
            ddd($e);
        }
        return view('layouts.app', compact('games'));
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
     * @param  \App\Models\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function show(Game $game)
    {
        //
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
