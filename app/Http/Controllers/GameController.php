<?php

namespace App\Http\Controllers;

use App\Builders\GameBuilder;
use App\Formatters\Formatter;
use App\Traits\FormatsToHtml;
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
    public GameBuilder $builder;

    public function __construct(Formatter $formatter, GameBuilder $builder)
    {
        $this->formatter = $formatter;
        $this->builder = $builder;
    }

    /**
     * Display a listing of the resource.
     *
     */
    public function index($page=null)
    {
        $page = $page ?? 1;
        
//        $games = $this->builder->select('name, slug, first_release_date')->get(); 
        
        $games_pager = $this->builder->listing();
//dump($games_pager, $games_pager->hasPages(), $games_pager->hasMorePages());        
        $games = $games_pager->items();
        
        $games = collect($games)->map(function($game, $key){
            return $this->formatter->format($game);
        });

        return view('games.index', compact(
            'games',
            'games_pager'
        ));
    }
    
    /**
     * Display the specified resource.
     *
     */
    public function show(Request $request, string $slug)
    {
        $game = $this->builder->bySlug($slug)->firstOrFail();
//        $game->similar_games = $game->similarGames();
        $game = $this->formatter->format($game);

        return view('games.show', compact('game'));
    }
}
