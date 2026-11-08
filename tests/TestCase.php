<?php

declare(strict_types=1);

namespace MortalKiller\FilamentSkeletonLoading\Tests;

use Filament\Facades\Filament;
use Filament\FilamentServiceProvider;
use Filament\Support\SupportServiceProvider;
use Filament\Widgets\WidgetsServiceProvider;
use Livewire\LivewireServiceProvider;
use MortalKiller\FilamentSkeletonLoading\SkeletonLoadingServiceProvider;
use MortalKiller\FilamentSkeletonLoading\Tests\Fixtures\PlainPanelProvider;
use MortalKiller\FilamentSkeletonLoading\Tests\Fixtures\TestPanelProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    /** The panel each test runs in: `test` uses the plugin, `plain` does not. */
    protected string $panel = 'test';

    protected function getPackageProviders($app): array
    {
        return [
            LivewireServiceProvider::class,
            SupportServiceProvider::class,
            WidgetsServiceProvider::class,
            FilamentServiceProvider::class,
            SkeletonLoadingServiceProvider::class,
            TestPanelProvider::class,
            PlainPanelProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app): void
    {
        $app['config']->set('app.key', 'base64:'.base64_encode(str_repeat('x', 32)));
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', ['driver' => 'sqlite', 'database' => ':memory:', 'prefix' => '']);
    }

    protected function setUp(): void
    {
        parent::setUp();

        Filament::setCurrentPanel(Filament::getPanel($this->panel));
        Filament::bootCurrentPanel();
    }
}
