<?php

declare(strict_types=1);

namespace Workbench\App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use MortalKiller\FilamentSkeletonLoading\Concerns\HasSkeletonPlaceholder;
use Workbench\App\Support\Latency;

final class ChannelShare extends ChartWidget
{
    use HasSkeletonPlaceholder;

    protected static ?int $sort = 3;

    protected ?string $heading = 'Orders per channel';

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getData(): array
    {
        Latency::wait(1.6);

        return [
            'datasets' => [['label' => 'Orders', 'data' => [620, 410, 254]]],
            'labels' => ['Web shop', 'Marketplace', 'Phone'],
        ];
    }
}
