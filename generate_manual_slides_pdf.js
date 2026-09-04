import puppeteer from 'puppeteer-core';
import path from 'path';
import { fileURLToPath } from 'url';
import fs from 'fs';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const CHROME_PATH = 'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe';
const HTML_FILE = path.join(__dirname, 'manual_book_slides.html');
const PDF_OUTPUT = path.join(__dirname, 'Manual_Book_Slide_SILAKAN.pdf');
const PDF_OUTPUT_PUB = path.join(__dirname, 'public', 'Manual_Book_Slide_SILAKAN.pdf');

async function main() {
    console.log('Generating Manual_Book_Slide_SILAKAN.pdf...');
    const browser = await puppeteer.launch({
        executablePath: CHROME_PATH,
        headless: 'new',
        args: ['--no-sandbox', '--disable-setuid-sandbox', '--allow-file-access-from-files']
    });

    const page = await browser.newPage();
    await page.setViewport({ width: 1920, height: 1080, deviceScaleFactor: 2 });

    const fileUrl = 'file:///' + HTML_FILE.replace(/\\/g, '/');
    console.log('Loading URL:', fileUrl);

    await page.goto(fileUrl, { waitUntil: 'networkidle0' });
    // Wait for fonts & images to render
    await new Promise(r => setTimeout(r, 2000));

    console.log('Printing to PDF:', PDF_OUTPUT);
    await page.pdf({
        path: PDF_OUTPUT,
        format: 'A4',
        landscape: true,
        printBackground: true,
        preferCSSPageSize: true,
        displayHeaderFooter: false
    });

    // Also copy to public directory
    fs.copyFileSync(PDF_OUTPUT, PDF_OUTPUT_PUB);
    console.log('Copied to public:', PDF_OUTPUT_PUB);

    await browser.close();
    console.log('PDF GENERATED SUCCESSFULLY!');
}

main().catch(err => {
    console.error('Error generating slide PDF:', err);
    process.exit(1);
});
