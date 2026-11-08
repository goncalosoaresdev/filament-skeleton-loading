<?php

declare(strict_types=1);

namespace MortalKiller\FilamentSkeletonLoading\Tests\Fixtures;

use Filament\Widgets\Widget;

/** A widget without the package trait: its placeholder is Filament's own. */
final class StockWidget extends Widget
{
    protected string $view = 'filament-skeleton-loading::placeholder';
}
