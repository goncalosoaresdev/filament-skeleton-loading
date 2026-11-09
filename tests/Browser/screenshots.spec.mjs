import { copyFileSync, mkdirSync } from 'node:fs'
import { test } from '@playwright/test'

// Refreshes the README and docs screenshots from the Workbench panels. Skipped unless asked:
//   CAPTURE_SCREENSHOTS=1 npx playwright test screenshots
// Each image is written to docs/screenshots (README) and docs-site/public/screenshots (docs site).

test.skip(!process.env.CAPTURE_SCREENSHOTS, 'Set CAPTURE_SCREENSHOTS=1 to refresh the screenshots.')

test.use({ viewport: { width: 1440, height: 900 } })

const save = async (target, name, options = {}) => {
    const path = `docs/screenshots/${name}.png`

    mkdirSync('docs-site/public/screenshots', { recursive: true })
    // A clip may reach below the viewport, so it is taken from the full page.
    await target.screenshot({ path, ...(options.clip ? { fullPage: true } : {}), ...options })
    copyFileSync(path, `docs-site/public/screenshots/${name}.png`)
}

const open = async (page, url, scheme = 'light') => {
    await page.emulateMedia({ colorScheme: scheme, reducedMotion: 'reduce' })
    await page.goto(url)
}

// The smallest area holding every element of the locator, with some room around it.
const around = async (locator, padding = 16) => {
    const boxes = await Promise.all((await locator.all()).map((element) => element.boundingBox()))
    const left = Math.min(...boxes.map((box) => box.x)) - padding
    const top = Math.min(...boxes.map((box) => box.y)) - padding

    return {
        x: Math.max(0, left),
        y: Math.max(0, top),
        width: Math.max(...boxes.map((box) => box.x + box.width)) + padding - Math.max(0, left),
        height: Math.max(...boxes.map((box) => box.y + box.height)) + padding - Math.max(0, top),
    }
}

for (const scheme of ['light', 'dark']) {
    test(`dashboard skeletons (${scheme})`, async ({ page }) => {
        await open(page, '/admin', scheme)
        await page.locator('.fsl-skeleton--table').waitFor()

        await save(page, `dashboard-${scheme}`)
    })

    test(`page loader (${scheme})`, async ({ page }) => {
        await open(page, '/admin/reports', scheme)
        await page.evaluate(() => document.getElementById('fsl-page-loader').classList.add('is-visible'))
        await page.waitForFunction(() => getComputedStyle(document.getElementById('fsl-page-loader')).opacity === '1')
        await page.waitForTimeout(500)

        await save(page, `page-loader-${scheme}`)
    })
}

test('dashboard without the plugin (light)', async ({ page }) => {
    await open(page, '/plain')
    await page.locator('.fi-loading-section').first().waitFor()

    await save(page, 'without-plugin-light')
})

test('typed widget skeletons (light)', async ({ page }) => {
    await open(page, '/admin')
    await page.locator('.fsl-skeleton--table').waitFor()

    await save(page, 'widget-stats-light', { clip: await around(page.locator('.fsl-skeleton--stats')) })
    await save(page, 'widget-charts-light', { clip: await around(page.locator('.fsl-skeleton--chart')) })
    await save(page, 'widget-table-light', { clip: await around(page.locator('.fsl-skeleton--table')) })
})

test('deferred table (light)', async ({ page }) => {
    await open(page, '/admin/orders')
    const loading = page.locator('.fi-ta-table-loading-ctn')
    await loading.waitFor()
    await page.waitForTimeout(300)

    await save(page, 'deferred-table-light', { clip: await around(page.locator('.fi-ta').filter({ has: loading })) })
})
