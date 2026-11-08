---
title: Widgets and tables
description: Typed skeletons for lazy widgets and relation managers, and skeleton rows for deferred tables.
---

## Typed widget skeletons

Add `HasSkeletonPlaceholder` to a lazy widget or relation manager. Both are lazy by default in Filament.

```php
use Filament\Widgets\ChartWidget;
use MortalKiller\FilamentSkeletonLoading\Concerns\HasSkeletonPlaceholder;

class RevenueChart extends ChartWidget
{
    use HasSkeletonPlaceholder;

    protected ?string $heading = 'Revenue per month';

    // ...
}
```

The trait picks the shape from the class:

| Class | Skeleton |
| --- | --- |
| `StatsOverviewWidget` | One tile per column of the widget (4 when the columns are not a plain number). |
| `ChartWidget` | Bars for bar charts and unknown types, a line for `line` and `radar`, a ring for `doughnut`, `pie` and `polarArea`. The chart's real heading is shown above it. |
| `TableWidget`, `RelationManager` | A header and 5 rows. |
| Anything else | The generic skeleton: a title and two lines. |

The skeleton keeps the widget's column span, column start and placeholder height, so the layout does not jump when the content arrives.

### Fine-tuning

```php
protected ?int $skeletonItems = 3; // stat tiles (stats widgets)
protected ?int $skeletonRows = 8;  // rows (table widgets and relation managers)
```

### Widgets without the trait

Every lazy widget, relation manager or Livewire component that renders Filament's `loading-section` view draws the generic skeleton, with no code change.

## Deferred tables

Use Filament's own deferred loading:

```php
public function table(Table $table): Table
{
    return $table
        ->deferLoading()
        ->columns([/* ... */]);
}
```

While the records load, the table draws skeleton rows instead of Filament's spinner.

To defer every table of a panel, call `deferTables()` on the plugin or turn the `defer_tables` setting on. A closure decides per table:

```php
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Table;

SkeletonLoadingPlugin::make()
    ->deferTables(fn (Table $table): bool => $table->getLivewire() instanceof ListRecords);
```

Filtering, sorting and paginating keep Filament's own behaviour: the table dims in place.

## Notifications and lazy sections

- The database notifications panel draws three placeholder notifications while it loads.
- Lazy loaded schemas (`Section::make()->schema([...])->lazyLoaded()`) draw form rows, a label and a field each, while they load.

Both work as soon as the plugin is registered.

## Tests in your application

A deferred table has no records until it loads. Load it before asserting:

```php
Livewire::test(ListOrders::class)
    ->loadTable()
    ->assertCanSeeTableRecords($orders);
```

If you turn `defer_tables` on, pin `SKELETON_LOADING_DEFER_TABLES` in `phpunit.xml` so the tests do not depend on `.env`.
