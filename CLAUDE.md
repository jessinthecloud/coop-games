# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

A Laravel 8 web application that aggregates cooperative multiplayer video game data from the IGDB API (via Twitch credentials). It displays game listings with filtering by multiplayer mode, platform, and genre.

## Development Commands

```bash
# Start Docker containers (Laravel Sail)
./vendor/bin/sail up

# Asset building
npm run dev          # Build assets once
npm run watch        # Watch and rebuild on changes
npm run watch-poll   # Watch with polling (for WSL)
npm run prod         # Production build

# Laravel
php artisan migrate  # Run database migrations
php artisan tinker   # Interactive shell

# Tests
./vendor/bin/phpunit
```

## Architecture

### Data Flow

IGDB API → **GameBuilder** (query construction) → **GameFormatter** (data transformation) → **Controllers** → **Blade views**

### Key Layers

- **Builders** (`app/Builders/`): Fluent query builders for IGDB API queries. `GameBuilder` handles filtering by multiplayer mode (online, offline, LAN, splitscreen, campaign, couch, drop-in), sorting (trending, coming soon, most anticipated), and pagination.
- **Formatters** (`app/Formatters/`): Transform raw IGDB data into view-friendly structures — cover images, ratings, platforms, multiplayer player counts, screenshots, trailers.
- **Traits** (`app/Traits/`): Cross-cutting concerns split into `HasGameFields`, `HasGameFilters`, `HasGameSorts`, `SetsUpQuery`, `FormatsToHtml`.
- **Controllers** (`app/Http/Controllers/`): Lean controllers that delegate to builders and formatters via constructor injection.

### Models

Models extend from `marcreichel/igdb-laravel` package models (not standard Eloquent). `Game` model inherits from `MarcReichel\IGDBLaravel\Models\Game`.

### Frontend Stack

- **Blade** templates with **Tailwind CSS 2.x** utility classes
- **Alpine.js** for lightweight interactivity
- **Livewire** for the search component (`app/Http/Livewire/SearchBox.php`)
- **Laravel Mix** (Webpack) for asset bundling

### Routes

Defined in `routes/web.php`. Key patterns:
- `/games/{page?}` — game listings
- `/games/{slug}` — game detail
- `/platforms/{slug}` — platform-filtered listings
- `/genres/{slug}` — genre-filtered listings

### Multiplayer Mode Enum

`app/Enums/MultiplayerMode.php` defines: CAMPAIGN, LAN, OFFLINE, ONLINE, SPLITONLINE, COUCH, DROPIN.

## Environment Setup

Requires `TWITCH_CLIENT_ID` and `TWITCH_CLIENT_SECRET` in `.env` for IGDB API access. `IGDB_CACHE_LIFETIME` controls query cache duration (default 3600s). Database is MySQL with credentials configured for Laravel Sail.
