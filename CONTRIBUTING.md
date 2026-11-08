# Contributing

Read [Development and release flow](docs/development-flow.md) and the [canonical package standard](https://github.com/mortalkiller/filament-package-standard/blob/2.x/docs/package-standard.md).

After `composer install`, the maintainer skill is available at `vendor/mortalkiller/filament-package-standard/resources/boost/skills/developing-filament-packages/SKILL.md`.

Create a focused temporary branch from the affected major and open a PR to that same major. Include an issue and acceptance criteria for meaningful changes, preserve backwards compatibility, and update tests and source-derived documentation. Squash temporary branches after review and successful CI.

Run the repository's Composer validation, tests, Pint, tracked syntax, compatibility and documentation checks, plus static analysis and JavaScript/browser checks where configured. Do not publish customer data, private applications, infrastructure or secrets in examples.

Releases are immutable `vX.Y.Z` tags on verified commits in `X.x`. Stable GitHub Releases publish documentation from the tag; branch pushes do not. Use [SECURITY.md](SECURITY.md) for private vulnerability reporting.
