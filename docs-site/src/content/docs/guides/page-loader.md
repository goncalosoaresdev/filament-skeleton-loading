---
title: Page loader
description: An opt-in full-screen loader between pages of a panel, shown only when the next page is slow.
---

The page loader is off by default. Turn it on per panel:

```php
SkeletonLoadingPlugin::make()
    ->pageLoader();
```

| Light | Dark |
| --- | --- |
| ![Page loader with the default symbol in light mode](/filament-skeleton-loading/screenshots/page-loader-light.png) | ![Page loader with the default symbol in dark mode](/filament-skeleton-loading/screenshots/page-loader-dark.png) |

## When it shows

- Clicking a link to another page of the same origin, or submitting a plain form such as logout.
- Only when that page takes longer than the delay (300 ms by default), so fast pages never flash.

It never shows for:

- Livewire requests (actions, filters, polling, lazy loading), because Livewire forms prevent the default submit;
- links with `wire:navigate`, `download`, a target other than `_self`, or a modifier key;
- links to the current page.

The browser may stay on the page after all: the user cancels a "leave page?" prompt (Filament's unsaved changes alert, for example), or the link answers with a file download. A click on the loader or Escape then hides it. It also hides itself after 10 seconds, and when the browser restores the page from the back/forward cache.

Give download links the `download` attribute, so they never show the loader.

## Options

```php
SkeletonLoadingPlugin::make()
    ->pageLoader()                                           // ->pageLoader(false) turns it off
    ->pageLoaderSymbol(fn () => view('brand.loader-symbol')) // optional; three dots otherwise
    ->pageLoaderDelay(300)                                   // milliseconds before it shows
    ->pageLoaderLabel('Loading…');                           // optional; translated "Loading…" otherwise
```

## Your brand symbol

The symbol is any markup: a string, an `Htmlable`, a view or a closure returning one of those. It is rendered as trusted HTML, so pass markup you control, never user input.

Give the elements that should come in one after the other the class `fsl-loader-piece`. Up to six pieces are animated in document order. They take the panel's primary colour, white in dark mode:

```blade
{{-- resources/views/brand/loader-symbol.blade.php --}}
<svg viewBox="0 0 120 96" width="64" height="52" fill="currentColor">
    <rect class="fsl-loader-piece" x="0" y="0" width="120" height="20" rx="10"/>
    <rect class="fsl-loader-piece" x="0" y="38" width="88" height="20" rx="10"/>
    <rect class="fsl-loader-piece" x="0" y="76" width="56" height="20" rx="10"/>
</svg>
```

## Accessibility

The loader is a `role="status"` live region. Its label is read by screen readers and is translated for `en`, `pt`, `pt_PT` and `pt_BR`; publish the translations to add a language. The symbol is `aria-hidden`, and all of its motion stops for users who prefer reduced motion.

After updating the package, run `php artisan filament:assets` to publish the page loader script. The script is loaded as an external asset and uses the application's Vite CSP nonce when configured. The asset origin must be permitted by the application's policy.
