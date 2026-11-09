<?php

declare(strict_types=1);

namespace MortalKiller\FilamentSkeletonLoading\Tests\Fixtures;

use Filament\Widgets\StatsOverviewWidget;
use MortalKiller\FilamentSkeletonLoading\Concerns\HasSkeletonPlaceholder;

final class SalesStats extends StatsOverviewWidget
{
    use HasSkeletonPlaceholder;

    protected int|array|null $columns = 3;

    protected function getStats(): array
    {
        return [];
    }
}
