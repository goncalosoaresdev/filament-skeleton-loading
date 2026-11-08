import { test } from '@playwright/test'

// Refreshes the README and docs screenshots from the Workbench panel. Skipped unless asked:
//   CAPTURE_SCREENSHOTS=1 npx playwright test screenshots

test.skip(!process.env.CAPTURE_SCREENSHOTS, 'Set CAPTURE_SCREENSHOTS=1 to refresh the screenshots.')

test.use({ viewport: { width: 1440, height: 900 } })

for (const scheme of ['light', 'dark']) {
    test(`dashboard skeletons (${scheme})`, async ({ page }) => {
        await page.emulateMedia({ colorScheme: scheme, reducedMotion: 'reduce' })
        await page.goto('/admin')
        await page.locator('.fsl-skeleton--table').waitFor()

        await page.screenshot({ path: `docs/screenshots/dashboard-${scheme}.png` })
    })
}

test('page loader (light)', async ({ page }) => {
    await page.emulateMedia({ colorScheme: 'light', reducedMotion: 'reduce' })
    await page.goto('/admin/reports')
    await page.evaluate(() => document.getElementById('fsl-page-loader').classList.add('is-visible'))
    await page.waitForFunction(() => getComputedStyle(document.getElementById('fsl-page-loader')).opacity === '1')
    await page.waitForTimeout(500)

    await page.screenshot({ path: 'docs/screenshots/page-loader-light.png' })
})
