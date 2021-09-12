<?php

namespace App\Models;

interface BuilderInterface
{
    public function get();

    public function paginate();
}