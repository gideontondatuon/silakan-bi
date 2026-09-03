import puppeteer from 'puppeteer-core';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const CHROME = 'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe';
const OUT_DIR = path.join(__dirname, 'public', 'images', 'manual');

async function screenshot(browser, url, filename, height = 900) {
    const page = await browser.newPage();
    await page.setViewport({ width: 1440, height });
    await page.goto(url, { waitUntil: 'networkidle0', timeout: 30000 });
    await new Promise(r => setTimeout(r, 1500));
    const out = path.join(OUT_DIR, filename);
    await page.screenshot({ path: out, fullPage: true });
    console.log('Captured:', out);
    await page.close();
}

async function main() {
    const browser = await puppeteer.launch({
        executablePath: CHROME,
        headless: 'new',
        args: ['--no-sandbox','--disable-setuid-sandbox']
    });

    // 1. Login
    const page = await browser.newPage();
    await page.setViewport({ width: 1440, height: 900 });
    await page.goto('http://localhost:8000/login', { waitUntil: 'networkidle0' });
    await page.type('input[name="login_input"]', 'admin');
    await page.type('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.waitForNavigation({ waitUntil: 'networkidle0' });
    console.log('Logged in:', page.url());
    await page.close();

    // 2. Laporan Index
    await screenshot(browser, 'http://localhost:8000/admin/laporan', 'laporan_index_new.png', 1000);

    // 3. Laporan Cetak
    await screenshot(browser, 'http://localhost:8000/admin/laporan/cetak', 'laporan_cetak_new.png', 900);

    await browser.close();
    console.log('All screenshots done!');
}

main().catch(err => { console.error(err); process.exit(1); });
