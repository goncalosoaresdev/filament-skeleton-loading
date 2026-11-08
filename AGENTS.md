# Agent instructions

This repository follows MortalKiller Filament Package Standard v2.

Canonical standard: https://github.com/mortalkiller/filament-package-standard/blob/2.x/docs/package-standard.md
Installed maintainer skill: `vendor/mortalkiller/filament-package-standard/resources/boost/skills/developing-filament-packages/SKILL.md`
Canonical skill fallback: https://github.com/mortalkiller/filament-package-standard/blob/2.x/resources/boost/skills/developing-filament-packages/SKILL.md

After `composer install`, read and use the installed `developing-filament-packages` skill. If dependencies are not installed, use the canonical skill fallback. Read `docs/development-flow.md` and the canonical standard. Work on temporary branches targeting the affected major. Publish immutable tags on verified major commits; do not introduce a stable-promotion branch.

Before completion, run required CI-equivalent checks and audit public content for private customer, consumer, infrastructure and credential information. Distinguish code/build verification from an actual release or live documentation deployment.
