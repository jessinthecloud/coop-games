<?php

namespace App\Models;

use MarcReichel\IGDBLaravel\Builder;

class Model extends \MarcReichel\IGDBLaravel\Models\Model
{
    /**
     * Model constructor.
     *
     * @param array $properties
     *
     * @throws ReflectionException
     */
    public function __construct(array $properties = [])
    {
        $this->builder = new Builder($this);
        self::$instance = $this;

        $this->setAttributes($properties);
        $this->setRelations($properties);
        $this->setIdentifier();
        $this->setEndpoint();
    }
    
    /**
     * @param mixed $method
     * @param mixed $parameters
     *
     * @return mixed
     * @throws ReflectionException
     */
    public static function __callStatic($method, $parameters)
    {
        $that = new static;
        return $that->forwardCallTo($that->newQuery(), $method, $parameters);
    }
}