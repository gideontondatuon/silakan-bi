import puppeteer from 'puppeteer-core';

async function main() {
    const browser = await puppeteer.launch({
        executablePath: 'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe',
        headless: 'new',
        defaultViewport: { width: 1366, height: 850, deviceScaleFactor: 1.5 }
    });
    const page = await browser.newPage();
    await page.goto('http://127.0.0.1:8000/login', { waitUntil: 'networkidle0' });
    await page.type('#login_input', 'admin');
    await page.type('#password', 'password');
    await Promise.all([
        page.click('button[type="submit"]'),
        page.waitForNavigation({ waitUntil: 'networkidle0' })
    ]);
    await page.goto('http://127.0.0.1:8000/admin/approval/19', { waitUntil: 'networkidle0' });
    await page.click('#btn-trigger-reject');
    await new Promise(r => setTimeout(r, 800));
    await page.screenshot({ path: 'D:\\Bank Indo\\silakan\\public\\images\\manual\\13b_modal_penolakan.png' });
    await browser.close();
    console.log('Modal penolakan captured successfully!');
}

main();
