import puppeteer from 'puppeteer-core';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const CHROME_PATH = 'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe';
const HTML_FILE = path.join(__dirname, 'panduan_ringkas.html');
const PDF_OUTPUT = path.join(__dirname, 'Panduan_Ringkas_SILAKAN.pdf');
const PUBLIC_PDF_OUTPUT = path.join(__dirname, 'public', 'Panduan_Ringkas_SILAKAN.pdf');

async function main() {
    console.log('Generating PDF from panduan_ringkas.html...');
    const browser = await puppeteer.launch({
        executablePath: CHROME_PATH,
        headless: 'new',
        args: ['--no-sandbox', '--disable-setuid-sandbox', '--allow-file-access-from-files']
    });

    const page = await browser.newPage();
    const fileUrl = 'file:///' + HTML_FILE.replace(/\\/g, '/');
    console.log('Loading URL:', fileUrl);

    await page.goto(fileUrl, { waitUntil: 'networkidle0' });
    await new Promise(r => setTimeout(r, 2000));

    console.log('Printing to PDF:', PDF_OUTPUT);
    await page.pdf({
        path: PDF_OUTPUT,
        format: 'A4',
        printBackground: true,
        preferCSSPageSize: true,
        displayHeaderFooter: false
    });

    // Copy to public directory
    await page.pdf({
        path: PUBLIC_PDF_OUTPUT,
        format: 'A4',
        printBackground: true,
        preferCSSPageSize: true,
        displayHeaderFooter: false
    });

    await browser.close();
    console.log('PDF GENERATED SUCCESSFULLY AT:', PDF_OUTPUT);
    console.log('PUBLIC PDF GENERATED AT:', PUBLIC_PDF_OUTPUT);
}

main().catch(err => {
    console.error('Error generating PDF:', err);
    process.exit(1);
});
