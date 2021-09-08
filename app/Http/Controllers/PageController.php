<?php

namespace App\Http\Controllers;

use App\Formatters\Formatter;
use App\Models\Game;
use Illuminate\Http\Request;

class PageController extends Controller
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
