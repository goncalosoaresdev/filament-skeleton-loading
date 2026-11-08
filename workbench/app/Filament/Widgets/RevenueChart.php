<?php

declare(strict_types=1);

namespace Workbench\App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use MortalKiller\FilamentSkeletonLoading\Concerns\HasSkeletonPlaceholder;
use Workbench\App\Support\Latency;

final class RevenueChart extends ChartWidget
{
    use HasSkeletonPlaceholder;

    protected static ?int $sort = 2;

    protected ?string $heading = 'Revenue per month';

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        Latency::wait(1.3);

        return [
            'datasets' => [['label' => 'Revenue', 'data' => [3200, 4100, 3800, 5200, 4700, 6100]]],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        ];
    }
}
