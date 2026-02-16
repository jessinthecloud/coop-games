<?php

namespace App\Resolvers;

use App\Enums\MultiplayerMode;
use Illuminate\Support\Collection;

class CoopTypeResolver
{
    /**
     * Resolve coop types from a game's multiplayer modes.
     *
     * Maps raw IGDB multiplayer mode flags to MultiplayerMode enum labels
     * with max player counts, grouped by platform.
     *
     * @param  mixed  $game
     * @return \Illuminate\Support\Collection
     */
    public function resolve($game): Collection
    {
        return collect($game->multiplayer_modes)->map(function ($mode, $key) {
            $types = [];

            if (isset($mode['campaigncoop']) && $mode['campaigncoop']) {
                $types[$key]['label'] = MultiplayerMode::CAMPAIGN;
            }
            if (isset($mode['lancoop']) && $mode['lancoop']) {
                $types[$key]['label'] = MultiplayerMode::LAN;
                $types[$key]['max'] = !empty($mode['offlinemax']) ? $mode['offlinemax'] : null;
            }
            if (isset($mode['offlinecoop']) && $mode['offlinecoop']) {
                $types[$key]['label'] = MultiplayerMode::OFFLINE;
                $types[$key]['max'] = !empty($mode['offlinemax']) ? $mode['offlinemax'] : null;
            }
            if (isset($mode['onlinecoop']) && $mode['onlinecoop']) {
                $types[$key]['label'] = MultiplayerMode::ONLINE;
                $types[$key]['max'] = !empty($mode['onlinemax']) ? $mode['onlinemax'] : null;
            }
            if (isset($mode['splitscreen']) && $mode['splitscreen']) {
                $types[$key]['label'] = MultiplayerMode::COUCH;
                $types[$key]['max'] = !empty($mode['offlinemax']) ? $mode['offlinemax'] : null;
            }
            if (isset($mode['splitscreenonline']) && $mode['splitscreenonline']) {
                $types[$key]['label'] = MultiplayerMode::SPLITONLINE;
                $types[$key]['max'] = !empty($mode['onlinemax']) ? $mode['onlinemax'] : null;
            }

            return collect($mode)->merge([
                'coop-types' => $types,
            ]);
        });
    }
}
