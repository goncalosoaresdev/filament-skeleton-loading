---
title: HasSkeletonPlaceholder
description: Trait that gives lazy widgets and relation managers a skeleton shaped like them.
---

`MortalKiller\FilamentSkeletonLoading\Concerns\HasSkeletonPlaceholder` overrides Filament's `placeholder()` on a lazy component.

```php
use Filament\Widgets\StatsOverviewWidget;
use MortalKiller\FilamentSkeletonLoading\Concerns\HasSkeletonPlaceholder;

class OrdersOverview extends StatsOverviewWidget
{
    use HasSkeletonPlaceholder;

    protected ?int $skeletonItems = 3;
}
```

## Requirements

Use it on Filament components that can be lazy loaded: widgets and relation managers. The trait calls the parent `placeholder()`, `getPlaceholderData()` and `getPlaceholderHeight()`.

## Properties

Declare them on the component to fine-tune the skeleton. Both are optional.

| Property | Default | Used by |
| --- | --- | --- |
| `protected ?int $skeletonItems` | the declared integer column count, or 4 | Stats widgets: number of tiles. |
| `protected ?int $skeletonRows` | 5 | Table widgets and relation managers: number of rows. |

## Methods

| Method | Description |
| --- | --- |
| `placeholder(): View` | The `filament-skeleton-loading::placeholder` view with the skeleton data. Falls back to the parent placeholder when the package is disabled or the current panel does not use the plugin. |

## Shapes

| Component | `kind` | Notes |
| --- | --- | --- |
| `StatsOverviewWidget` | `stats` | One tile per column. |
| `ChartWidget` | `chart` | `ring` for doughnut, pie and polar area; `line` for line and radar; `bars` otherwise. The heading is the chart's heading with tags stripped. |
| `TableWidget`, `RelationManager` | `table` | A header and `$skeletonRows` rows. |
| Other components | `generic` | A title and two lines. |

When the chart type or heading cannot be read before the widget loads, the skeleton falls back to bars without a heading.
