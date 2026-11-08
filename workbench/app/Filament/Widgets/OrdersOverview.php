<?php

declare(strict_types=1);

namespace Workbench\App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use MortalKiller\FilamentSkeletonLoading\Concerns\HasSkeletonPlaceholder;
use Workbench\App\Support\Latency;

final class OrdersOverview extends StatsOverviewWidget
{
    use HasSkeletonPlaceholder;

    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        Latency::wait();

        return [
            Stat::make('Orders', '1,284')->description('Last 30 days'),
            Stat::make('Revenue', '€48,920')->description('Last 30 days'),
            Stat::make('Average order', '€38.10')->description('Last 30 days'),
            Stat::make('Returning customers', '42%')->description('Last 30 days'),
        ];
    }
}
