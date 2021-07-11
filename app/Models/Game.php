<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class Game extends Model
{
    use HasFactory;

    public static function fetchGames(string $key='popular-games', $duration=600, $cache=true) {

        $after = Carbon::now()->addMonths(2)->timestamp;
//Cache::flush();
        return Cache::remember($key, $duration, function() use($after) {

            return Http::withHeaders(config('services.igdb'))
                ->withBody(
                // fields * for all fields
                // cover.url references the cover table url field/column, etc
                // platforms: 6: PC, 48: PS4, 49: XboxOne, 130: switch, 169: Series X, 167: PS5
                    "fields name, cover.url, first_release_date, total_rating_count, platforms.abbreviation, rating, slug, multiplayer_modes.*;
	                    where platforms = (48,49,130,6,167,169)
	                        & first_release_date < {$after}
                            & total_rating_count >= 2
                            & (multiplayer_modes.onlinecoop = true
                                | multiplayer_modes.offlinecoop = true
                                | multiplayer_modes.lancoop = true
                                | multiplayer_modes.splitscreen = true
                                | multiplayer_modes.splitscreenonline = true
                                | multiplayer_modes.campaigncoop = true);
	                    sort total_rating_count desc;
	                    limit 15;", "text/plain"
                )->post(env('IGDB_URL').'games')
                ->json();
        });
    }
}
