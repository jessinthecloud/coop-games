## Changes Summary

### New Files Created (4)

| File | Purpose |
|------|---------|
| `app/Resolvers/CoopTypeResolver.php` | Extracts multiplayer mode → enum mapping logic (was domain logic inside the formatter) |
| `app/Resolvers/IgdbMediaResolver.php` | Extracts IGDB URL manipulation for covers, screenshots, and trailers |
| `resources/views/components/platform-list.blade.php` | Blade component rendering comma-separated platform abbreviations |
| `resources/views/components/genre-list.blade.php` | Blade component rendering linked genre list |
| `resources/views/components/company-list.blade.php` | Blade component rendering linked company list |

### Modified Files (8)

| File | What Changed |
|------|-------------|
| **`app/Formatters/Formatter.php`** | Added `formatAll(iterable $items): array` to the interface contract; added type hints (`object $item`, `: array`) to `format()` |
| **`app/Formatters/GameFormatter.php`** | Removed `use FormatsToHtml` trait; delegates to injected `CoopTypeResolver` and `IgdbMediaResolver`; `formatGames()` renamed to `formatAll()`; `platforms()`, `genres()`, `companies()` now return raw arrays instead of HTML strings; added `WEBSITE_CATEGORY_OFFICIAL` constant replacing magic number `1`; removed unused imports |
| **`app/Http/Controllers/GameController.php`** | Lines 41-43 replaced `collect()->map()` chain with `$this->formatter->formatAll($games)`; cleaned up unused imports (`FormatsToHtml`, `Carbon`, `Str`, `Game`, etc.) |
| **`app/Http/Controllers/PageController.php`** | All three `->map()` chains replaced with `$this->formatter->formatAll()` |
| **`app/Livewire/SearchBox.php`** | `formatForView()` now delegates to `$this->formatter->formatAll()`; removed unused `Http` import |
| **`resources/views/components/game-card.blade.php`** | `{!! $game['platforms'] !!}` → `<x-platform-list :platforms="$game['platforms']" />` |
| **`resources/views/components/game-listing-card.blade.php`** | Same `{!! !!}` → `<x-platform-list>` swap |
| **`resources/views/games/partials/_game-details-sidebar.blade.php`** | Platforms, genres, and companies all switched from `{!! !!}` raw HTML to `<x-platform-list>`, `<x-genre-list>`, and `<x-company-list>` components; also tightened `@if()` checks to `!empty()` for consistency |
| **`app/Traits/FormatsToHtml.php`** | Gutted to an empty `@deprecated` stub (nothing uses it anymore) |

### SOLID Principles Addressed

1. **SRP** — `GameFormatter` no longer handles domain logic (→ `CoopTypeResolver`), media URL rewriting (→ `IgdbMediaResolver`), or HTML generation (→ Blade components)
2. **OCP** — New Blade components or resolvers can be added without modifying `GameFormatter`
3. **LSP** — `Formatter` interface now has type-safe signatures and `formatAll()` in the contract, so any implementation is substitutable
4. **ISP** — The 14-method `FormatsToHtml` trait (11 empty stubs) is eliminated; Blade components only implement what they need
5. **DIP** — `GameFormatter` depends on injected `CoopTypeResolver` and `IgdbMediaResolver` via constructor injection; magic number replaced with named constant
