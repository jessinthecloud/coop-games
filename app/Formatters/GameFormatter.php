<?php

namespace App\Formatters;

use App\Resolvers\CoopTypeResolver;
use App\Resolvers\IgdbMediaResolver;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class GameFormatter implements Formatter
{
    public const WEBSITE_CATEGORY_OFFICIAL = 1;


    public function __construct(
        protected CoopTypeResolver $coopTypeResolver,
        protected IgdbMediaResolver $mediaResolver
    ) {
    }

    public function format(object $item): array
    {
        return collect($item)->merge([
            'cover_url'          => $this->mediaResolver->cover(
                isset($item->cover) ? $item->cover['url'] : null
            ),
            'rating'             => $this->rating($item),
            'aggregated_rating'  => $this->criticRating($item),
            'platforms'          => $this->platforms($item),
            'first_release_date' => $this->date($item),
            'multiplayer_modes'  => $this->coopTypeResolver->resolve($item),
            'genres'             => $this->genres($item),
            'companies'          => $this->companies($item),
            'screenshots'        => $this->mediaResolver->screenshots(
                $item['screenshots'] ?? null
            ),
            'trailer'            => $this->mediaResolver->trailer(
                $item['videos'] ?? null
            ),
            'stores'             => $this->stores($item),
            'websites'           => $this->websites($item),
            'website'            => $this->officialWebsite($item),
            'summary'            => $item->summary ?? '',
        ])->toArray();
    }

    public function formatAll(iterable $items): array
    {
        return collect($items)->map(function ($game) {
            return $this->format($game);
        })->toArray();
    }

    // ------------------------------------------------------------------
    // Data transformation methods
    // ------------------------------------------------------------------

    public function date($game, string $format = 'M d, Y'): ?string
    {
        return !empty($game->first_release_date)
            ? Carbon::parse($game->first_release_date)->format($format)
            : null;
    }

    /**
     * @param  object  $game
     * @param  int|null  $score
     *
     * @return int|string
     */
    public function rating($game, $score = null)
    {
        $rating = $score ?? $game->rating;
        return !empty($rating) ? round($rating) : '';
    }

    /**
     * @param  object  $game
     * @param  int|null  $score
     *
     * @return int|string
     */
    public function criticRating($game, $score = null)
    {
        $rating = $score ?? $game->aggregated_rating;
        return !empty($rating) ? round($rating) : '';
    }

    /**
     * Return raw platform data for the Blade component to render.
     *
     * @param  object  $game
     * @return array
     */
    public function platforms($game): array
    {
        return !empty($game->platforms)
            ? collect($game->platforms)->toArray()
            : [];
    }

    public function numPlayers($game, $mode = 'onlinecoop', $limiter = 'max'): Collection
    {
        // num players and multiplayer_mode are relative to platform
        return collect($game->multiplayer_modes)->map(function ($mode, $key) {
            // onlinecoopmax, offlinecoopmax, per platform
            // TODO: finish implementing
        });
    }

    /**
     * Return raw genre data for the Blade component to render.
     *
     * @param  object  $game
     * @return array
     */
    public function genres($game): array
    {
        return !empty($game->genres)
            ? collect($game->genres)->toArray()
            : [];
    }

    /**
     * Return separated developer and publisher collections for the Blade component.
     *
     * @param  object  $game
     * @return array{devs: array, pubs: array}
     */
    public function companies($game): array
    {
        $involved = !empty($game->involved_companies)
            ? collect($game->involved_companies)
            : collect();

        return [
            'devs' => $involved->filter(fn($c) => $c['developer'] === true)->values()->toArray(),
            'pubs' => $involved->filter(fn($c) => $c['publisher'] === true)->values()->toArray(),
        ];
    }

    /**
     * @param  object  $game
     *
     * @return array|\Illuminate\Support\Collection
     */
    public function similarGames($game)
    {
        return !empty($game['similar_games'])
            ? collect($game['similar_games'])->map(function ($game) {
                return collect($game)->merge([
                    'cover_url'          => $this->mediaResolver->cover(
                        $game['cover']['url'] ?? null
                    ),
                    'platforms'          => $game['platforms'] ?? [],
                    'rating'             => isset($game['rating']) ? round($game['rating']) : null,
                    'first_release_date' => isset($game['first_release_date'])
                        ? Carbon::parse($game['first_release_date'])->format('M d, Y')
                        : null,
                ]);
            })->take(5)
            : [];
    }

    /**
     * @param  object  $game
     *
     * @return mixed|string
     */
    public function officialWebsite($game)
    {
        return !empty($game['websites'])
            ? collect($game['websites'])->filter(function ($website) {
                return (int)$website['category'] === self::WEBSITE_CATEGORY_OFFICIAL;
            })->pluck('url')->first()
            : '';
    }

    /**
     * @param  object  $game
     */
    public function stores($game)
    {
        // TODO: Implement stores() method
    }

    /**
     * @param  object  $game
     */
    public function websites($game)
    {
        // TODO: Implement websites() method
    }
}
