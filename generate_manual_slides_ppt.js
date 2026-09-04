import pptxgen from 'pptxgenjs';
import path from 'path';
import { fileURLToPath } from 'url';
import fs from 'fs';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const OUT_PPTX = path.join(__dirname, 'Manual_Book_Slide_SILAKAN.pptx');
const OUT_PPTX_PUB = path.join(__dirname, 'public', 'Manual_Book_Slide_SILAKAN.pptx');

const LOGO_PATH = path.join(__dirname, 'public', 'images', 'logo-bi4.png');
const IMG_LOGIN = path.join(__dirname, 'public', 'images', 'manual', '01_login_page.png');
const IMG_DASHBOARD_USER = path.join(__dirname, 'public', 'images', 'manual', '02_dashboard_user.png');
const IMG_FORM = path.join(__dirname, 'public', 'images', 'manual', '03_form_pemesanan.png');
const IMG_POPUP_5MB = path.join(__dirname, 'public', 'images', 'manual', '03b_popup_file_5mb.png');
const IMG_RIWAYAT = path.join(__dirname, 'public', 'images', 'manual', '06_riwayat_pemesanan.png');
const IMG_DETAIL_USER = path.join(__dirname, 'public', 'images', 'manual', '07_detail_pemesanan_user.png');
const IMG_KALENDER = path.join(__dirname, 'public', 'images', 'manual', '08_kalender_ruangan.png');
const IMG_DASHBOARD_ADMIN = path.join(__dirname, 'public', 'images', 'manual', '14_live_monitoring.png');
const IMG_APPROVAL = path.join(__dirname, 'public', 'images', 'manual', '12_approval_index.png');
const IMG_PENOLAKAN = path.join(__dirname, 'public', 'images', 'manual', '13b_modal_penolakan.png');
const IMG_MASTER_RUANGAN = path.join(__dirname, 'public', 'images', 'manual', '15_master_ruangan.png');
const IMG_LAPORAN = path.join(__dirname, 'public', 'images', 'manual', '21_laporan_cetak_pdf.png');
const IMG_TV_DISPLAY = path.join(__dirname, 'public', 'images', 'manual', '23_tv_display_kiosk.png');

