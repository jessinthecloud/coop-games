<?php

namespace App\Resolvers;

use Illuminate\Support\Str;

class IgdbMediaResolver
{
    private const PLACEHOLDER_COVER = 'https://via.placeholder.com/264x352';

    /**
     * Resolve cover URL, replacing IGDB thumb size with cover_big.
     *
     * @param  string|null  $url
     * @return string
     */
    public function cover(?string $url): string
    {
        return !empty($url)
            ? Str::replaceFirst('thumb', 'cover_big', $url)
            : self::PLACEHOLDER_COVER;
    }

    /**
     * Resolve screenshot URLs in huge and big sizes.
     *
     * @param  array|null  $screenshots
     * @param  int  $limit
     * @return array|\Illuminate\Support\Collection
     */
    public function screenshots(?array $screenshots, int $limit = 9)
    {
        return !empty($screenshots)
            ? collect($screenshots)->map(function ($screenshot) {
                return [
                    'huge' => Str::replaceFirst('thumb', 'screenshot_huge', $screenshot['url']),
                    'big'  => Str::replaceFirst('thumb', 'screenshot_big', $screenshot['url']),
                ];
            })->take($limit)
            : [];
    }

    /**
     * Resolve trailer embed URL from IGDB video data.
     *
     * @param  array|null  $videos
     * @return string
     */
    public function trailer(?array $videos): string
    {
        return !empty($videos[0]['video_id'])
            ? 'https://youtube.com/embed/' . $videos[0]['video_id']
            : '';
    }
}
