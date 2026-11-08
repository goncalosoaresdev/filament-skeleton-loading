<?php

declare(strict_types=1);

namespace Workbench\App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Workbench\App\Support\Latency;

/** A slow page, to show the page loader on the way in. */
final class Reports extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentChartBar;

    protected static ?string $slug = 'reports';

    protected string $view = 'workbench::reports';

    public function mount(): void
    {
        Latency::wait();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('refresh')
                ->label('Refresh')
                ->action(fn () => Latency::wait(0.5)),
        ];
    }
}
