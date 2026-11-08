<?php

declare(strict_types=1);

namespace MortalKiller\FilamentSkeletonLoading;

use Filament\Contracts\Plugin;
use Filament\Panel;

final class FilamentSkeletonLoadingPlugin implements Plugin
{
    public function getId(): string
    {
        return 'filament-skeleton-loading';
    }

    public static function make(): static
    {
        return app(self::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(self::class)->getId());

        return $plugin;
    }

    public function register(Panel $panel): void
    {
        //
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
