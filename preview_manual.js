import puppeteer from 'puppeteer-core';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const CHROME = 'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe';
const HTML_PATH = path.join(__dirname, 'manual_book.html');
const OUT_DIR = path.join(__dirname, 'public', 'images', 'manual');

async function main() {
    const browser = await puppeteer.launch({
        executablePath: CHROME,
        headless: 'new',
        args: ['--no-sandbox','--disable-setuid-sandbox','--allow-file-access-from-files']
    });

    const page = await browser.newPage();
    await page.setViewport({ width: 900, height: 1200 });

    const fileUrl = 'file:///' + HTML_PATH.replace(/\\/g, '/');
    await page.goto(fileUrl, { waitUntil: 'networkidle0', timeout: 60000 });
    await new Promise(r => setTimeout(r, 3000));

    // Full page preview (tall clip)
    await page.screenshot({
        path: path.join(OUT_DIR, 'mb_preview_full.png'),
        clip: { x: 0, y: 0, width: 900, height: 5000 }
    });

    // Cover only (first 1200px)
    await page.screenshot({
        path: path.join(OUT_DIR, 'mb_cover.png'),
        clip: { x: 0, y: 24, width: 900, height: 1130 }
    });

    // TOC (next page)
    await page.screenshot({
        path: path.join(OUT_DIR, 'mb_toc.png'),
        clip: { x: 0, y: 1200, width: 900, height: 1130 }
    });

    console.log('Screenshots done!');
    await browser.close();
}

main().catch(e => { console.error(e); process.exit(1); });
