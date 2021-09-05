<?php

namespace App\Models;

use App\Formatters\Formatter;
use MarcReichel\IGDBLaravel\Models\MultiplayerMode;

class CoopMode extends MultiplayerMode
{
    use QuerySetup;

    protected static $fields = [
        '*',
    ];

    protected static $detail_fields = [
    ];

    protected static $with = [
        'platform' => ['slug', 'abbreviation', 'name'],
    ];

    protected static $detail_with = [
    ];

    public ?Formatter $formatter;

    public function __construct(array $properties = [], Formatter $formatter=null)
    {
        parent::__construct($properties);

        // only allow null and set because of static method calls
        // TODO: find a way to inject to constructor even when using __callStatic()
        $this->formatter = $formatter ?? null;
        if( isset($this->formatter) && !$this->formatter->hasGame() ){

        }
    }

    /**
     * Inject formatter (if created model statically)
     *
     * @param \App\Formatters\Formatter $formatter
     */
    public function setFormatter(Formatter $formatter)
    {
        $this->formatter = $formatter;
//        $this->formatter->setGame($this);

        return $this;
    }
}
