<?php

declare(strict_types=1);

namespace MortalKiller\FilamentSkeletonLoading;

use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Support\Facades\View;
use Illuminate\View\FileViewFinder;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class SkeletonLoadingServiceProvider extends PackageServiceProvider
{
    /** Filament's own views, for panels that do not use the plugin. */
    public const FILAMENT_VIEWS = 'filament-skeleton-loading-filament';

    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-skeleton-loading')
            ->hasConfigFile()
            ->hasTranslations()
            ->hasViews();
    }

    public function packageBooted(): void
    {
        if (! SkeletonLoadingPlugin::isEnabled()) {
            return;
        }

        // Filament's lazy widgets and sections render `filament::components.loading-section`, a grey
        // pulsing box. Putting this package's copy first lets panels with the plugin draw a skeleton;
        // the copy hands every other panel back to Filament's own view.
        $this->app->booted(function (): void {
            $finder = View::getFinder();
            $overrides = realpath(__DIR__.'/../resources/views/overrides');
            $filamentPaths = $finder instanceof FileViewFinder ? ($finder->getHints()['filament'] ?? []) : [];

            View::addNamespace(self::FILAMENT_VIEWS, array_values(array_filter(
                $filamentPaths,
                static fn (string $path): bool => realpath($path) !== $overrides,
            )));
        });

        View::prependNamespace('filament', __DIR__.'/../resources/views/overrides');

        // Loaded by the plugin, only in the panels that use it.
        FilamentAsset::register([
            Css::make('skeleton-loading', __DIR__.'/../resources/css/skeleton-loading.css')->loadedOnRequest(),
        ], package: SkeletonLoadingPlugin::PACKAGE);
    }
}
