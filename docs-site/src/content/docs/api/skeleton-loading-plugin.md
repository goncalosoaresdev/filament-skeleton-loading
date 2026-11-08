---
title: SkeletonLoadingPlugin
description: Panel-level configuration for Filament Skeleton Loading.
---

`MortalKiller\FilamentSkeletonLoading\SkeletonLoadingPlugin` registers the package on a Filament panel.

```php
use MortalKiller\FilamentSkeletonLoading\SkeletonLoadingPlugin;

$panel->plugin(SkeletonLoadingPlugin::make());
```

Register it on each panel that should use the skeletons, the page loader or table deferral. Panels without it keep Filament's own loading states: the stylesheet is not loaded there, and lazy widgets and sections draw Filament's own placeholder. The global `enabled` setting turns everything off, the plugin included.

## Constants

| Constant | Value |
| --- | --- |
| `ID` | `mortalkiller-skeleton-loading`, the plugin id on the panel. |
| `PACKAGE` | `mortalkiller/filament-skeleton-loading`, the Filament asset package name. |

## Static methods

| Method | Description |
| --- | --- |
| `make(): self` | Resolve a plugin instance from the container. |
| `current(): ?self` | The plugin registered on the current panel, or `null`. |
| `isEnabled(): bool` | The global `filament-skeleton-loading.enabled` setting. |

## Configuration methods

All of them return the plugin, so they chain.

| Method | Description |
| --- | --- |
| `shimmer(bool $condition = true): self` | Light sweeping across the skeletons (on by default). Always still for reduced motion. |
| `pageLoader(bool $condition = true): self` | Full-screen loader between pages (off by default). |
| `pageLoaderSymbol(string\|Htmlable\|Closure $symbol): self` | Brand symbol of the page loader, rendered as trusted HTML. A closure may return a string, an `Htmlable` or a view. |
| `pageLoaderDelay(int $milliseconds): self` | How long a page may take before the loader shows (300 by default, never below 0). |
| `pageLoaderLabel(string\|Closure $label): self` | Text under the symbol; the translated "Loading…" when not set. |
| `deferTables(bool\|Closure $condition = true): self` | Call `deferLoading()` on the panel's tables. A `Closure(Table): bool` decides per table. Not called: the `defer_tables` setting decides. |

## Read methods

| Method | Description |
| --- | --- |
| `getId(): string` | The plugin id (`ID`). |
| `hasShimmer(): bool` | Whether the shimmer is on. |
| `hasPageLoader(): bool` | Whether the page loader is on. |
| `renderPageLoader(): string` | The page loader markup, as injected at the end of the panel's `<body>`. |

## Lifecycle

- `register(Panel $panel)` loads the stylesheet through the panel's `STYLES_AFTER` render hook, adds the page loader to its `BODY_END` render hook when it is on, and a style that hides the shimmer when `shimmer(false)` is set.
- `boot(Panel $panel)` configures every table built while that panel is current, so `deferTables()` and the `defer_tables` setting apply only to the panels that use the plugin.
