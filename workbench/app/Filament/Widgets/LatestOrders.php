<?php

declare(strict_types=1);

namespace Workbench\App\Filament\Widgets;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use MortalKiller\FilamentSkeletonLoading\Concerns\HasSkeletonPlaceholder;
use Workbench\App\Support\Latency;

final class LatestOrders extends TableWidget
{
    use HasSkeletonPlaceholder;

    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 'full';

    protected ?int $skeletonRows = 5;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Latest orders')
            ->records(function (): array {
                Latency::wait(1.9);

                return [
                    ['id' => 1, 'reference' => 'ORD-1001', 'customer' => 'Customer A', 'total' => '€120.00'],
                    ['id' => 2, 'reference' => 'ORD-1002', 'customer' => 'Customer B', 'total' => '€86.50'],
                    ['id' => 3, 'reference' => 'ORD-1003', 'customer' => 'Customer C', 'total' => '€42.90'],
                    ['id' => 4, 'reference' => 'ORD-1004', 'customer' => 'Customer D', 'total' => '€310.00'],
                    ['id' => 5, 'reference' => 'ORD-1005', 'customer' => 'Customer E', 'total' => '€18.75'],
                ];
            })
            ->columns([
                TextColumn::make('reference'),
                TextColumn::make('customer'),
                TextColumn::make('total'),
            ])
            ->paginated(false);
    }
}
