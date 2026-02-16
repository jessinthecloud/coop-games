<?php

namespace App\Formatters;

interface Formatter
{
    /**
     * Format a single item into a view-friendly array.
     *
     * @param  object  $item
     * @return array
     */
    public function format(object $item): array;

    /**
     * Format a collection of items into an array of view-friendly arrays.
     *
     * @param  iterable  $items
     * @return array
     */
    public function formatAll(iterable $items): array;
}
