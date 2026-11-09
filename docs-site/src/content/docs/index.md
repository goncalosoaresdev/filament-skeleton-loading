---
title: Filament Skeleton Loading
description: Skeleton loading states for Filament lazy widgets, lazy sections and deferred tables.
template: splash
editUrl: false
hero:
  tagline: Show the shape of what is coming while Filament loads, instead of a grey box or a spinner.
  actions:
    - text: Get started
      link: /filament-skeleton-loading/getting-started/installation/
      icon: right-arrow
    - text: API reference
      link: /filament-skeleton-loading/api/
      variant: minimal
    - text: Pedro Monteiro
      link: https://pedromonteiro.dev
      icon: external
      variant: minimal
---

Filament Skeleton Loading keeps Filament's own loading mechanisms (`placeholder()`, `deferLoading()` and the `loading-section` view) and only changes what is drawn while they wait: stat tiles, a chart or table rows shaped like the real content.

![Dashboard skeletons in light mode](/filament-skeleton-loading/screenshots/dashboard-light.png)

## Highlights

- Typed skeletons for stats, chart and table widgets with one trait.
- A generic skeleton for every other lazy widget, relation manager or Livewire component.
- Skeleton rows for deferred tables, the database notifications panel and lazy loaded sections.
- An opt-in full-screen page loader that only shows when the next page is slow.
- Plain CSS, light and dark mode, still for reduced motion, no theme build.
- Filament 4.15.2+ and 5.10.2+ on Laravel 12 and 13.

## Where to go next

- [Installation](/filament-skeleton-loading/getting-started/installation/)
- [Widgets and tables](/filament-skeleton-loading/guides/widgets-and-tables/)
- [Page loader](/filament-skeleton-loading/guides/page-loader/)
- [Configuration](/filament-skeleton-loading/guides/configuration/)
- [API reference](/filament-skeleton-loading/api/)
