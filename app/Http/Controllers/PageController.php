<?php

namespace App\Http\Controllers;

use App\Builders\BuilderInterface;
use App\Formatters\Formatter;
use App\Models\Game;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public Formatter $formatter;
    /**
     * @var \App\Builders\BuilderInterface
     */
    protected BuilderInterface $builder;

    public function __construct(BuilderInterface $builder, Formatter $formatter)
    {
        $this->builder = $builder;
        $this->formatter = $formatter;
    }
    
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        $trending_games = $this->builder->trending();
    
// dump('trending ', $trending_games);

        $trending_games = $this->formatter->formatAll($trending_games);

        $mostAnticipated = $this->builder->mostAnticipated();
        $mostAnticipated = $this->formatter->formatAll($mostAnticipated);

        $comingSoon = $this->builder->comingSoon();
        $comingSoon = $this->formatter->formatAll($comingSoon);

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
