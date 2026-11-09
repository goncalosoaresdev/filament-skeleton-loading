import { expect, test } from '@playwright/test'

// The Workbench widgets and the Reports page wait 1.5 s or more (WORKBENCH_LATENCY_MS), so the
// skeletons and the page loader stay on screen long enough to be asserted.

const widgetsLoaded = async (page) => {
    await expect(page.locator('.fsl-skeleton')).toHaveCount(0, { timeout: 20000 })
}

test('lazy widgets show skeletons shaped like them, then the real widgets', async ({ page }) => {
    await page.goto('/admin')

    await expect(page.locator('.fsl-skeleton--stats')).toBeVisible()
    await expect(page.locator('.fsl-skeleton--stats .fsl-stat')).toHaveCount(4)
    await expect(page.locator('.fsl-skeleton--chart')).toHaveCount(2)
    await expect(page.locator('.fsl-skeleton--table')).toBeVisible()

    await widgetsLoaded(page)

    await expect(page.getByText('Returning customers')).toBeVisible()
    await expect(page.getByText('ORD-1001')).toBeVisible()
})

test('chart skeletons follow the chart type and keep the real heading', async ({ page }) => {
    await page.goto('/admin')

    const bars = page.locator('.fsl-skeleton--chart').filter({ has: page.locator('.fsl-chart-bars') })
    const ring = page.locator('.fsl-skeleton--chart').filter({ has: page.locator('.fsl-chart-ring') })

    await expect(bars).toContainText('Revenue per month')
    await expect(ring).toContainText('Orders per channel')
})

test('skeletons are busy status regions with hidden shapes', async ({ page }) => {
    await page.goto('/admin')

    const skeleton = page.locator('.fsl-skeleton--stats')

    await expect(skeleton).toHaveAttribute('role', 'status')
    await expect(skeleton).toHaveAttribute('aria-busy', 'true')
    await expect(skeleton.locator('.fsl-grid')).toHaveAttribute('aria-hidden', 'true')
})

test('the shimmer stands still for reduced motion', async ({ page }) => {
    await page.emulateMedia({ reducedMotion: 'reduce' })
    await page.goto('/admin')

    const display = await page.locator('.fsl-bone').first().evaluate(
        (bone) => getComputedStyle(bone, '::after').display,
    )

    expect(display).toBe('none')
})

test('the page loader stands still for reduced motion', async ({ page }) => {
    await page.emulateMedia({ reducedMotion: 'reduce' })
    await page.goto('/admin/reports')

    const animations = await page.locator('#fsl-page-loader .fsl-loader-piece, #fsl-page-loader .fsl-loader-label').evaluateAll(
        (elements) => elements.map((element) => getComputedStyle(element).animationName),
    )

    expect(animations.length).toBeGreaterThan(1)
    expect(new Set(animations)).toEqual(new Set(['none']))
})

test('the page loader covers a slow page change', async ({ page }) => {
    await page.goto('/admin')
    await widgetsLoaded(page)

    const loader = page.locator('#fsl-page-loader')
    await expect(loader).toBeHidden()

    // The click resolves only once the slow page arrives, so watch the old page while it waits.
    const shown = page.waitForFunction(() => {
        const element = document.getElementById('fsl-page-loader')

        return element?.getAttribute('aria-hidden') === 'false' && getComputedStyle(element).visibility === 'visible'
    })

    await Promise.all([
        shown,
        page.locator('.fi-sidebar').getByRole('link', { name: 'Reports' }).click(),
    ])

    await page.waitForURL('**/admin/reports')
    await expect(page.getByText('The monthly reports are ready.')).toBeVisible()
    await expect(page.locator('#fsl-page-loader')).toBeHidden()
})

test('the page loader stays away from Livewire actions', async ({ page }) => {
    await page.goto('/admin/reports')

    const request = page.waitForResponse(
        (response) => response.url().includes('/livewire') && response.request().method() === 'POST',
    )
    await page.getByRole('button', { name: 'Refresh' }).click()
    await request

    await expect(page.locator('#fsl-page-loader')).not.toHaveClass(/is-visible/)
    await expect(page.locator('#fsl-page-loader')).toBeHidden()
})

for (const dismiss of ['click', 'Escape']) {
    test(`the page loader never traps the user when the page stays (${dismiss})`, async ({ page }) => {
        await page.goto('/admin/reports')

        // Like Filament's unsaved changes alert: the user cancels the "leave page?" prompt.
        await page.evaluate(() => window.addEventListener('beforeunload', (event) => {
            event.preventDefault()
            event.returnValue = true
        }))
        page.on('dialog', (dialog) => dialog.dismiss())

        const loader = page.locator('#fsl-page-loader')

        await page.locator('.fi-sidebar').getByRole('link', { name: 'Dashboard' }).click({ noWaitAfter: true })
        await expect(loader).toBeVisible()
        expect(new URL(page.url()).pathname).toBe('/admin/reports')

        if (dismiss === 'click') {
            await loader.click()
        } else {
            await page.keyboard.press('Escape')
        }

        // Well before the loader's own 10 s safety timeout.
        await expect(loader).toBeHidden({ timeout: 2000 })
        await page.getByRole('button', { name: 'Refresh' }).click()
    })
}

test('page loader keeps a single set of listeners after SPA navigation', async ({ page }) => {
    await page.addInitScript(() => {
        const add = document.addEventListener.bind(document)
        window.pageLoaderSubmitListeners = 0

        document.addEventListener = (type, listener, ...options) => {
            if (type === 'submit' && String(listener).includes('schedule()')) {
                window.pageLoaderSubmitListeners++
            }

            return add(type, listener, ...options)
        }
    })

    await page.goto('/admin/reports')
    await expect(page.locator('script[src*="/page-loader.js"]')).toHaveCount(1)
    await expect.poll(() => page.evaluate(() => window.pageLoaderSubmitListeners)).toBe(1)

    await page.evaluate(() => window.Livewire.navigate('/admin/orders'))
    await expect(page).toHaveURL(/\/admin\/orders$/)
    await expect(page.getByText('ORD-1001', { exact: true })).toBeVisible()
    await page.evaluate(() => window.Livewire.navigate('/admin/reports'))
    await expect(page).toHaveURL(/\/admin\/reports$/)
    await expect(page.getByText('The monthly reports are ready.')).toBeVisible()
    expect(await page.evaluate(() => window.pageLoaderSubmitListeners)).toBe(1)
})

test('page loader respects a submit button that opens another tab', async ({ page }) => {
    await page.goto('/admin/reports')

    await page.clock.install()
    await page.evaluate(() => {
        const form = document.createElement('form')
        const button = document.createElement('button')
        button.type = 'submit'
        button.formTarget = '_blank'
        form.append(button)
        document.body.append(form)

        // Dispatch only the event so the listener is tested without actually opening a tab.
        form.dispatchEvent(new SubmitEvent('submit', { bubbles: true, submitter: button }))
    })

    await page.clock.fastForward(500)
    await expect(page.locator('#fsl-page-loader')).toHaveAttribute('aria-hidden', 'true')
})
