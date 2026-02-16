<?php

namespace App\Http\Controllers;

use App\Builders\GameBuilder;
use App\Formatters\Formatter;
use Illuminate\Http\Request;

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
        
        $games = $this->formatter->formatAll($games);

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
