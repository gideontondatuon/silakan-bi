import puppeteer from 'puppeteer-core';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const CHROME = 'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe';
const HTML_PATH = path.join(__dirname, 'manual_book.html');
const OUT_PDF = path.join(__dirname, 'Manual_Book_SILAKAN_v1.0.pdf');
const OUT_PDF_PUB = path.join(__dirname, 'public', 'Manual_Book_SILAKAN_v1.0.pdf');

async function main() {
    console.log('Launching Chromium...');
    const browser = await puppeteer.launch({
        executablePath: CHROME,
        headless: 'new',
        args: ['--no-sandbox', '--disable-setuid-sandbox', '--allow-file-access-from-files']
    });

    const page = await browser.newPage();
    const fileUrl = 'file:///' + HTML_PATH.replace(/\\/g, '/');
    console.log('Loading:', fileUrl);

    await page.goto(fileUrl, { waitUntil: 'networkidle0', timeout: 60000 });
    // Wait for fonts and icons to load
    await new Promise(r => setTimeout(r, 3500));

    console.log('Generating PDF...');
    await page.pdf({
        path: OUT_PDF,
        format: 'A4',
        printBackground: true,
        preferCSSPageSize: true,
        displayHeaderFooter: false,
    });

    // Copy to public
    await page.pdf({
        path: OUT_PDF_PUB,
        format: 'A4',
        printBackground: true,
        preferCSSPageSize: true,
        displayHeaderFooter: false,
    });

    await browser.close();

    const fs = await import('fs');
    const size = fs.statSync(OUT_PDF).size;
    console.log('PDF saved:', OUT_PDF);
    console.log('PDF size:', (size / 1024 / 1024).toFixed(2), 'MB');
    console.log('DONE!');
}

main().catch(e => { console.error(e); process.exit(1); });
