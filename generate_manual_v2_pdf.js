import puppeteer from 'puppeteer-core';
import path from 'path';
import { fileURLToPath } from 'url';
import fs from 'fs';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const CHROME = 'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe';
const HTML_PATH = path.join(__dirname, 'manual_book_v2.html');
const OUT_PDF = path.join(__dirname, 'Manual_Book_SILAKAN_v2.0.pdf');
const OUT_PDF_PUB = path.join(__dirname, 'public', 'Manual_Book_SILAKAN_v2.0.pdf');

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
    // Wait for fonts, stylesheets, and images to settle
    await new Promise(r => setTimeout(r, 4000));

    console.log('Generating PDF...');
    await page.pdf({
        path: OUT_PDF,
        format: 'A4',
        printBackground: true,
        preferCSSPageSize: true,
        displayHeaderFooter: false,
    });

    // Also output to public
    await page.pdf({
        path: OUT_PDF_PUB,
        format: 'A4',
        printBackground: true,
        preferCSSPageSize: true,
        displayHeaderFooter: false,
    });

    await browser.close();

    const size = fs.statSync(OUT_PDF).size;
    console.log('PDF saved to:', OUT_PDF);
    console.log('PDF saved to public:', OUT_PDF_PUB);
    console.log('PDF size:', (size / 1024 / 1024).toFixed(2), 'MB');
    console.log('GENERATION COMPLETE!');
}

main().catch(e => {
    console.error('Error generating PDF:', e);
    process.exit(1);
});
