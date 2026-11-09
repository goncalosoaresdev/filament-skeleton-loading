<?php

declare(strict_types=1);

use Filament\Facades\Filament;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Filament\Widgets\StatsOverviewWidget;
use Livewire\Drawer\Utils;
use Livewire\Mechanisms\ExtendBlade\ExtendBlade;
use MortalKiller\FilamentSkeletonLoading\Concerns\HasSkeletonPlaceholder;
use MortalKiller\FilamentSkeletonLoading\SkeletonLoadingPlugin;
use MortalKiller\FilamentSkeletonLoading\Tests\Fixtures\LatestOrders;
use MortalKiller\FilamentSkeletonLoading\Tests\Fixtures\Plain;
use MortalKiller\FilamentSkeletonLoading\Tests\Fixtures\RevenueChart;
use MortalKiller\FilamentSkeletonLoading\Tests\Fixtures\SalesStats;
use MortalKiller\FilamentSkeletonLoading\Tests\Fixtures\ShareChart;
use MortalKiller\FilamentSkeletonLoading\Tests\Fixtures\StockWidget;

function skeletonOf(string $widget): string
{
    return app($widget)->placeholder()->render();
}

it('registers its stylesheet', function () {
    expect(FilamentAsset::getStyles([SkeletonLoadingPlugin::PACKAGE]))->not->toBeEmpty()
        ->and(SkeletonLoadingPlugin::current())->toBeInstanceOf(SkeletonLoadingPlugin::class);
});

it('draws one tile per stats column', function () {
    expect(substr_count(skeletonOf(SalesStats::class), 'fsl-stat'))->toBe(3);
});

it('draws a default stats placeholder without evaluating its metrics', function () {
    $widget = new class extends StatsOverviewWidget
    {
        use HasSkeletonPlaceholder;

        protected function getStats(): array
        {
            throw new LogicException('Statistics must stay deferred.');
        }
    };

    expect(substr_count($widget->placeholder()->render(), 'fsl-stat'))->toBe(4);
});

it('draws bars with the chart heading', function () {
    expect(skeletonOf(RevenueChart::class))->toContain('fsl-chart-bars')->toContain('Revenue');
});

it('draws a ring for doughnut charts', function () {
    expect(skeletonOf(ShareChart::class))->toContain('fsl-chart-ring');
});

it('draws the configured number of table rows', function () {
    expect(substr_count(skeletonOf(LatestOrders::class), 'fsl-row'))->toBe(3);
});

it('falls back to a generic skeleton', function () {
    expect(skeletonOf(Plain::class))->toContain('fsl-skeleton--generic');
});

it('replaces the lazy loading section of Filament', function () {
    expect(view('filament::components.loading-section')->render())
        ->toContain('fsl-skeleton--generic')
        ->toContain('role="status"');
});

it('lets Livewire mount a nested lazy widget on the entire placeholder', function (string $panel, string $placeholderClass) {
    Filament::setCurrentPanel(Filament::getPanel($panel));
    $widget = app(StockWidget::class);
    $blade = app(ExtendBlade::class);
    $blade->startLivewireRendering($widget);

    try {
        $html = Utils::insertAttributesIntoHtmlRoot($widget->placeholder()->render(), ['wire:id' => 'lazy-widget']);
    } finally {
        $blade->endLivewireRendering();
    }

    expect(ltrim($html))->toStartWith('<div wire:id="lazy-widget"')
        ->and($html)->toContain($placeholderClass);
})->with([
    'with the plugin' => ['test', 'fsl-skeleton--generic'],
    'without the plugin' => ['plain', 'fi-loading-section'],
]);

it('can turn the shimmer off', function () {
    $plugin = SkeletonLoadingPlugin::make()->shimmer(false);

    expect($plugin->hasShimmer())->toBeFalse()
        ->and(Filament::getCurrentPanel()->getId())->toBe('test');
});

it('loads its stylesheet only through the panel', function () {
    expect(FilamentView::renderHook(PanelsRenderHook::STYLES_AFTER)->toHtml())->toContain('skeleton-loading.css')
        ->and(FilamentAsset::renderStyles())->not->toContain('skeleton-loading');
});
