---
title: API Reference
description: Public API of Filament Skeleton Loading.
---

Everything below is public API and stays compatible within a package major.

| API | Purpose |
| --- | --- |
| [`SkeletonLoadingPlugin`](/filament-skeleton-loading/api/skeleton-loading-plugin/) | Panel plugin: shimmer, page loader and table deferral. |
| [`HasSkeletonPlaceholder`](/filament-skeleton-loading/api/has-skeleton-placeholder/) | Trait for lazy widgets and relation managers: a skeleton shaped like them. |
| [Config, views and translations](/filament-skeleton-loading/api/resources/) | The `filament-skeleton-loading` config, view and translation namespaces, and the CSS hooks. |

The service provider, `MortalKiller\FilamentSkeletonLoading\SkeletonLoadingServiceProvider`, is discovered by Laravel and needs no registration.
