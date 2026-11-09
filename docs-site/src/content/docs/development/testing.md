---
title: Testing
description: Run the package tests and quality checks.
---

## PHP

```bash
composer check   # Pint, Larastan (level 6) and Pest
composer test    # Pest only
```

Pest runs in random order and fails on warnings, risky tests and unexpected output. `tests/Disabled` runs the same package with `enabled` off.

## Browser

```bash
composer browser:prepare
npm install
npx playwright install chromium
npm run test:browser
```

Playwright starts the Workbench on port 8000. Set `PLAYWRIGHT_PORT` when that port is taken, and `PLAYWRIGHT_CHROMIUM_EXECUTABLE` to use a Chromium you already have.

## Screenshots

The README and documentation screenshots come from the Workbench:

```bash
PHP_CLI_SERVER_WORKERS=8 WORKBENCH_LATENCY_MS=8000 CAPTURE_SCREENSHOTS=1 npx playwright test screenshots
```

The spec writes each image to `docs/screenshots` (README) and `docs-site/public/screenshots` (documentation site).

## CI

CI runs the PHP tests against the lowest and latest dependency sets of Filament 4 and 5, Pint and Larastan, the browser tests on both Filament majors, the package standard check, the documentation build and Zizmor.
