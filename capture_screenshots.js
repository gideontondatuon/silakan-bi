import puppeteer from 'puppeteer-core';
import fs from 'fs';
import path from 'path';

const CHROME_PATH = 'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe';
const OUTPUT_DIR = 'D:\\Bank Indo\\silakan\\public\\images\\manual';

if (!fs.existsSync(OUTPUT_DIR)) {
    fs.mkdirSync(OUTPUT_DIR, { recursive: true });
}

async function capture() {
    console.log('Launching browser...');
    const browser = await puppeteer.launch({
        executablePath: CHROME_PATH,
        headless: 'new',
        defaultViewport: { width: 1366, height: 850, deviceScaleFactor: 1.5 },
        args: ['--no-sandbox', '--disable-setuid-sandbox', '--window-size=1366,850']
    });

    const page = await browser.newPage();

    async function takeScreenshot(filename, clipOrFull = false) {
        const dest = path.join(OUTPUT_DIR, filename);
        console.log(`Capturing: ${filename}`);
        await new Promise(r => setTimeout(r, 1200)); // wait for transitions/animations
        await page.screenshot({ path: dest, fullPage: clipOrFull });
        console.log(`Saved: ${dest}`);
    }

    try {
        // 1. Login Page
        console.log('Navigating to Login...');
        await page.goto('http://127.0.0.1:8000/login', { waitUntil: 'networkidle0' });
        await takeScreenshot('01_login_page.png');

        // 2. Login as User (uk_kpwbisulut)
        console.log('Logging in as User (uk_kpwbisulut)...');
        await page.type('#login_input', 'uk_kpwbisulut');
        await page.type('#password', 'password');
        await Promise.all([
            page.click('button[type="submit"]'),
            page.waitForNavigation({ waitUntil: 'networkidle0' })
        ]);

        // 3. User Dashboard
        console.log('On User Dashboard...');
        await takeScreenshot('02_dashboard_user.png');

        // 4. Form Pemesanan Baru
        console.log('Navigating to Pemesanan Create...');
        await page.goto('http://127.0.0.1:8000/pemesanan/create', { waitUntil: 'networkidle0' });
        await takeScreenshot('03_form_pemesanan.png');

        // 5. Select Room with layout (Tondano)
        console.log('Selecting room Tondano...');
        const roomSelect = await page.$('select[name="ruangan_id"]');
        if (roomSelect) {
            await page.select('select[name="ruangan_id"]', '1'); // Tondano ID 1
            await new Promise(r => setTimeout(r, 1000));
            await takeScreenshot('04_layout_filtering.png');
        }

        // 6. Select Room without layout (Bunaken)
        console.log('Selecting room Bunaken...');
        if (roomSelect) {
            await page.select('select[name="ruangan_id"]', '9'); // Bunaken ID 9
            await new Promise(r => setTimeout(r, 1000));
            await takeScreenshot('05_dropdown_no_layout.png');
        }

        // 7. Riwayat Pemesanan User
        console.log('Navigating to Riwayat Pemesanan...');
        await page.goto('http://127.0.0.1:8000/pemesanan', { waitUntil: 'networkidle0' });
        await takeScreenshot('06_riwayat_pemesanan.png');

        // 8. Detail Pemesanan User
        console.log('Navigating to Detail Pemesanan (ID 17)...');
        await page.goto('http://127.0.0.1:8000/pemesanan/17', { waitUntil: 'networkidle0' });
        await takeScreenshot('07_detail_pemesanan_user.png');

        // 9. Kalender Ruangan
        console.log('Navigating to Kalender Ruangan...');
        await page.goto('http://127.0.0.1:8000/kalender', { waitUntil: 'networkidle0' });
        await new Promise(r => setTimeout(r, 2000)); // wait for fullcalendar events to load
        await takeScreenshot('08_kalender_ruangan.png');

        // 10. Halaman Notifikasi
        console.log('Navigating to Notifikasi...');
        await page.goto('http://127.0.0.1:8000/notifications', { waitUntil: 'networkidle0' });
        await takeScreenshot('09_halaman_notifikasi.png');

        // 11. Profil User
        console.log('Navigating to Profil...');
        await page.goto('http://127.0.0.1:8000/profile', { waitUntil: 'networkidle0' });
        await takeScreenshot('10_profil_user.png');

        // 12. Logout User
        console.log('Logging out user...');
        const logoutBtn = await page.$('form[action*="logout"] button');
        if (logoutBtn) {
            await Promise.all([
                logoutBtn.click(),
                page.waitForNavigation({ waitUntil: 'networkidle0' })
            ]);
        } else {
            await page.goto('http://127.0.0.1:8000/login', { waitUntil: 'networkidle0' });
        }

        // 13. Login as Admin
        console.log('Logging in as Admin...');
        await page.goto('http://127.0.0.1:8000/login', { waitUntil: 'networkidle0' });
        await page.type('#login_input', 'admin');
        await page.type('#password', 'password');
        await Promise.all([
            page.click('button[type="submit"]'),
            page.waitForNavigation({ waitUntil: 'networkidle0' })
        ]);

        // 16. Admin Approval Review Detail
        console.log('Navigating to Approval Review (ID 19)...');
        await page.goto('http://127.0.0.1:8000/admin/approval/19', { waitUntil: 'networkidle0' });
        await takeScreenshot('13_approval_review.png');

        // 16b. Modal Tolak Pemesanan
        const rejectBtn = await page.$('button[onclick*="tolakModal"], button[data-bs-target*="tolak"], #btn-tolak, button.btn-danger, button:has-text("Tolak")');
        if (rejectBtn) {
            try {
                await rejectBtn.click();
                await new Promise(r => setTimeout(r, 600));
                await takeScreenshot('13b_modal_penolakan.png');
            } catch (e) {}
        }

        // 17. Live Monitoring Kegiatan Berlangsung
        console.log('Navigating to Kegiatan Berlangsung...');
        await page.goto('http://127.0.0.1:8000/admin/kegiatan-berlangsung', { waitUntil: 'networkidle0' });
        await takeScreenshot('14_live_monitoring.png');

        // 18. Master Data Ruangan
        console.log('Navigating to Master Ruangan...');
        await page.goto('http://127.0.0.1:8000/admin/ruangan', { waitUntil: 'networkidle0' });
        await takeScreenshot('15_master_ruangan.png');

        // 19. Form Ruangan Create (with layout checkboxes)
        console.log('Navigating to Ruangan Create...');
        await page.goto('http://127.0.0.1:8000/admin/ruangan/create', { waitUntil: 'networkidle0' });
        await takeScreenshot('16_form_ruangan_create.png');

        // 20. Master Data Layout
        console.log('Navigating to Master Layout...');
        await page.goto('http://127.0.0.1:8000/admin/layout', { waitUntil: 'networkidle0' });
        await takeScreenshot('17_master_layout.png');

        // 21. Master Hari Libur
        console.log('Navigating to Hari Libur...');
        await page.goto('http://127.0.0.1:8000/admin/hari-libur', { waitUntil: 'networkidle0' });
        await takeScreenshot('18_master_hari_libur.png');

        // 22. Master Data User
        console.log('Navigating to Master User...');
        await page.goto('http://127.0.0.1:8000/admin/users', { waitUntil: 'networkidle0' });
        await takeScreenshot('19_master_user.png');

        // 23. Laporan & Rekapitulasi
        console.log('Navigating to Laporan...');
        await page.goto('http://127.0.0.1:8000/admin/laporan', { waitUntil: 'networkidle0' });
        await takeScreenshot('20_laporan_filter.png');

        // 24. Laporan Cetak PDF (Official BI Letterhead)
        console.log('Navigating to Cetak PDF...');
        await page.goto('http://127.0.0.1:8000/admin/laporan/cetak', { waitUntil: 'networkidle0' });
        await takeScreenshot('21_laporan_cetak_pdf.png');

        // 25. Audit Log System
        console.log('Navigating to Audit Log...');
        await page.goto('http://127.0.0.1:8000/admin/audit-log', { waitUntil: 'networkidle0' });
        await takeScreenshot('22_audit_log.png');

        // 26. TV Display Kiosk Mode (Dark Mode)
        console.log('Navigating to TV Display...');
        await page.setViewport({ width: 1920, height: 1080, deviceScaleFactor: 1 });
        await page.goto('http://127.0.0.1:8000/display', { waitUntil: 'networkidle0' });
        await new Promise(r => setTimeout(r, 2000));
        await takeScreenshot('23_tv_display_kiosk.png');

        console.log('ALL SCREENSHOTS CAPTURED SUCCESSFULLY!');
    } catch (err) {
        console.error('Error during capture:', err);
    } finally {
        await browser.close();
    }
}

capture();
