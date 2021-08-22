<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use MarcReichel\IGDBLaravel\Exceptions\InvalidParamsException;
use MarcReichel\IGDBLaravel\Exceptions\MissingEndpointException;
use MarcReichel\IGDBLaravel\Models\Game;
use MarcReichel\IGDBLaravel\Models\Platform;

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
                        'platforms' => ['id', 'name', 'abbreviation'],
                        'multiplayer_modes',
                    ]
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
                ->limit(15)
                ->get()
                ->sortByDesc('total_rating_count');
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
