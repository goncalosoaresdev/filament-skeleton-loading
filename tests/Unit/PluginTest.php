<?php

declare(strict_types=1);

namespace MortalKiller\FilamentSkeletonLoading\Tests\Unit;

use MortalKiller\FilamentSkeletonLoading\FilamentSkeletonLoadingPlugin;
use MortalKiller\FilamentSkeletonLoading\Tests\TestCase;

final class PluginTest extends TestCase
{
    public function test_plugin_exposes_the_expected_filament_contract(): void
    {
        $plugin = FilamentSkeletonLoadingPlugin::make();

        self::assertSame('filament-skeleton-loading', $plugin->getId());
        self::assertInstanceOf(FilamentSkeletonLoadingPlugin::class, $plugin);
    }
}
