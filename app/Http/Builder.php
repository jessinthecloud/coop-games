<?php

namespace App\Http;

class Builder extends \MarcReichel\IGDBLaravel\Builder
{
    /**
     * Builder constructor.
     *
     * @param $model
     *
     * @throws ReflectionException
     */
    public function __construct($model = null)
    {
        if ($model) {
            $this->setEndpoint($model);
        }

        $this->init();
    }
}