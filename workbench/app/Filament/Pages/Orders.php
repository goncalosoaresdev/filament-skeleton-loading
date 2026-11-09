<?php

declare(strict_types=1);

namespace Workbench\App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Workbench\App\Support\Latency;

/** A deferred table: the page shows at once and the rows load after it. */
final class Orders extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingCart;

    protected static ?string $slug = 'orders';

    protected string $view = 'workbench::orders';

    public function table(Table $table): Table
    {
        return $table
            ->deferLoading()
            ->records(function (): array {
                Latency::wait();

                return [
                    ['id' => 1, 'reference' => 'ORD-1001', 'customer' => 'Customer A', 'status' => 'Paid', 'total' => '€120.00'],
                    ['id' => 2, 'reference' => 'ORD-1002', 'customer' => 'Customer B', 'status' => 'Shipped', 'total' => '€86.50'],
                    ['id' => 3, 'reference' => 'ORD-1003', 'customer' => 'Customer C', 'status' => 'Paid', 'total' => '€42.90'],
                    ['id' => 4, 'reference' => 'ORD-1004', 'customer' => 'Customer D', 'status' => 'Pending', 'total' => '€310.00'],
                    ['id' => 5, 'reference' => 'ORD-1005', 'customer' => 'Customer E', 'status' => 'Shipped', 'total' => '€18.75'],
                ];
            })
            ->columns([
                TextColumn::make('reference'),
                TextColumn::make('customer'),
                TextColumn::make('status'),
                TextColumn::make('total'),
            ])
            ->paginated(false);
    }
}
