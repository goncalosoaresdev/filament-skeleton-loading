<?php

declare(strict_types=1);

use Filament\Facades\Filament;
use Filament\Panel;
use Filament\Schemas\Schema;
use Filament\Support\Facades\FilamentView;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\View\TablesRenderHook;
use Filament\View\PanelsRenderHook;
use MortalKiller\FilamentSkeletonLoading\Enums\SchemaPlaceholder;
use MortalKiller\FilamentSkeletonLoading\SkeletonLoadingPlugin;
use MortalKiller\FilamentSkeletonLoading\Tests\Fixtures\LatestOrders;

it('keeps search skeletons opt-in per panel', function () {
    expect(FilamentView::renderHook(PanelsRenderHook::GLOBAL_SEARCH_END)->toHtml())->toBeEmpty();

    $panel = Panel::make()->id('search')->plugin(SkeletonLoadingPlugin::make()->globalSearchSkeletons());
    Filament::setCurrentPanel($panel);
    $panel->boot();

    expect(FilamentView::renderHook(PanelsRenderHook::GLOBAL_SEARCH_END)->toHtml())
        ->toContain('wire:target="search"')
        ->toContain('aria-hidden="true"');
});

it('lets a panel enable each search surface independently', function (bool $selects, bool $globalSearch) {
    $plugin = SkeletonLoadingPlugin::make()
        ->selectSearchSkeletons($selects)
        ->globalSearchSkeletons($globalSearch);
    $panel = Panel::make()->id('search')->plugin($plugin);
    Filament::setCurrentPanel($panel);
    $panel->boot();

    expect(str_contains(FilamentView::renderHook(PanelsRenderHook::BODY_START)->toHtml(), 'data-fsl-search'))
        ->toBe($selects)
        ->and(str_contains(FilamentView::renderHook(PanelsRenderHook::GLOBAL_SEARCH_END)->toHtml(), 'wire:target="search"'))
        ->toBe($globalSearch);
})->with([[true, false], [false, true], [true, true], [false, false]]);

it('uses only visible table columns while records are deferred', function () {
    if (! defined(TablesRenderHook::class.'::CONTENT_BEFORE')) {
        $this->markTestSkipped('This Filament version uses the CSS fallback.');
    }

    $table = Table::make(app(LatestOrders::class))->columns([
        TextColumn::make('name'),
        TextColumn::make('status'),
        TextColumn::make('secret')->hidden(),
    ]);

    $render = fn (mixed $records): string => FilamentView::renderHook(
        TablesRenderHook::CONTENT_BEFORE,
        data: ['table' => $table, 'records' => $records],
    )->toHtml();

    expect($render(null))->toContain('data-fsl-columns="2"')
        ->and($render(collect()))->toBeEmpty();

    $table->contentGrid(['md' => 2, 'xl' => 3]);

    expect($render(null))->toContain('fsl-deferred-cards')
        ->toContain('--cols-md: repeat(2');
});

it('lets a deferred schema select a placeholder without evaluating its children', function () {
    $schema = Schema::make(app(LatestOrders::class))
        ->components(fn () => throw new LogicException('The schema should remain lazy.'))
        ->extraAttributes(['data-test' => 'kept'], merge: true);

    expect($schema->skeletonPlaceholder(SchemaPlaceholder::Infolist))->toBe($schema)
        ->and($schema->getExtraAttributes()['data-test'])->toBe('kept');

    expect($schema->getExtraAttributes())->not->toHaveKey('data-fsl-schema-default');

    if (method_exists($schema, 'lazyLoaded')) {
        $schema->lazyLoaded();

        expect($schema->getExtraAttributes()['data-fsl-schema-default'])->toBe('form');
    }

    expect($schema->getExtraAttributes()['data-fsl-schema'])->toBe('infolist')
        ->and(file_get_contents(__DIR__.'/../../resources/css/skeleton-loading.css'))
        ->toContain('[data-fsl-schema="infolist"]')
        ->toContain('[data-fsl-schema="list"]');
});

it('keeps the placeholder choice only when it comes after plain extra attributes', function () {
    $after = Schema::make(app(LatestOrders::class))
        ->extraAttributes(['data-test' => 'kept'])
        ->skeletonPlaceholder(SchemaPlaceholder::List);

    $before = Schema::make(app(LatestOrders::class))
        ->skeletonPlaceholder(SchemaPlaceholder::List)
        ->extraAttributes(['data-test' => 'kept']);

    expect($after->getExtraAttributes())->toMatchArray(['data-test' => 'kept', 'data-fsl-schema' => 'list'])
        ->and($before->getExtraAttributes())->not->toHaveKey('data-fsl-schema');
});
