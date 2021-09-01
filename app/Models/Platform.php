<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use MarcReichel\IGDBLaravel\Models\Platform as IgdbPlatform;
use Throwable;

class Platform extends IgdbPlatform
{
    use QuerySetup;

    protected const MODERN = [
        6, // PC
        48, // PS4
        49, // XboxOne
        130, // Switch
        167, // PS5
        169, // Series X|S
//        170 // Stadia
    ];

    protected static $fields = [
        'id', 'name', 'abbreviation', 'slug'
    ];

    protected static $detail_fields = [
    ];

    protected static $with = [
    ];

    protected static $detail_with = [
    ];

    protected $formatter;

    public function __construct(array $properties = [], $formatter=null)
    {
        parent::__construct($properties);

        // TODO: create formatter class to format data for view
        // $this->formatter = $formatter;
    }

    protected static function querySetup(
        ?array $fieldsArg=null,
        ?array $withArg=null,
        $listing=true/*,
        ?int $cache=null*/
    )
    {
        $fields = self::querySetupFields();
        $with = self::querySetupWith();

        return IgdbPlatform::/*cache(0)->*/select(
            $fields
        )
            ->with(
                $with
            )
            ->whereNotNull('slug')
            ;
    }

    /**
     * Get modern platforms
     *
     * @param array|null $fields
     * @param array|null $with
     * @param int|null   $limit
     *
     * @return mixed
     *
     * @throws \JsonException
     * @throws \ReflectionException
     */
    public static function modern(
        ?array $fields=null,
        ?array $with=null/*,
        ?int $cache=null*/
    )
    {
        $query = self::querySetup($fields, $with);

        $query = $query
            ->whereIn('id', self::MODERN)
        ;

        return self::queryExecute($query);
    }
}
