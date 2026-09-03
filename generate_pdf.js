import puppeteer from 'puppeteer-core';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const CHROME_PATH = 'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe';
const HTML_FILE = path.join(__dirname, 'manual_book.html');
const PDF_OUTPUT = path.join(__dirname, 'Manual_Book_SILAKAN_v1.0.pdf');

async function main() {
    console.log('Generating PDF from manual_book.html...');
    const browser = await puppeteer.launch({
        executablePath: CHROME_PATH,
        headless: 'new',
        args: ['--no-sandbox', '--disable-setuid-sandbox', '--allow-file-access-from-files']
    });

    const page = await browser.newPage();
    const fileUrl = 'file:///' + HTML_FILE.replace(/\\/g, '/');
    console.log('Loading URL:', fileUrl);

    await page.goto(fileUrl, { waitUntil: 'networkidle0' });
    // Wait an extra 2 seconds for fonts, images, and styles to settle
    await new Promise(r => setTimeout(r, 2500));

    console.log('Printing to PDF:', PDF_OUTPUT);
    await page.pdf({
        path: PDF_OUTPUT,
        format: 'A4',
        printBackground: true,
        preferCSSPageSize: true,
        displayHeaderFooter: false
    });

    await browser.close();
    console.log('PDF GENERATED SUCCESSFULLY AT:', PDF_OUTPUT);
}

main().catch(err => {
    console.error('Error generating PDF:', err);
    process.exit(1);
});
