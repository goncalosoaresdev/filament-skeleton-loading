<?php

declare(strict_types=1);

use Filament\Facades\Filament;
use Filament\Panel;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use MortalKiller\FilamentSkeletonLoading\SkeletonLoadingPlugin;

it('sets a validated skeleton radius for its panel', function (int|string|null $size, string $css) {
    $plugin = SkeletonLoadingPlugin::make();
    $size === null ? $plugin->rounded() : $plugin->rounded($size);

    $panel = Panel::make()->id('rounded')->plugin($plugin);
    Filament::setCurrentPanel($panel);
    $panel->boot();

    expect(FilamentView::renderHook(PanelsRenderHook::HEAD_END)->toHtml())
        ->toContain('--fsl-rounded:'.$css);
})->with([
    'default size' => [null, 'var(--radius-md, 6px)'],
    'theme size' => ['lg', 'var(--radius-lg, 0.5rem)'],
    'square' => ['none', '0'],
    'pixels' => ['8px', '8px'],
    'integer pixels' => [8, '8px'],
    'zero pixels' => [0, '0px'],
    'decimal pixels' => ['2.5px', '2.5px'],
]);

it('rejects invalid radius values', function (int|string $size) {
    expect(fn () => SkeletonLoadingPlugin::make()->rounded($size))
        ->toThrow(InvalidArgumentException::class);
})->with([-2, 'wide', '-2px', '2rem', '8px}</style>']);
