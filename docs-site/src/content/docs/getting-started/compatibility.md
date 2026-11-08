---
title: Compatibility
description: Supported PHP, Laravel and Filament versions.
---

| Package | PHP | Laravel | Filament |
| --- | --- | --- | --- |
| 1.x | 8.3, 8.4, 8.5 | 12, 13 | 4.12.6+ and 5.8.1+ |

The package depends on `filament/filament`, because it registers a panel plugin, and on `spatie/laravel-package-tools`.

## Tested boundaries

CI runs the PHP test suite against:

- the lowest dependency set of each Filament major on PHP 8.3 and Laravel 12 (Filament 4.12.6 and 5.8.1);
- the latest Filament 4 and 5 releases on PHP 8.4 and 8.5 with Laravel 13.

The browser tests run the Workbench panel with Filament 4.12.6 and the latest Filament 5.

The lowest-boundary jobs ignore one known Filament advisory inside CI only, to keep testing the oldest supported release. Applications should run the latest patch release of their Filament major.
