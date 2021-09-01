<?php

namespace App\Models;

use Throwable;

trait QuerySetup
{
    protected static function querySetupFields(
        ?array $fieldsArg=null,
        $listing=true/*,
        ?int $cache=null*/
    )
    {
        return ($fieldsArg === null && $listing === false) ? array_merge(self::$fields, self::$detail_fields) : self::$fields;
    }

    protected static function querySetupWith(
        ?array $withArg=null,
        $listing=true/*,
        ?int $cache=null*/
    )
    {
        return ($withArg === null && $listing === false) ? array_merge(self::$with, self::$detail_with) : self::$with;
    }

    protected static function queryExecute(
        $query,
        ?int $limit=15,
        ?array $order=['first_release_date', 'desc'],
        ?array $sort=null
    )
    {
        try {
            return $query
                /**
                 * can only have one sort field for IGDB API
                 * can have any number of order fields (but not sort)
                 *
                 * Must keep in mind what your main sort field is because the limit will
                 * mess with proper ordering
                 */
                ->orderBy(...$order)
                ->limit($limit)
                ->get()
                ->sortBy($sort);
        } catch (Throwable $e) {
            ddd($e);
        }
    }
}