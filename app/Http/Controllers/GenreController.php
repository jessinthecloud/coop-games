<?php

namespace App\Http\Controllers;

use App\Formatters\Formatter;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    /**
     * @var \App\Formatters\Formatter
     */
    protected Formatter $formatter;

    public function __construct(Formatter $formatter)
    {

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /** Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     */
    public function show(Request $request, string $slug)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function edit(Game $game)
    {

    }

    /**
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Models\Game  $game
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, Game $game)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function destroy(Game $game)
    {

    }
}