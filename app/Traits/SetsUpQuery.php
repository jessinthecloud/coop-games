<?php

namespace App\Traits;

use Throwable;

trait SetsUpQuery
{
    /**
     * @param array|null $fieldsArg
     * @param bool       $listing
     *
     * @return array|string[]
     */
    protected function setupFields(
        ?array $fieldsArg=null,
        $listing=true/*,
        ?int $cache=null*/
    )
    {
        return ($fieldsArg === null && $listing === false) 
            ? array_merge($this->fields, $this->detail_fields) 
            : ($fieldsArg ?? $this->fields);
    }

    protected function setupWith(
        ?array $withArg=null,
        $listing=true/*,
        ?int $cache=null*/
    )
    {
        return ($withArg === null && $listing === false) 
            ? array_merge($this->with, $this->detail_with) 
                : ($withArg ?? $this->with);
    }
   
    /**
     * Ensure fields and filters for every query
     * (bascially global scope)
     *
     * @param array|null $fields
     * @param array|null $fieldsArg
     * @param array|null $withArg
     * @param bool       $listing
     *
     * @return \MarcReichel\IGDBLaravel\Builder
     * @throws \JsonException
     * @throws \ReflectionException
     */
    protected function executeQuery(
        $query,
        ?int $limit=15,
        array $order=['first_release_date', 'desc'],
        ?array $sort=null,
        bool $listing=true,
        ?array $fieldsArg=null,
        ?array $withArg=null
    )
    {
        $fields = $this->setupFields($fieldsArg, $listing);
        $with = $this->setupWith($withArg, $listing);
        
        try {
        
            return $query
                ->select(
                    $fields
                )
                ->with(
                    $with
                )
                ->where(
                    function ($query) {
                        $query->where('multiplayer_modes.onlinecoop', '=', true)
                            ->orWhere('multiplayer_modes.offlinecoop', '=', true);
                    }
                )
                ->whereNotNull('slug')
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
            ddd($e, $query);
        }
    }
}