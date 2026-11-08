<?php

declare(strict_types=1);

namespace MortalKiller\FilamentSkeletonLoading;

use Filament\Support\Assets\Asset;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class FilamentSkeletonLoadingServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('filament-skeleton-loading');

        if (is_file(__DIR__.'/../config/filament-skeleton-loading.php')) {
            $package->hasConfigFile();
        }

        if (is_file(__DIR__.'/../database/migrations/create_filament_skeleton_loading_table.php.stub')) {
            $package->hasMigration('create_filament_skeleton_loading_table');
        }

        if (is_dir(__DIR__.'/../resources/lang')) {
            $package->hasTranslations();
        }

        if (is_dir(__DIR__.'/../resources/views')) {
            $package->hasViews();
        }
    }

    public function packageBooted(): void
    {
        $assets = $this->getAssets();

        if ($assets !== []) {
            FilamentAsset::register($assets, 'mortalkiller/filament-skeleton-loading');
        }
    }

    /**
     * @return array<Asset>
     */
    private function getAssets(): array
    {
        $assets = [];
        $css = __DIR__.'/../resources/dist/index.css';
        $js = __DIR__.'/../resources/dist/index.js';

        if (is_file($css)) {
            $assets[] = Css::make('filament-skeleton-loading-styles', $css);
        }

        if (is_file($js)) {
            $assets[] = Js::make('filament-skeleton-loading-scripts', $js);
        }

        return $assets;
    }
}
