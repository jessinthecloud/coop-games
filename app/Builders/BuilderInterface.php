<?php

namespace App\Builders;

interface BuilderInterface
{
    public function get();

    public function paginate();
}