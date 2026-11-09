---
title: Installation
description: Install the package and register the plugin on a Filament panel.
---

## Requirements

- PHP 8.3, 8.4 or 8.5
- Laravel 12 or 13
- Filament 4.12.6+ or 5.8.1+

## Install

```bash
composer require mortalkiller/filament-skeleton-loading
```

Register the plugin on every panel that should use the skeletons:

```php
use MortalKiller\FilamentSkeletonLoading\SkeletonLoadingPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->plugin(SkeletonLoadingPlugin::make());
}
```

## Publish the stylesheet

The stylesheet is registered as a Filament asset. Publish it with Filament's assets:

```bash
php artisan filament:assets
```

`php artisan filament:upgrade`, which Filament's installer adds to the `post-autoload-dump` script, already runs it after every `composer install` or `composer update`.

## What changes right away

With the plugin registered and the stylesheet published:

- every lazy widget, relation manager and lazy Livewire component draws a generic skeleton instead of Filament's grey box;
- deferred tables, the database notifications panel and lazy loaded sections draw skeleton rows instead of a spinner.

The Workbench demo dashboard, without and with the plugin (its widgets also use the trait):

| Without the plugin | With the plugin |
| --- | --- |
| ![Filament's own grey loading boxes](/filament-skeleton-loading/screenshots/without-plugin-light.png) | ![Skeletons shaped like the widgets](/filament-skeleton-loading/screenshots/dashboard-light.png) |

Add the `HasSkeletonPlaceholder` trait to get skeletons shaped like stats, chart and table widgets. See [Widgets and tables](/filament-skeleton-loading/guides/widgets-and-tables/).

## Optional publishing

```bash
php artisan vendor:publish --tag=filament-skeleton-loading-config
php artisan vendor:publish --tag=filament-skeleton-loading-translations
php artisan vendor:publish --tag=filament-skeleton-loading-views
```