async function createManualBookSlides() {
    console.log('Generating Manual_Book_Slide_SILAKAN.pptx...');
    const pres = new pptxgen();

    // 16:9 Widescreen (13.33 x 7.5 inches)
    pres.layout = 'LAYOUT_16x9';
    pres.title = 'Manual Book Slide — SILAKAN KPwBI Prov. Sulawesi Utara';
    pres.company = 'Kantor Perwakilan Bank Indonesia Provinsi Sulawesi Utara';

    // Color Palette
    const C_NAVY = '003B73';
    const C_BLUE = '005BAA';
    const C_GOLD = 'B8972A';
    const C_DARK = '1E293B';
    const C_GRAY = '64748B';
    const C_BG_LIGHT = 'F8FAFC';
    const C_WHITE = 'FFFFFF';
    const C_BORDER = 'CBD5E1';
    const C_RED = 'DC2626';
    const C_GREEN = '059669';

    // Helper: Standard Slide Header & Footer
    function applySlideHeader(slide, title, category = 'PANDUAN OPERASIONAL SILAKAN', pageNum = '01') {
        // Top Stripe Accent
        slide.addShape(pres.ShapeType.rect, { x: 0, y: 0, w: '100%', h: 0.12, fill: { color: C_BLUE } });
        slide.addShape(pres.ShapeType.rect, { x: 9.8, y: 0, w: 3.53, h: 0.12, fill: { color: C_GOLD } });

        // Category Tag
        slide.addText(category.toUpperCase(), {
            x: 0.8, y: 0.35, w: 9.0, h: 0.28,
            fontSize: 9, fontFace: 'Plus Jakarta Sans', color: C_BLUE, bold: true, charSpacing: 1.5
        });

        // Slide Title
        slide.addText(title, {
            x: 0.8, y: 0.62, w: 9.5, h: 0.55,
            fontSize: 18, fontFace: 'Plus Jakarta Sans', color: C_NAVY, bold: true
        });

        // Title Divider
        slide.addShape(pres.ShapeType.line, {
            x: 0.8, y: 1.20, w: 11.7, h: 0,
            line: { color: C_BLUE, width: 2 }
        });

        // Top-right Logo
        if (fs.existsSync(LOGO_PATH)) {
            slide.addImage({ path: LOGO_PATH, x: 10.8, y: 0.35, w: 1.7, h: 0.65 });
        }

        // Running Footer
        slide.addShape(pres.ShapeType.line, {
            x: 0.8, y: 7.0, w: 11.7, h: 0,
            line: { color: C_BORDER, width: 0.8 }
        });

        slide.addText('Manual Book Slide SILAKAN v1.0 — Kantor Perwakilan Bank Indonesia Provinsi Sulawesi Utara', {
            x: 0.8, y: 7.05, w: 9.5, h: 0.3,
            fontSize: 8.5, fontFace: 'Plus Jakarta Sans', color: C_GRAY
        });

        slide.addText(`${pageNum} / 14`, {
            x: 10.8, y: 7.05, w: 1.7, h: 0.3,
            fontSize: 8.5, fontFace: 'Plus Jakarta Sans', color: C_NAVY, bold: true, align: 'right'
        });
    }

    // Helper: Step Card Box
    function addStepCard(slide, x, y, w, h, num, title, desc, isGold = false) {
        slide.addShape(pres.ShapeType.roundRect, {
            x: x, y: y, w: w, h: h,
            fill: { color: C_BG_LIGHT }, line: { color: C_BORDER, width: 1 },
            rectRadius: 0.1
        });

        // Badge number circle
        slide.addShape(pres.ShapeType.ellipse, {
            x: x + 0.18, y: y + 0.16, w: 0.38, h: 0.38,
            fill: { color: isGold ? C_GOLD : C_BLUE }
        });

        slide.addText(String(num), {
            x: x + 0.18, y: y + 0.16, w: 0.38, h: 0.38,
            fontSize: 11, fontFace: 'Plus Jakarta Sans', color: C_WHITE, bold: true, align: 'center', valign: 'middle'
        });

        slide.addText(title, {
            x: x + 0.68, y: y + 0.12, w: w - 0.8, h: 0.25,
            fontSize: 11, fontFace: 'Plus Jakarta Sans', color: C_NAVY, bold: true
        });

        slide.addText(desc, {
            x: x + 0.68, y: y + 0.38, w: w - 0.8, h: h - 0.45,
            fontSize: 9.5, fontFace: 'Plus Jakarta Sans', color: C_DARK, lineSpacingMultiple: 1.15
        });
    }

    // Helper: Screenshot Card Frame
    function addScreenshotFrame(slide, imgPath, caption, x = 6.8, y = 1.45, w = 5.7, h = 5.2) {
        slide.addShape(pres.ShapeType.roundRect, {
            x: x, y: y, w: w, h: h,
            fill: { color: C_WHITE }, line: { color: C_BORDER, width: 1 },
            rectRadius: 0.12,
            shadow: { type: 'outer', color: '003B73', blur: 12, offset: 4, angle: 90, opacity: 0.15 }
        });

        // Browser top bar
        slide.addShape(pres.ShapeType.roundRect, {
            x: x, y: y, w: w, h: 0.35,
            fill: { color: 'F1F5F9' }, line: { color: C_BORDER, width: 0.8 },
            rectRadius: 0.1
        });

        // 3 Dots
        slide.addShape(pres.ShapeType.ellipse, { x: x + 0.15, y: y + 0.11, w: 0.12, h: 0.12, fill: { color: 'EF4444' } });
        slide.addShape(pres.ShapeType.ellipse, { x: x + 0.32, y: y + 0.11, w: 0.12, h: 0.12, fill: { color: 'F59E0B' } });
        slide.addShape(pres.ShapeType.ellipse, { x: x + 0.49, y: y + 0.11, w: 0.12, h: 0.12, fill: { color: '10B981' } });

        slide.addText('SISTEM INFORMASI LAYANAN RUANGAN (SILAKAN)', {
            x: x + 0.7, y: y + 0.05, w: w - 0.8, h: 0.25,
            fontSize: 7.5, fontFace: 'Plus Jakarta Sans', color: C_GRAY, bold: true
        });

        // Embedded image
        if (fs.existsSync(imgPath)) {
            slide.addImage({
                path: imgPath,
                x: x + 0.12, y: y + 0.42, w: w - 0.24, h: h - 0.82,
                sizing: { type: 'contain', w: w - 0.24, h: h - 0.82 }
            });
        }

        // Caption
        slide.addText(caption, {
            x: x + 0.1, y: y + h - 0.36, w: w - 0.2, h: 0.3,
            fontSize: 8.5, fontFace: 'Plus Jakarta Sans', color: C_BLUE, bold: true, align: 'center'
        });
    }

    // ============================================================
    // SLIDE 1: COVER
    // ============================================================
    const s1 = pres.addSlide();
    s1.background = { color: '002855' };

    // Decorative gradient accent bar
    s1.addShape(pres.ShapeType.rect, { x: 0, y: 0, w: '100%', h: 0.2, fill: { color: C_GOLD } });

    // Logo BI
    if (fs.existsSync(LOGO_PATH)) {
        s1.addImage({ path: LOGO_PATH, x: 1.0, y: 1.0, w: 2.8, h: 1.1 });
    }

    s1.addText('BANK INDONESIA', {
        x: 4.1, y: 1.1, w: 8.0, h: 0.4,
        fontSize: 18, fontFace: 'Plus Jakarta Sans', color: C_WHITE, bold: true
    });
    s1.addText('Kantor Perwakilan Provinsi Sulawesi Utara', {
        x: 4.1, y: 1.55, w: 8.0, h: 0.35,
        fontSize: 12, fontFace: 'Plus Jakarta Sans', color: '93C5FD'
    });

    s1.addShape(pres.ShapeType.roundRect, {
        x: 1.0, y: 2.6, w: 3.6, h: 0.42,
        fill: { color: '003B73' }, line: { color: C_GOLD, width: 1.5 },
        rectRadius: 0.15
    });
    s1.addText('EDISI RESMI VERSI 1.0 — TAHUN 2026', {
        x: 1.0, y: 2.6, w: 3.6, h: 0.42,
        fontSize: 9.5, fontFace: 'Plus Jakarta Sans', color: 'FEF08A', bold: true, align: 'center'
    });

    s1.addText('MANUAL BOOK SLIDE\nSISTEM SILAKAN', {
        x: 1.0, y: 3.2, w: 11.0, h: 1.6,
        fontSize: 36, fontFace: 'Plus Jakarta Sans', color: C_WHITE, bold: true, lineSpacingMultiple: 1.1
    });

    s1.addText('Panduan Langkah Praktis Pemesanan Ruangan Rapat untuk Seluruh Satuan Kerja & Administrator', {
        x: 1.0, y: 4.9, w: 10.5, h: 0.6,
        fontSize: 14, fontFace: 'Plus Jakarta Sans', color: 'E2E8F0'
    });

    // 3 Highlight Feature Badges
    const bY = 5.8;
    const bW = 3.6;
    const badges = [
        { title: 'PANDUAN UNIT KERJA', desc: 'Login, cek kalender, isi form, & unggah disposisi.' },
        { title: 'PANDUAN ADMINISTRATOR', desc: 'Verifikasi berkas, persetujuan, & kelola ruangan.' },
        { title: 'LAYANAN TERPADU BI', desc: 'WhatsApp Gateway, TV Display, & Laporan PDF.' },
    ];

    badges.forEach((b, idx) => {
        const bX = 1.0 + idx * 3.9;
        s1.addShape(pres.ShapeType.roundRect, {
            x: bX, y: bY, w: bW, h: 0.95,
            fill: { color: '003B73' }, line: { color: '1E40AF', width: 1 },
            rectRadius: 0.1
        });
        s1.addText(b.title, {
            x: bX + 0.15, y: bY + 0.12, w: bW - 0.3, h: 0.25,
            fontSize: 9.5, fontFace: 'Plus Jakarta Sans', color: 'FEF08A', bold: true
        });
        s1.addText(b.desc, {
            x: bX + 0.15, y: bY + 0.38, w: bW - 0.3, h: 0.5,
            fontSize: 8.5, fontFace: 'Plus Jakarta Sans', color: 'CBD5E1'
        });
    });

    // ============================================================
    // SLIDE 2: DAFTAR ISI & AKUN RESMI
    // ============================================================
    const s2 = pres.addSlide();
    applySlideHeader(s2, 'Daftar Isi Panduan & Akses Akun Resmi', 'PANDUAN OPERASIONAL', '02');

    // Left Column: Table of Contents Card
    s2.addShape(pres.ShapeType.roundRect, {
        x: 0.8, y: 1.45, w: 5.6, h: 5.2,
        fill: { color: C_BG_LIGHT }, line: { color: C_BORDER, width: 1 },
        rectRadius: 0.12
    });
    s2.addText('STRUKTUR PANDUAN PENGGUNAAN', {
        x: 1.1, y: 1.65, w: 5.0, h: 0.3,
        fontSize: 12, fontFace: 'Plus Jakarta Sans', color: C_NAVY, bold: true
    });
    s2.addShape(pres.ShapeType.line, { x: 1.1, y: 2.0, w: 5.0, h: 0, line: { color: C_BLUE, width: 1.5 } });

    const tocList = [
        { label: '01. Alur Kerja Sistem (Integrated Workflow)', slide: 'Slide 03' },
        { label: '02. BAGIAN I: PANDUAN UNIT KERJA (USER)', slide: 'Slide 04 - 08' },
        { label: '    • Login & Membaca Kalender Ruangan', slide: 'Slide 04' },
        { label: '    • Formulir Pemesanan & Layout Meja', slide: 'Slide 05' },
        { label: '    • Unggah Disposisi & Validasi Maksimal 5 MB', slide: 'Slide 06' },
        { label: '    • Monitoring Riwayat & Fitur Selesai Awal', slide: 'Slide 07 - 08' },
        { label: '03. BAGIAN II: PANDUAN ADMINISTRATOR', slide: 'Slide 09 - 14' },
        { label: '    • Dashboard & Live Monitoring Rapat', slide: 'Slide 09' },
        { label: '    • Verifikasi & Persetujuan (WhatsApp Alert)', slide: 'Slide 10' },
        { label: '    • Mekanisme Penolakan (Alasan Wajib)', slide: 'Slide 11' },
        { label: '    • Data Master Ruangan, Layout, & Pengguna', slide: 'Slide 12' },
        { label: '    • Ekspor Excel Cepat & Cetak PDF Kop Surat BI', slide: 'Slide 13' },
        { label: '    • Layar TV Display Kiosk Lobby & Bantuan', slide: 'Slide 14' }
    ];

    let tY = 2.15;
    tocList.forEach(item => {
        s2.addText(item.label, {
            x: 1.1, y: tY, w: 3.8, h: 0.22,
            fontSize: 9, fontFace: 'Plus Jakarta Sans', color: item.label.includes('BAGIAN') ? C_BLUE : C_DARK,
            bold: item.label.includes('BAGIAN') || item.label.includes('01.')
        });
        s2.addText(item.slide, {
            x: 4.8, y: tY, w: 1.3, h: 0.22,
            fontSize: 8.5, fontFace: 'Plus Jakarta Sans', color: C_NAVY, bold: true, align: 'right'
        });
        tY += 0.28;
    });

    // Right Column: Official Accounts Card
    s2.addShape(pres.ShapeType.roundRect, {
        x: 6.8, y: 1.45, w: 5.7, h: 5.2,
        fill: { color: C_WHITE }, line: { color: C_BORDER, width: 1 },
        rectRadius: 0.12
    });
    s2.addText('KREDENSIAL AKUN RESMI KPwBI SULUT', {
        x: 7.1, y: 1.65, w: 5.1, h: 0.3,
        fontSize: 12, fontFace: 'Plus Jakarta Sans', color: C_NAVY, bold: true
    });
    s2.addShape(pres.ShapeType.line, { x: 7.1, y: 2.0, w: 5.1, h: 0, line: { color: C_GOLD, width: 1.5 } });

    s2.addText('Akses browser kantor: http://localhost:8000', {
        x: 7.1, y: 2.15, w: 5.1, h: 0.3,
        fontSize: 10, fontFace: 'Plus Jakarta Sans', color: C_BLUE, bold: true
    });

    // Accounts Table
    const tableRows = [
        [{ text: 'Role / Akun', options: { bold: true, fill: { color: 'F1F5F9' }, color: C_NAVY } },
         { text: 'Username', options: { bold: true, fill: { color: 'F1F5F9' }, color: C_NAVY } },
         { text: 'Password Default', options: { bold: true, fill: { color: 'F1F5F9' }, color: C_NAVY } }],
        [{ text: 'Administrator' }, { text: 'admin', options: { bold: true, color: C_BLUE } }, { text: 'password', options: { bold: true, color: C_RED } }],
        [{ text: 'Tim Manajemen Internal' }, { text: 'tmi_kpwbisulut' }, { text: 'kpwbisulut' }],
        [{ text: 'Unit Kehumasan' }, { text: 'uk_kpwbisulut' }, { text: 'kpwbisulut' }],
        [{ text: 'Fungsi Data & Ekon. Keu.' }, { text: 'fdsek_kpwbisulut' }, { text: 'kpwbisulut' }],
        [{ text: 'Pengelolaan Uang Rupiah' }, { text: 'pur_kpwbisulut' }, { text: 'kpwbisulut' }],
        [{ text: 'Fungsi Implementasi KSP' }, { text: 'fiksp_kpwbisulut' }, { text: 'kpwbisulut' }],
        [{ text: 'Fungsi Pengawasan Bank' }, { text: 'fpkp_kpwbisulut' }, { text: 'kpwbisulut' }],
        [{ text: 'Unit Lainnya (Total 10 Unit)' }, { text: 'fppu, pipebi, ca, dll.' }, { text: 'kpwbisulut' }]
    ];

    s2.addTable(tableRows, {
        x: 7.1, y: 2.5, w: 5.1, h: 3.8,
        colW: [2.3, 1.5, 1.3],
        fontSize: 8.5, fontFace: 'Plus Jakarta Sans',
        border: { pt: 0.5, color: C_BORDER }
    });

    // ============================================================
    // SLIDE 3: WORKFLOW
    // ============================================================
    const s3 = pres.addSlide();
    applySlideHeader(s3, 'Alur Kerja Sistem Terintegrasi (End-to-End)', 'ARSITEKTUR LAYANAN', '03');

    addStepCard(s3, 0.8, 1.45, 5.6, 1.2, 1,
        'Unit Kerja Mengajukan Permohonan',
        'Pilih ruangan di kalender visual, isi nama rapat & jam WITA, pilih konfigurasi layout meja, dan lampirkan file disposisi resmi pimpinan.');

    addStepCard(s3, 0.8, 2.75, 5.6, 1.2, 2,
        'Verifikasi Disposisi & Persetujuan Admin',
        'Admin sarpras memeriksa kelayakan permohonan. Admin berhak Menyetujui atau Menolak pengajuan (wajib menyertakan alasan tertulis resmi).');

    addStepCard(s3, 0.8, 4.05, 5.6, 1.2, 3,
        'Notifikasi WhatsApp Otomatis ke HP PIC',
        'Sistem seketika mengirimkan pesan konfirmasi resmi ke nomor WhatsApp PIC pemohon tanpa perlu konfirmasi manual via telepon.');

    addStepCard(s3, 0.8, 5.35, 5.6, 1.2, 4,
        'Live TV Display Lobi & Rekapitulasi Laporan',
        'Agenda rapat otomatis tampil pada TV lobi kantor (/display), dan riwayat pemakaian dapat diekspor menjadi laporan Excel & PDF resmi KOP BI.', true);

    addScreenshotFrame(s3, IMG_APPROVAL, 'Dashboard Sentral Verifikasi & Tata Kelola Pemesanan Ruangan');

    // ============================================================
    // SLIDE 4: [USER 1] LOGIN & KALENDER
    // ============================================================
    const s4 = pres.addSlide();
    applySlideHeader(s4, 'Login & Membaca Kalender Ketersediaan Ruangan', 'BAGIAN I: PANDUAN UNIT KERJA', '04');

    addStepCard(s4, 0.8, 1.55, 5.6, 1.5, 1,
        'Masuk Menggunakan Akun Unit Kerja',
        'Buka browser dan akses http://localhost:8000. Masukkan username unit kerja resmi Anda (contoh: tmi_kpwbisulut) dengan kata sandi kpwbisulut.');

    addStepCard(s4, 0.8, 3.20, 5.6, 1.5, 2,
        'Buka Menu "Kalender Ruangan"',
        'Kalender menyajikan seluruh agenda kegiatan pada 9 ruangan rapat KPwBI Sulut secara transparan (Ruang Tondano, Bunaken, Klabat, Lokon, dll.).');

    addStepCard(s4, 0.8, 4.85, 5.6, 1.5, 3,
        'Periksa Slot Waktu Kosong (Zona WITA)',
        'Pastikan jam mulai dan jam selesai yang Anda rencanakan belum terisi oleh rapat unit lain. Transparansi kalender menjamin 0% risiko bentrok jadwal.');

    addScreenshotFrame(s4, IMG_KALENDER, 'Kalender Visual Jadwal Pemakaian Ruangan Rapat Real-Time');

    // ============================================================
    // SLIDE 5: [USER 2] FORM PEMESANAN
    // ============================================================
    const s5 = pres.addSlide();
    applySlideHeader(s5, 'Mengisi Formulir Pengajuan Pemesanan Ruangan', 'BAGIAN I: PANDUAN UNIT KERJA', '05');

    addStepCard(s5, 0.8, 1.45, 5.6, 1.2, 1,
        'Klik Tombol "Buat Pemesanan"',
        'Pilih ruangan yang diinginkan (misal: Ruang Tondano) lalu ketik nama kegiatan dan perkiraan jumlah peserta yang akan hadir.');

    addStepCard(s5, 0.8, 2.75, 5.6, 1.2, 2,
        'Tentukan Tanggal & Jam (WITA)',
        'Masukkan tanggal pelaksanaan serta jam mulai dan jam selesai rapat. Seluruh pencatatan waktu menggunakan standar zona waktu lokal (WITA).');

    addStepCard(s5, 0.8, 4.05, 5.6, 1.2, 3,
        'Pilih Konfigurasi Layout Meja & Kursi',
        'Pilih tata letak yang sesuai dengan kebutuhan acara: Classroom, U-Shape, Boardroom, atau Round Table.');

    addStepCard(s5, 0.8, 5.35, 5.6, 1.2, 4,
        'Lengkapi Data PIC & Nomor WhatsApp',
        'Masukkan nama PIC kegiatan (disarankan nama unit) dan nomor handphone aktif untuk penerimaan notifikasi otomatis WhatsApp Gateway.');

    addScreenshotFrame(s5, IMG_FORM, 'Formulir Pemesanan Ruangan & Pemilihan Layout Meja');

    // ============================================================
    // SLIDE 6: [USER 3] DISPOSISI & POPUP 5MB
    // ============================================================
    const s6 = pres.addSlide();
    applySlideHeader(s6, 'Unggah Berkas Disposisi & Validasi Maksimal 5 MB', 'BAGIAN I: PANDUAN UNIT KERJA', '06');

    addStepCard(s6, 0.8, 1.55, 5.6, 1.5, 1,
        'Wajib Melampirkan Bukti Disposisi Pimpinan',
        'Sebagai wujud kepatuhan tata kelola BI, setiap pemesanan wajib mengunggah file lembar disposisi pimpinan atau undangan rapat (Format: PDF, JPG, PNG).');

    addStepCard(s6, 0.8, 3.20, 5.6, 1.5, 2,
        'Batas Maksimal Ukuran File: 5 MegaByte (5 MB)',
        'Sistem membatasi ukuran berkas maksimal 5 MB guna menjaga efisiensi penyimpanan dan keandalan kecepatan transmisi jaringan kantor.', true);

    addStepCard(s6, 0.8, 4.85, 5.6, 1.5, 3,
        'Proteksi Popup Keamanan Otomatis',
        'Jika file yang dipilih melebihi 5 MB, sistem otomatis membatalkan pilihan berkas dan memunculkan popup peringatan warna merah.');

    addScreenshotFrame(s6, IMG_POPUP_5MB, 'Popup Peringatan Otomatis Bila Ukuran File Melebihi 5 MB');

    // ============================================================
    // SLIDE 7: [USER 4] MONITORING STATUS
    // ============================================================
    const s7 = pres.addSlide();
    applySlideHeader(s7, 'Memantau Riwayat & Status Pengajuan Pemesanan', 'BAGIAN I: PANDUAN UNIT KERJA', '07');

    addStepCard(s7, 0.8, 1.55, 5.6, 1.5, 1,
        'Buka Menu "Pemesanan Saya"',
        'Menampilkan tabel daftar seluruh pengajuan yang diajukan oleh unit Anda lengkap dengan nomor kode unik pemesanan (misal: PM-2026-XXXX).');

    addStepCard(s7, 0.8, 3.20, 5.6, 1.5, 2,
        'Kenali 3 Status Verifikasi Pengajuan',
        '• Pending (Kuning): Permohonan sedang menunggu verifikasi admin.\n• Disetujui (Hijau): Ruangan resmi terkunci untuk unit Anda.\n• Ditolak (Merah): Permohonan tidak dapat disetujui disertai alasan resmi.');

    addStepCard(s7, 0.8, 4.85, 5.6, 1.5, 3,
        'Silent Real-Time Background Update',
        'Jika Anda standby di halaman ini, perubahan status pemesanan akan otomatis diperbarui di layar tanpa perlu menekan F5 / reload.');

    addScreenshotFrame(s7, IMG_RIWAYAT, 'Tabel Riwayat Pemesanan & Status Verifikasi Pengajuan Unit');

    // ============================================================
    // SLIDE 8: [USER 5] SELESAI AWAL & BATAL
    // ============================================================
    const s8 = pres.addSlide();
    applySlideHeader(s8, 'Fitur "Selesai Awal" & Pembatalan Mandiri', 'BAGIAN I: PANDUAN UNIT KERJA', '08');

    addStepCard(s8, 0.8, 1.55, 5.6, 1.5, 1,
        'Fitur Unggulan: "Selesai Awal" (Early Finish)',
        'Apabila rapat selesai 1 jam lebih awal dari jadwal yang tertera, klik tombol hijau "Selesai Awal" pada lembar detail pemesanan.', true);

    addStepCard(s8, 0.8, 3.20, 5.6, 1.5, 2,
        'Optimalisasi Pemanfaatan Ruangan Kantor',
        'Status ruangan seketika berubah kembali menjadi Hijau (Tersedia) di kalender dan monitor TV lobi sehingga dapat dialokasikan untuk kegiatan mendadak.');

    addStepCard(s8, 0.8, 4.85, 5.6, 1.5, 3,
        'Hak Pembatalan Mandiri oleh Unit Pemohon',
        'Selama permohonan masih berstatus Pending, unit kerja berhak membatalkan jadwal secara mandiri bila terjadi perubahan agenda pimpinan.');

    addScreenshotFrame(s8, IMG_DETAIL_USER, 'Detail Rapat dengan Tombol Fitur Selesai Awal');

    // ============================================================
    // SLIDE 9: [ADMIN 1] DASHBOARD ADMIN
    // ============================================================
    const s9 = pres.addSlide();
    applySlideHeader(s9, 'Dashboard Administrator & Monitoring Rapat Live', 'BAGIAN II: PANDUAN ADMINISTRATOR', '09');

    addStepCard(s9, 0.8, 1.55, 5.6, 1.5, 1,
        'Login dengan Akun Administrator',
        'Gunakan username admin dan kata sandi password untuk masuk ke portal manajemen sentral sarana dan prasarana ruangan.');

    addStepCard(s9, 0.8, 3.20, 5.6, 1.5, 2,
        'Banner "Kegiatan Berlangsung" Real-Time',
        'Menampilkan ruangan mana saja yang sedang aktif digunakan saat ini lengkap dengan hitung mundur sisa waktu rapat (countdown).');

    addStepCard(s9, 0.8, 4.85, 5.6, 1.5, 3,
        'Statistik Keterisian & Antrean Pengajuan',
        'Admin memantau total pemesanan bulan ini, persentase keterisian 9 ruangan, dan badge kuning jumlah pemesanan yang menunggu approval.');

    addScreenshotFrame(s9, IMG_DASHBOARD_ADMIN, 'Banner Kegiatan Berlangsung dengan Hitung Mundur Real-Time');

    // ============================================================
    // SLIDE 10: [ADMIN 2] APPROVAL SYSTEM
    // ============================================================
    const s10 = pres.addSlide();
    applySlideHeader(s10, 'Verifikasi Disposisi & Persetujuan Pemesanan', 'BAGIAN II: PANDUAN ADMINISTRATOR', '10');

    addStepCard(s10, 0.8, 1.55, 5.6, 1.5, 1,
        'Buka Menu "Pemesanan Ruangan"',
        'Admin disajikan tab switcher terorganisir: Menunggu Approval, Disetujui/Aktif, Selesai, dan Semua Pemesanan.');

    addStepCard(s10, 0.8, 3.20, 5.6, 1.5, 2,
        'Pemeriksaan Kelayakan & Lampiran Disposisi',
        'Klik tombol "Periksa" untuk membaca agenda, mengecek kecukupan kapasitas ruangan, dan mengunduh berkas disposisi yang dilampirkan.');

    addStepCard(s10, 0.8, 4.85, 5.6, 1.5, 3,
        'Klik "Setujui" &rarr; WhatsApp Otomatis Meluncur',
        'Begitu disetujui, ruangan terkunci di kalender dan sistem otomatis mengirimkan notifikasi WhatsApp resmi ke handphone PIC pemohon.', true);

    addScreenshotFrame(s10, IMG_APPROVAL, 'Antarmuka Verifikasi & Persetujuan Pemesanan Masuk');

    // ============================================================
    // SLIDE 11: [ADMIN 3] PENOLAKAN BERKAS
    // ============================================================
    const s11 = pres.addSlide();
    applySlideHeader(s11, 'Mekanisme Penolakan Berkas (Kewajiban Alasan)', 'BAGIAN II: PANDUAN ADMINISTRATOR', '11');

    addStepCard(s11, 0.8, 1.55, 5.6, 1.5, 1,
        'Prinsip Akuntabilitas & Transparansi BI',
        'Admin berhak menolak permohonan bila ruangan dibutuhkan untuk agenda VVIP mendadak atau dokumen disposisi yang diunggah tidak sesuai.');

    addStepCard(s11, 0.8, 3.20, 5.6, 1.5, 2,
        'Klik Tombol Merah "Tolak Pemesanan"',
        'Sistem akan menampilkan jendela modal konfirmasi penolakan pemesanan ruangan.');

    addStepCard(s11, 0.8, 4.85, 5.6, 1.5, 3,
        'Wajib Mencantumkan Alasan Penolakan',
        'Admin wajib mengisi alasan tertulis (misal: "Ruang Tondano dialokasikan untuk Rapat Dewan Gubernur"). Alasan otomatis terkirim ke WhatsApp PIC.', true);

    addScreenshotFrame(s11, IMG_PENOLAKAN, 'Modal Dialog Penolakan Pemesanan dengan Kolom Alasan Wajib');

    // ============================================================
    // SLIDE 12: [ADMIN 4] DATA MASTER
    // ============================================================
    const s12 = pres.addSlide();
    applySlideHeader(s12, 'Manajemen Data Master Ruangan & Pengguna', 'BAGIAN II: PANDUAN ADMINISTRATOR', '12');

    addStepCard(s12, 0.8, 1.55, 5.6, 1.5, 1,
        'Master 9 Ruangan Rapat KPwBI Sulut',
        'Kelola nama ruangan, kapasitas maksimal peserta, lokasi lantai gedung, fasilitas pendukung (AC, proyektor, sound), serta status aktif.');

    addStepCard(s12, 0.8, 3.20, 5.6, 1.5, 2,
        'Master Konfigurasi Layout Ruangan',
        'Mengatur ketersediaan variasi tata letak kursi dan meja per ruangan (Classroom, U-Shape, Round Table, Boardroom).');

    addStepCard(s12, 0.8, 4.85, 5.6, 1.5, 3,
        'Master User & Fitur Reset Password Akun',
        'Admin dapat memantau akun 10 unit kerja, melihat password saat ini menggunakan ikon mata, menyalin password, atau mereset password.');

    addScreenshotFrame(s12, IMG_MASTER_RUANGAN, 'Pengelolaan Data 9 Ruangan Rapat Gedung Kantor KPwBI Sulut');

    // ============================================================
    // SLIDE 13: [ADMIN 5] EXPORT LAPORAN
    // ============================================================
    const s13 = pres.addSlide();
    applySlideHeader(s13, 'Ekspor Laporan Excel Cepat & Cetak PDF KOP BI', 'BAGIAN II: PANDUAN ADMINISTRATOR', '13');

    addStepCard(s13, 0.8, 1.55, 5.6, 1.5, 1,
        'Buka Menu "Laporan Pemesanan"',
        'Terapkan filter berdasarkan rentang tanggal kegiatan (harian, mingguan, bulanan), ruangan tertentu, atau unit kerja pemohon.');

    addStepCard(s13, 0.8, 3.20, 5.6, 1.5, 2,
        '1-Klik "Ekspor Excel" (High Speed Engine)',
        'Mengunduh data rekapitulasi ke dalam format .xlsx secara instan tanpa jeda loading untuk kebutuhan analisis pemanfaatan aset kantor.', true);

    addStepCard(s13, 0.8, 4.85, 5.6, 1.5, 3,
        'Cetak PDF Resmi dengan KOP Bank Indonesia',
        'Menghasilkan lembar cetak laporan resmi berstandar arsip yang sudah dilengkapi Kop Surat Kantor Perwakilan Bank Indonesia Provinsi Sulawesi Utara.');

    addScreenshotFrame(s13, IMG_LAPORAN, 'Format Dokumen Laporan PDF Resmi Lengkap dengan KOP Surat BI');

    // ============================================================
    // SLIDE 14: TV DISPLAY & KONTAK
    // ============================================================
    const s14 = pres.addSlide();
    applySlideHeader(s14, 'TV Display Monitor Lobby & Kontak Bantuan', 'MODUL EKSEKUTIF & PENUTUP', '14');

    addStepCard(s14, 0.8, 1.55, 5.6, 1.5, 1,
        'Kiosk Mode TV Lobi Kantor: /display',
        'Buka URL http://localhost:8000/display pada layar monitor TV di lobi utama atau depan ruang rapat untuk penyambutan tamu eksternal.');

    addStepCard(s14, 0.8, 3.20, 5.6, 1.5, 2,
        'Auto-Refresh 15 Detik Tanpa Disentuh',
        'Tampilan otomatis bergulir memperbarui jadwal rapat hari ini secara hening, menyajikan informasi ruangan dan unit kerja secara elegan.', true);

    addStepCard(s14, 0.8, 4.85, 5.6, 1.5, 3,
        'Narahubung & Layanan Dukungan Teknis',
        'Bila mengalami kendala teknis atau memerlukan penyesuaian hak akses:\nHubungi Tim Manajemen Internal (TMI) / Sarpras KPwBI Prov. Sulawesi Utara.');

    addScreenshotFrame(s14, IMG_TV_DISPLAY, 'Tampilan TV Monitor Lobi Kantor Gedung KPwBI Sulut');

    // Save PPTX Files
    await pres.writeFile({ fileName: OUT_PPTX });
    console.log('Saved presentation to:', OUT_PPTX);

    // Also copy to public folder
    fs.copyFileSync(OUT_PPTX, OUT_PPTX_PUB);
    console.log('Copied to public folder:', OUT_PPTX_PUB);

    console.log('ALL MANUAL BOOK SLIDES PPTX GENERATED SUCCESSFULLY!');
}

createManualBookSlides().catch(err => {
    console.error('Error generating Manual Book Slides PPTX:', err);
    process.exit(1);
});
