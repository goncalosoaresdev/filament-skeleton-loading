<?php

declare(strict_types=1);

namespace MortalKiller\FilamentSkeletonLoading\Tests;

use MortalKiller\FilamentSkeletonLoading\FilamentSkeletonLoadingServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            FilamentSkeletonLoadingServiceProvider::class,
        ];
    }
}
