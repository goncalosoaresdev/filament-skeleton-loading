<?php

declare(strict_types=1);

namespace MortalKiller\FilamentSkeletonLoading\Tests\Unit;

use MortalKiller\FilamentSkeletonLoading\FilamentSkeletonLoadingServiceProvider;
use MortalKiller\FilamentSkeletonLoading\Tests\TestCase;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class PackageBootTest extends TestCase
{
    public function test_service_provider_boots(): void
    {
        self::assertTrue(app()->providerIsLoaded(FilamentSkeletonLoadingServiceProvider::class));
        self::assertTrue(is_subclass_of(FilamentSkeletonLoadingServiceProvider::class, PackageServiceProvider::class));
    }
}
