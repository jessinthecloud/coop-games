<?php

namespace App\Http\Controllers;

use App\Formatters\Formatter;
use App\Models\BuilderInterface;
use App\Models\Game;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public Formatter $formatter;
    /**
     * @var \App\Models\GameBuilder
     */
    protected BuilderInterface $builder;

    public function __construct(BuilderInterface $builder, Formatter $formatter)
    {
//    ddd('pagecontroller builder: ', $builder);
        $this->formatter = $formatter;
        $this->builder = $builder;
    }
    
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        $trending_games = $this->builder->trending();
    
//    dump('trending ', $trending_games);

        $trending_games = $trending_games->map(function($game, $key){
//            $game->setFormatter($this->formatter);
//dump($this->formatter->format($game));
            return $this->formatter->format($game);
        });

        $mostAnticipated = $this->builder->mostAnticipated();
        $mostAnticipated = $mostAnticipated->map(function($game, $key){
            return $this->formatter->format($game);
        });

        $comingSoon = $this->builder->comingSoon();
        
        $comingSoon = $comingSoon->map(function($game, $key){
            return $this->formatter->format($game);
        });

        /*$online_games = Game::online(null, null, 5);
        $online_games = $online_games->map(function($game, $key){
            return $this->formatter->format($game);
        });

        $offline_games = Game::offline(null, null, 5);
        $offline_games = $offline_games->map(function($game, $key){
            return $this->formatter->format($game);
        });*/

        return view('pages.home', compact(
            'trending_games',
            'comingSoon',
            'mostAnticipated',
        ));
    } // index()

    public function about(  )
    {
        return view('pages.about');
    }

    public function contact(  )
    {
        return view('pages.contact');
    }
}
