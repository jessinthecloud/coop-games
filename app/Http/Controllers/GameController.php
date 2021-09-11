<?php

namespace App\Http\Controllers;

use App\Formatters\Formatter;
use App\Formatters\FormatsToHtml;
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
        $games = Game::listing();

        $games = $games->map(function($game, $key){
            $game->setFormatter($this->formatter);
            return $game->formatter->format();
        });

        return view('games.index', compact(
            'games'
        ));
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
}
