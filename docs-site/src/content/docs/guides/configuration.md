---
title: Configuration
description: Global settings and per-panel plugin options.
---

## Global settings

Set them in `.env`, or publish the config file:

```bash
php artisan vendor:publish --tag=filament-skeleton-loading-config
```

| Setting | Env | Default | Effect |
| --- | --- | --- | --- |
| `enabled` | `SKELETON_LOADING_ENABLED` | `true` | Off: Filament's own grey placeholder and spinner everywhere, the trait included, and no page loader. |
| `defer_tables` | `SKELETON_LOADING_DEFER_TABLES` | `false` | Every table of the panels using the plugin loads after the page, with skeleton rows. |

## Per panel

| Method | Default | Effect |
| --- | --- | --- |
| `shimmer(bool $condition = true)` | on | Light sweeping across the skeleton. |
| `pageLoader(bool $condition = true)` | off | Full-screen loader between pages. See [Page loader](/filament-skeleton-loading/guides/page-loader/). |
| `pageLoaderSymbol(string\|Htmlable\|Closure $symbol)` | three dots | Brand symbol of the page loader. |
| `pageLoaderDelay(int $milliseconds)` | `300` | How long a page may take before the loader shows. Negative values become `0`. |
| `pageLoaderLabel(string\|Closure $label)` | "Loading…" | Text under the symbol, also read by screen readers. |
| `deferTables(bool\|Closure $condition = true)` | the `defer_tables` setting | Calls `deferLoading()` on the panel's tables; a closure receives each table and decides. |

```php
use MortalKiller\FilamentSkeletonLoading\SkeletonLoadingPlugin;

$panel->plugin(
    SkeletonLoadingPlugin::make()
        ->shimmer(false)
        ->deferTables(),
);
```

## Reduced motion and dark mode

The shimmer and the page loader always stand still for users who prefer reduced motion, whatever `shimmer()` says. Skeleton colours follow Filament's light and dark mode.

![Dashboard skeletons in dark mode](/filament-skeleton-loading/screenshots/dashboard-dark.png)

## Accessibility

Every skeleton is a `role="status"` region with `aria-busy="true"` and Filament's translated "Loading" label for screen readers. The shapes themselves are `aria-hidden`.
