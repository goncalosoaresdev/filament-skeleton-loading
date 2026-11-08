import { defineConfig } from '@playwright/test'

// PLAYWRIGHT_PORT moves the Workbench server off 8000 when another app already uses that port.
const port = process.env.PLAYWRIGHT_PORT || '8000'
const origin = `http://127.0.0.1:${port}`

export default defineConfig({
    testDir: './tests/Browser',
    timeout: 30000,
    expect: { timeout: 10000 },
    fullyParallel: false,
    workers: 1,
    reporter: [['list'], ['html', { open: 'never' }]],
    use: {
        baseURL: origin,
        launchOptions: process.env.PLAYWRIGHT_CHROMIUM_EXECUTABLE
            ? { executablePath: process.env.PLAYWRIGHT_CHROMIUM_EXECUTABLE }
            : {},
        screenshot: 'only-on-failure',
        trace: 'retain-on-failure',
    },
    projects: [{ name: 'chromium', use: { browserName: 'chromium' } }],
    webServer: {
        command: `php vendor/bin/testbench serve --host=127.0.0.1 --port=${port}`,
        url: `${origin}/admin`,
        reuseExistingServer: !process.env.CI,
        timeout: 120000,
    },
})
