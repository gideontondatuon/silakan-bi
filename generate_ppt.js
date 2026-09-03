import pptxgen from 'pptxgenjs';
import path from 'path';
import { fileURLToPath } from 'url';
import fs from 'fs';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const OUT_PPTX = path.join(__dirname, 'SILAKAN_Presentasi_Manajer.pptx');
const OUT_PPTX_PUB = path.join(__dirname, 'public', 'SILAKAN_Presentasi_Manajer.pptx');

const LOGO_PATH = path.join(__dirname, 'public', 'images', 'logo-bi4.png');
const IMG_DASHBOARD_USER = path.join(__dirname, 'public', 'images', 'manual', '02_dashboard_user.png');
const IMG_FORM = path.join(__dirname, 'public', 'images', 'manual', '03_form_pemesanan.png');
const IMG_POPUP_5MB = path.join(__dirname, 'public', 'images', 'manual', '03b_popup_file_5mb.png');
const IMG_APPROVAL = path.join(__dirname, 'public', 'images', 'manual', '12_approval_index.png');
const IMG_MONITORING = path.join(__dirname, 'public', 'images', 'manual', '14_live_monitoring.png');
const IMG_LAPORAN = path.join(__dirname, 'public', 'images', 'manual', '20_laporan_filter.png');
const IMG_KALENDER = path.join(__dirname, 'public', 'images', 'manual', '08_kalender_ruangan.png');

async function createPresentation() {
    console.log('Generating SILAKAN_Presentasi_Manajer.pptx...');
    const pres = new pptxgen();

    // 16:9 Widescreen
    pres.layout = 'LAYOUT_16x9';
    pres.title = 'SILAKAN — Presentasi Eksekutif untuk Manajer';
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

    // Helper: Standard Slide Background & Running Elements
    function applySlideHeader(slide, title, category = 'SISTEM INFORMASI LAYANAN KANTOR (SILAKAN)') {
        // Top Stripe
        slide.addShape(pres.ShapeType.rect, { x: 0, y: 0, w: '100%', h: 0.12, fill: { color: C_BLUE } });
        slide.addShape(pres.ShapeType.rect, { x: 9.8, y: 0, w: 3.53, h: 0.12, fill: { color: C_GOLD } });

        // Category Tag
        slide.addText(category.toUpperCase(), {
            x: 0.8, y: 0.35, w: 9.0, h: 0.3,
            fontSize: 9, fontFace: 'Plus Jakarta Sans', color: C_BLUE, bold: true, charSpacing: 1.5
        });

        // Slide Title
        slide.addText(title, {
            x: 0.8, y: 0.65, w: 9.5, h: 0.55,
            fontSize: 20, fontFace: 'Plus Jakarta Sans', color: C_NAVY, bold: true
        });

        // Top Divider Line (ONLY FOR TITLE)
        slide.addShape(pres.ShapeType.line, {
            x: 0.8, y: 1.22, w: 11.7, h: 0,
            line: { color: C_BLUE, width: 2 }
        });

        // Top-right small logo
        if (fs.existsSync(LOGO_PATH)) {
            slide.addImage({ path: LOGO_PATH, x: 10.8, y: 0.35, w: 1.7, h: 0.65 });
        }

        // Running Footer
        slide.addShape(pres.ShapeType.line, {
            x: 0.8, y: 7.0, w: 11.7, h: 0,
            line: { color: C_BORDER, width: 0.8 }
        });

        slide.addText('SILAKAN v1.0 — Kantor Perwakilan Bank Indonesia Provinsi Sulawesi Utara', {
            x: 0.8, y: 7.05, w: 9.0, h: 0.3,
            fontSize: 8.5, fontFace: 'Plus Jakarta Sans', color: C_GRAY
        });

        slide.addText('Paparan Manajemen', {
            x: 10.5, y: 7.05, w: 2.0, h: 0.3,
            fontSize: 8.5, fontFace: 'Plus Jakarta Sans', color: C_NAVY, bold: true, align: 'right'
        });
    }

    // ==========================================
    // SLIDE 1: COVER
    // ==========================================
    const slide1 = pres.addSlide();
    slide1.background = { color: C_WHITE };

    // Left accent bar
    slide1.addShape(pres.ShapeType.rect, { x: 0, y: 0, w: 0.35, h: 7.5, fill: { color: C_BLUE } });
    slide1.addShape(pres.ShapeType.rect, { x: 0.35, y: 0, w: 0.15, h: 7.5, fill: { color: C_GOLD } });

    // Logo BI
    if (fs.existsSync(LOGO_PATH)) {
        slide1.addImage({ path: LOGO_PATH, x: 1.0, y: 0.8, w: 2.6, h: 1.0 });
    }

    slide1.addText('BANK INDONESIA', {
        x: 3.8, y: 0.9, w: 8.0, h: 0.4,
        fontSize: 16, fontFace: 'Plus Jakarta Sans', color: C_NAVY, bold: true
    });
    slide1.addText('Kantor Perwakilan Provinsi Sulawesi Utara', {
        x: 3.8, y: 1.3, w: 8.0, h: 0.35,
        fontSize: 11, fontFace: 'Plus Jakarta Sans', color: C_GRAY
    });

    slide1.addShape(pres.ShapeType.rect, {
        x: 1.0, y: 2.2, w: 2.8, h: 0.38,
        fill: { color: 'EFF6FF' }, line: { color: 'BFDBFE', width: 1 },
        rectRadius: 0.15
    });
    slide1.addText('PAPARAN EKSEKUTIF SISTEM', {
        x: 1.0, y: 2.2, w: 2.8, h: 0.38,
        fontSize: 9, fontFace: 'Plus Jakarta Sans', color: C_BLUE, bold: true, align: 'center'
    });

    slide1.addText('SILAKAN v1.0', {
        x: 1.0, y: 2.7, w: 11.0, h: 0.9,
        fontSize: 44, fontFace: 'Plus Jakarta Sans', color: C_NAVY, bold: true
    });

    slide1.addText('Sistem Informasi Layanan Kantor — Solusi Digitalisasi & Otomasi Pemesanan Ruangan Rapat', {
        x: 1.0, y: 3.65, w: 10.5, h: 0.5,
        fontSize: 14, fontFace: 'Plus Jakarta Sans', color: C_BLUE, bold: true
    });

    slide1.addText('Modernisasi tata kelola fasilitas kantor guna meningkatkan efisiensi, akurasi penjadwalan (Zero Double-Booking), transparansi antar-unit kerja, dan kepatuhan administrasi berbasis disposisi digital.', {
        x: 1.0, y: 4.25, w: 10.5, h: 0.8,
        fontSize: 11, fontFace: 'Plus Jakarta Sans', color: C_DARK, lineSpacing: 18
    });

    // Divider
    slide1.addShape(pres.ShapeType.line, {
        x: 1.0, y: 5.2, w: 11.3, h: 0,
        line: { color: C_GOLD, width: 2.5 }
    });

    // Meta box bottom
    slide1.addShape(pres.ShapeType.rect, {
        x: 1.0, y: 5.5, w: 3.5, h: 1.3,
        fill: { color: C_BG_LIGHT }, line: { color: C_BORDER, width: 1 }, rectRadius: 0.1
    });
    slide1.addText('AUDIENS PRESENTASI', { x: 1.2, y: 5.65, w: 3.1, h: 0.25, fontSize: 8, color: C_GRAY, bold: true });
    slide1.addText('Manajemen & Pimpinan', { x: 1.2, y: 5.9, w: 3.1, h: 0.4, fontSize: 13, color: C_NAVY, bold: true });
    slide1.addText('KPwBI Prov. Sulawesi Utara', { x: 1.2, y: 6.3, w: 3.1, h: 0.3, fontSize: 9.5, color: C_DARK });

    slide1.addShape(pres.ShapeType.rect, {
        x: 4.8, y: 5.5, w: 3.5, h: 1.3,
        fill: { color: C_BG_LIGHT }, line: { color: C_BORDER, width: 1 }, rectRadius: 0.1
    });
    slide1.addText('CAKUPAN OPERASIONAL', { x: 5.0, y: 5.65, w: 3.1, h: 0.25, fontSize: 8, color: C_GRAY, bold: true });
    slide1.addText('9 Ruangan & 10 Unit', { x: 5.0, y: 5.9, w: 3.1, h: 0.4, fontSize: 13, color: C_NAVY, bold: true });
    slide1.addText('Terintegrasi WhatsApp Gateway', { x: 5.0, y: 6.3, w: 3.1, h: 0.3, fontSize: 9.5, color: C_DARK });

    slide1.addShape(pres.ShapeType.rect, {
        x: 8.6, y: 5.5, w: 3.7, h: 1.3,
        fill: { color: C_BG_LIGHT }, line: { color: C_BORDER, width: 1 }, rectRadius: 0.1
    });
    slide1.addText('STATUS IMPLEMENTASI', { x: 8.8, y: 5.65, w: 3.3, h: 0.25, fontSize: 8, color: C_GRAY, bold: true });
    slide1.addText('Production Ready (v1.0)', { x: 8.8, y: 5.9, w: 3.3, h: 0.4, fontSize: 13, color: '166534', bold: true });
    slide1.addText('Manual Book Lengkap (14 Halaman)', { x: 8.8, y: 6.3, w: 3.3, h: 0.3, fontSize: 9.5, color: C_DARK });

    // ==========================================
    // SLIDE 2: LATAR BELAKANG & URGENSI
    // ==========================================
    const slide2 = pres.addSlide();
    slide2.background = { color: C_WHITE };
    applySlideHeader(slide2, 'Latar Belakang & Urgensi Digitalisasi', 'TANTANGAN OPERASIONAL');

    // Left Column: Kondisi Sebelumnya
    slide2.addShape(pres.ShapeType.rect, {
        x: 0.8, y: 1.5, w: 5.6, h: 5.2,
        fill: { color: 'FEF2F2' }, line: { color: 'FECACA', width: 1.2 }, rectRadius: 0.12
    });
    slide2.addText('KONDISI SEBELUM SISTEM (MANUAL)', {
        x: 1.1, y: 1.7, w: 5.0, h: 0.35,
        fontSize: 11, fontFace: 'Plus Jakarta Sans', color: '991B1B', bold: true
    });

    const manualPoints = [
        '1. Bentrok Jadwal (Double Booking): Sering terjadi pemesanan ruangan pada jam yang sama oleh dua unit berbeda karena koordinasi lisan.',
        '2. Verifikasi Disposisi Lambat: Lembar disposisi fisik sering tertahan di meja staf atau tercecer, menunda kepastian rapat pimpinan.',
        '3. Tidak Ada Visibilitas Real-Time: Pegawai tidak tahu apakah ruangan kosong atau sedang dipakai tanpa harus mendatangi lokasi langsung.',
        '4. Rekapitulasi Laporan Sulit: Pembuatan laporan rekap penggunaan ruangan memakan waktu berhari-hari untuk mencari arsip nota dinas.'
    ];
    slide2.addText(manualPoints.join('\n\n'), {
        x: 1.1, y: 2.15, w: 5.0, h: 4.3,
        fontSize: 10, fontFace: 'Plus Jakarta Sans', color: '7F1D1D', lineSpacing: 18
    });

    // Right Column: Target Solusi SILAKAN
    slide2.addShape(pres.ShapeType.rect, {
        x: 6.8, y: 1.5, w: 5.7, h: 5.2,
        fill: { color: 'F0FDF4' }, line: { color: 'BBF7D0', width: 1.2 }, rectRadius: 0.12
    });
    slide2.addText('TRANSFORMASI DENGAN SILAKAN', {
        x: 7.1, y: 1.7, w: 5.1, h: 0.35,
        fontSize: 11, fontFace: 'Plus Jakarta Sans', color: '166534', bold: true
    });

    const modernPoints = [
        '1. 100% Bebas Bentrok (Zero Conflict): Algoritma validasi jadwal otomatis (WITA) menolak booking yang saling tumpang tindih secara seketika.',
        '2. Disposisi Digital Cepat: Pengunggahan berkas digital (PDF/gambar maks. 5 MB) dengan notifikasi langsung ke admin untuk di-review.',
        '3. Kalender Interaktif Terbuka: Seluruh unit kerja dapat melihat agenda pemakaian ruangan secara transparan kapan saja dan di mana saja.',
        '4. Laporan Eksekutif 1-Klik: Admin dapat mengunduh rekap Excel dan cetak PDF resmi bertanda tangan dalam hitungan detik.'
    ];
    slide2.addText(modernPoints.join('\n\n'), {
        x: 7.1, y: 2.15, w: 5.1, h: 4.3,
        fontSize: 10, fontFace: 'Plus Jakarta Sans', color: '14532D', lineSpacing: 18
    });

    // ==========================================
    // SLIDE 3: 4 FITUR UNGGULAN SISTEM
    // ==========================================
    const slide3 = pres.addSlide();
    slide3.background = { color: C_WHITE };
    applySlideHeader(slide3, 'Nilai Tambah & Fitur Unggulan Utama', 'SOLUSI TERINTEGRASI');

    const featureCards = [
        {
            title: '1. Validasi Bentrok Jadwal (WITA)',
            desc: 'Sistem secara cerdas memeriksa irisan waktu mulai & selesai. Pemesanan yang bertabrakan otomatis diblokir serta mencegah waktu kedaluwarsa.',
            tag: 'Akurasi 100%',
            color: C_BLUE,
            bg: 'EFF6FF'
        },
        {
            title: '2. Notifikasi WhatsApp Otomatis',
            desc: 'Terintegrasi WhatsApp Gateway resmi. Setiap keputusan persetujuan atau penolakan langsung dikirim ke ponsel PIC unit kegiatan.',
            tag: 'Responsif & Nyata',
            color: '166534',
            bg: 'F0FDF4'
        },
        {
            title: '3. Validasi Berkas Disposisi 5 MB',
            desc: 'Sistem membatasi lampiran maks. 5 MB dengan proteksi popup modal instan untuk menjaga stabilitas kapasitas server dan performa unduh berkas.',
            tag: 'Kepatuhan Tata Graha',
            color: '92400E',
            bg: 'FFFBEB'
        },
        {
            title: '4. Live Monitoring & Early Finish',
            desc: 'Menampilkan countdown timer rapat yang sedang berjalan. Rapat yang usai lebih cepat dapat langsung diselesaikan untuk membuka jadwal unit lain.',
            tag: 'Optimalisasi Fasilitas',
            color: C_NAVY,
            bg: 'F1F5F9'
        }
    ];

    featureCards.forEach((card, idx) => {
        const xPos = 0.8 + (idx % 2) * 6.0;
        const yPos = 1.5 + Math.floor(idx / 2) * 2.65;

        slide3.addShape(pres.ShapeType.rect, {
            x: xPos, y: yPos, w: 5.7, h: 2.4,
            fill: { color: card.bg }, line: { color: C_BORDER, width: 1 }, rectRadius: 0.1
        });

        slide3.addText(card.title, {
            x: xPos + 0.3, y: yPos + 0.25, w: 5.1, h: 0.35,
            fontSize: 12, fontFace: 'Plus Jakarta Sans', color: card.color, bold: true
        });

        slide3.addText(card.desc, {
            x: xPos + 0.3, y: yPos + 0.7, w: 5.1, h: 1.1,
            fontSize: 10, fontFace: 'Plus Jakarta Sans', color: C_DARK, lineSpacing: 16
        });

        slide3.addShape(pres.ShapeType.rect, {
            x: xPos + 0.3, y: yPos + 1.85, w: 2.2, h: 0.32,
            fill: { color: C_WHITE }, line: { color: C_BORDER, width: 0.8 }, rectRadius: 0.08
        });
        slide3.addText(card.tag, {
            x: xPos + 0.3, y: yPos + 1.85, w: 2.2, h: 0.32,
            fontSize: 8.5, fontFace: 'Plus Jakarta Sans', color: card.color, bold: true, align: 'center'
        });
    });

    // ==========================================
    // SLIDE 4: CAKUPAN RUANGAN & USER
    // ==========================================
    const slide4 = pres.addSlide();
    slide4.background = { color: C_WHITE };
    applySlideHeader(slide4, 'Infrastruktur 9 Ruangan Rapat & 10 Akun Unit Kerja', 'CAKUPAN OPERASIONAL');

    // Left Table: 9 Ruangan
    slide4.addText('DAFTAR 9 RUANGAN RAPAT RESMI KPwBI SULUT', {
        x: 0.8, y: 1.45, w: 6.0, h: 0.3,
        fontSize: 10, fontFace: 'Plus Jakarta Sans', color: C_NAVY, bold: true
    });

    const roomRows = [
        [{ text: 'Nama Ruangan', options: { bold: true, fill: C_NAVY, color: C_WHITE } },
         { text: 'Kapasitas', options: { bold: true, fill: C_NAVY, color: C_WHITE } },
         { text: 'Lokasi Lantai', options: { bold: true, fill: C_NAVY, color: C_WHITE } }],
        ['Tondano (Auditorium Utama)', '300 Orang', 'Lantai 3'],
        ['Klabat (Ruang Rapat Utama)', '70 Orang', 'Lantai 4'],
        ['Karangetang Besar', '53 Orang', 'Lantai 4'],
        ['Karangetang Kecil', '25 Orang', 'Lantai 4'],
        ['Bunaken', '23 Orang', 'Lantai 2'],
        ['Tomohon', '23 Orang', 'Lantai 3'],
        ['Lokon', '20 Orang', 'Lantai 1'],
        ['Linow 1 & Linow 2', '7 & 8 Orang', 'Lantai 1 (VIP/Transit)']
    ];

    slide4.addTable(roomRows, {
        x: 0.8, y: 1.8, w: 6.0, h: 3.3,
        fontSize: 8.5, fontFace: 'Plus Jakarta Sans',
        border: { pt: 0.5, color: C_BORDER },
        rowH: [0.35, 0.32, 0.32, 0.32, 0.32, 0.32, 0.32, 0.32, 0.32]
    });

    // Smart Layout note
    slide4.addShape(pres.ShapeType.rect, {
        x: 0.8, y: 5.3, w: 6.0, h: 1.4,
        fill: { color: C_BG_LIGHT }, line: { color: C_BORDER, width: 1 }, rectRadius: 0.08
    });
    slide4.addText('Sistem Smart Layout Dinamis:', {
        x: 1.0, y: 5.4, w: 5.6, h: 0.25,
        fontSize: 9.5, fontFace: 'Plus Jakarta Sans', color: C_BLUE, bold: true
    });
    slide4.addText('Setiap ruangan hanya menampilkan pilihan tata letak yang didukung secara fisik (misal: Teater, U-Shape, Classroom, Round Table) sehingga tidak terjadi kesalahan penataan oleh petugas tata graha.', {
        x: 1.0, y: 5.7, w: 5.6, h: 0.9,
        fontSize: 8.5, fontFace: 'Plus Jakarta Sans', color: C_DARK, lineSpacing: 14
    });

    // Right Box: 10 Unit Kerja
    slide4.addText('10 AKUN UNIT KERJA RESMI TERDAFTAR', {
        x: 7.2, y: 1.45, w: 5.3, h: 0.3,
        fontSize: 10, fontFace: 'Plus Jakarta Sans', color: C_NAVY, bold: true
    });

    slide4.addShape(pres.ShapeType.rect, {
        x: 7.2, y: 1.8, w: 5.3, h: 4.9,
        fill: { color: C_WHITE }, line: { color: C_BORDER, width: 1.2 }, rectRadius: 0.1
    });

    const units = [
        '1. tmi_kpwbisulut — Tim Manajemen Internal',
        '2. uk_kpwbisulut — Unit Kehumasan',
        '3. fiksp_kpwbisulut — Kebijakan Sistem Pembayaran',
        '4. pur_kpwbisulut — Pengelolaan Uang Rupiah',
        '5. fdsek_kpwbisulut — Data & Statistik Ekonomi',
        '6. fpkp_kpwbisulut — Pengendalian Inflasi & Kebijakan',
        '7. fppu_kpwbisulut — Pengembangan UMKM',
        '8. pipebi_kpwbisulut — Persatuan Istri Pegawai BI',
        '9. ppbi_kpwbisulut — Persatuan Pegawai BI',
        '10. ca_kpwbisulut — Change Agent',
        '11. admin — Administrator Sistem'
    ];

    slide4.addText(units.join('\n'), {
        x: 7.5, y: 2.0, w: 4.8, h: 4.4,
        fontSize: 9.5, fontFace: 'Plus Jakarta Sans', color: C_DARK, lineSpacing: 18
    });

    // ==========================================
    // SLIDE 5: ALUR KERJA SISTEM (WORKFLOW)
    // ==========================================
    const slide5 = pres.addSlide();
    slide5.background = { color: C_WHITE };
    applySlideHeader(slide5, 'Alur Operasional Pengajuan & Persetujuan', 'STANDAR OPERASIONAL PROSEDUR');

    const steps = [
        {
            num: '01',
            role: 'USER (UNIT KERJA)',
            title: 'Cek Kalender & Ketersediaan',
            desc: 'Pegawai unit memeriksa jadwal rapat pada kalender interaktif untuk memilih ruangan & tanggal yang kosong.'
        },
        {
            num: '02',
            role: 'USER (UNIT KERJA)',
            title: 'Isi Form & Unggah Disposisi',
            desc: 'Mengisi detail acara, jam WITA, layout, nama PIC, nomor WA, dan mengunggah lembar disposisi pimpinan (maks. 5 MB).'
        },
        {
            num: '03',
            role: 'ADMINISTRATOR',
            title: 'Review Berkas & Keputusan',
            desc: 'Admin memeriksa disposisi. Jika sesuai disetujui; jika ditolak, wajib melampirkan alasan resmi pembatalan.'
        },
        {
            num: '04',
            role: 'SISTEM SILAKAN',
            title: 'Notifikasi Otomatis WhatsApp',
            desc: 'Server mengirimkan pesan WhatsApp real-time berisi bukti persetujuan atau catatan penolakan ke ponsel PIC.'
        },
        {
            num: '05',
            role: 'ADMINISTRATOR & USER',
            title: 'Pelaksanaan & Early Finish',
            desc: 'Rapat berlangsung dipantau via live timer. Ruangan dapat diselesaikan lebih awal jika rapat selesai cepat.'
        }
    ];

    steps.forEach((st, idx) => {
        const xPos = 0.8 + idx * 2.38;
        slide5.addShape(pres.ShapeType.rect, {
            x: xPos, y: 1.6, w: 2.25, h: 4.9,
            fill: { color: C_BG_LIGHT }, line: { color: C_BORDER, width: 1 }, rectRadius: 0.1
        });

        slide5.addShape(pres.ShapeType.rect, {
            x: xPos + 0.2, y: 1.8, w: 0.8, h: 0.45,
            fill: { color: C_NAVY }, rectRadius: 0.08
        });
        slide5.addText(st.num, {
            x: xPos + 0.2, y: 1.8, w: 0.8, h: 0.45,
            fontSize: 14, fontFace: 'Plus Jakarta Sans', color: C_WHITE, bold: true, align: 'center'
        });

        slide5.addText(st.role, {
            x: xPos + 0.2, y: 2.4, w: 1.85, h: 0.35,
            fontSize: 8, fontFace: 'Plus Jakarta Sans', color: C_BLUE, bold: true
        });

        slide5.addText(st.title, {
            x: xPos + 0.2, y: 2.8, w: 1.85, h: 0.8,
            fontSize: 11, fontFace: 'Plus Jakarta Sans', color: C_NAVY, bold: true
        });

        slide5.addShape(pres.ShapeType.line, {
            x: xPos + 0.2, y: 3.65, w: 1.85, h: 0,
            line: { color: C_BORDER, width: 1 }
        });

        slide5.addText(st.desc, {
            x: xPos + 0.2, y: 3.8, w: 1.85, h: 2.5,
            fontSize: 9, fontFace: 'Plus Jakarta Sans', color: C_DARK, lineSpacing: 15
        });
    });

    // ==========================================
    // SLIDE 6: SHOWCASE SCREENSHOT AKTUAL
    // ==========================================
    const slide6 = pres.addSlide();
    slide6.background = { color: C_WHITE };
    applySlideHeader(slide6, 'Antarmuka Visual Sistem (Live Production)', 'BUKTI VISUAL PRODUKSI');

    // Screenshot 1: Dashboard User
    if (fs.existsSync(IMG_DASHBOARD_USER)) {
        slide6.addImage({ path: IMG_DASHBOARD_USER, x: 0.8, y: 1.5, w: 5.6, h: 2.4 });
        slide6.addText('1. Dashboard Pengguna (Unit TMI)', {
            x: 0.8, y: 3.95, w: 5.6, h: 0.3,
            fontSize: 9, fontFace: 'Plus Jakarta Sans', color: C_NAVY, bold: true, align: 'center'
        });
    }

    // Screenshot 2: 5MB Popup
    if (fs.existsSync(IMG_POPUP_5MB)) {
        slide6.addImage({ path: IMG_POPUP_5MB, x: 6.8, y: 1.5, w: 5.7, h: 2.4 });
        slide6.addText('2. Validasi Instan Berkas Disposisi > 5 MB', {
            x: 6.8, y: 3.95, w: 5.7, h: 0.3,
            fontSize: 9, fontFace: 'Plus Jakarta Sans', color: C_NAVY, bold: true, align: 'center'
        });
    }

    // Screenshot 3: Approval Review
    if (fs.existsSync(IMG_APPROVAL)) {
        slide6.addImage({ path: IMG_APPROVAL, x: 0.8, y: 4.35, w: 5.6, h: 2.3 });
        slide6.addText('3. Panel Verifikasi Persetujuan & Penolakan', {
            x: 0.8, y: 6.7, w: 5.6, h: 0.3,
            fontSize: 9, fontFace: 'Plus Jakarta Sans', color: C_NAVY, bold: true, align: 'center'
        });
    }

    // Screenshot 4: Live Monitoring
    if (fs.existsSync(IMG_MONITORING)) {
        slide6.addImage({ path: IMG_MONITORING, x: 6.8, y: 4.35, w: 5.7, h: 2.3 });
        slide6.addText('4. Live Monitoring Ruangan Aktif & Countdown', {
            x: 6.8, y: 6.7, w: 5.7, h: 0.3,
            fontSize: 9, fontFace: 'Plus Jakarta Sans', color: C_NAVY, bold: true, align: 'center'
        });
    }

    // ==========================================
    // SLIDE 7: PELAPORAN & TERTIB ADMINISTRASI
    // ==========================================
    const slide7 = pres.addSlide();
    slide7.background = { color: C_WHITE };
    applySlideHeader(slide7, 'Pelaporan Eksekutif & Kepatuhan Tata Kelola', 'AKUNTABILITAS & AUDIT');

    // Left Column: Image Laporan
    if (fs.existsSync(IMG_LAPORAN)) {
        slide7.addImage({ path: IMG_LAPORAN, x: 0.8, y: 1.5, w: 5.8, h: 3.5 });
        slide7.addText('Antarmuka Modul Laporan & Ekspor Cepat', {
            x: 0.8, y: 5.05, w: 5.8, h: 0.3,
            fontSize: 8.5, fontFace: 'Plus Jakarta Sans', color: C_GRAY, align: 'center', italic: true
        });
    }

    slide7.addShape(pres.ShapeType.rect, {
        x: 0.8, y: 5.4, w: 5.8, h: 1.4,
        fill: { color: 'F0FDF4' }, line: { color: 'BBF7D0', width: 1 }, rectRadius: 0.08
    });
    slide7.addText('Ekspor Cepat Tanpa Loading Gantung:', {
        x: 1.0, y: 5.5, w: 5.4, h: 0.25,
        fontSize: 9.5, fontFace: 'Plus Jakarta Sans', color: '166534', bold: true
    });
    slide7.addText('Modul laporan telah dioptimalkan secara teknis menggunakan lightweight stream engine, menghasilkan berkas Excel (.xlsx) murni secara instan bahkan untuk ribuan data historis.', {
        x: 1.0, y: 5.8, w: 5.4, h: 0.85,
        fontSize: 8.5, fontFace: 'Plus Jakarta Sans', color: '14532D', lineSpacing: 14
    });

    // Right Column: Key Capabilities
    const govPoints = [
        {
            title: 'Format Cetak PDF Resmi KOP BI',
            desc: 'Mencetak dokumen rekapitulasi penggunaan ruangan rapat siap tanda tangan pimpinan lengkap dengan kop resmi Kantor Perwakilan Sulawesi Utara.'
        },
        {
            title: 'Rekam Jejak Terpusat (Audit Trail)',
            desc: 'Seluruh riwayat pengajuan, persetujuan, penolakan beserta alasan penolakannya tersimpan rapi dan tidak dapat dimanipulasi.'
        },
        {
            title: 'Proteksi Akun & Password Masking',
            desc: 'Admin dapat melihat dan menyalin sandi akun unit kerja dengan aman melalui tombol mata dan copy clipboard tanpa membocorkan data privat.'
        }
    ];

    govPoints.forEach((gp, idx) => {
        const yPos = 1.5 + idx * 1.75;
        slide7.addShape(pres.ShapeType.rect, {
            x: 7.0, y: yPos, w: 5.5, h: 1.55,
            fill: { color: C_BG_LIGHT }, line: { color: C_BORDER, width: 1 }, rectRadius: 0.1
        });

        slide7.addText(gp.title, {
            x: 7.3, y: yPos + 0.2, w: 4.9, h: 0.35,
            fontSize: 11, fontFace: 'Plus Jakarta Sans', color: C_NAVY, bold: true
        });

        slide7.addText(gp.desc, {
            x: 7.3, y: yPos + 0.6, w: 4.9, h: 0.85,
            fontSize: 9, fontFace: 'Plus Jakarta Sans', color: C_DARK, lineSpacing: 15
        });
    });

    // ==========================================
    // SLIDE 8: KESIMPULAN & PENUTUP
    // ==========================================
    const slide8 = pres.addSlide();
    slide8.background = { color: C_WHITE };
    applySlideHeader(slide8, 'Kesimpulan, Dampak Operasional & Kesiapan', 'KESIMPULAN EKSEKUTIF');

    // 3 Stat Boxes
    const stats = [
        { num: '0%', label: 'JADWAL GANDA', sub: 'Zero Double Booking berkat validasi otomatis WITA' },
        { num: '100%', label: 'DIGITALISASI DISPOSISI', sub: 'Arsip berkas tersimpan rapi dan mudah diaudit' },
        { num: '< 1 Menit', label: 'WAKTU NOTIFIKASI', sub: 'Pemberitahuan persetujuan otomatis via WhatsApp' }
    ];

    stats.forEach((st, idx) => {
        const xPos = 0.8 + idx * 4.0;
        slide8.addShape(pres.ShapeType.rect, {
            x: xPos, y: 1.6, w: 3.7, h: 1.9,
            fill: { color: C_BG_LIGHT }, line: { color: C_BLUE, width: 1.5 }, rectRadius: 0.1
        });

        slide8.addText(st.num, {
            x: xPos + 0.2, y: 1.8, w: 3.3, h: 0.65,
            fontSize: 28, fontFace: 'Plus Jakarta Sans', color: C_BLUE, bold: true, align: 'center'
        });

        slide8.addText(st.label, {
            x: xPos + 0.2, y: 2.5, w: 3.3, h: 0.3,
            fontSize: 9.5, fontFace: 'Plus Jakarta Sans', color: C_NAVY, bold: true, align: 'center'
        });

        slide8.addText(st.sub, {
            x: xPos + 0.2, y: 2.8, w: 3.3, h: 0.6,
            fontSize: 8.5, fontFace: 'Plus Jakarta Sans', color: C_GRAY, align: 'center'
        });
    });

    // Big Final Note
    slide8.addShape(pres.ShapeType.rect, {
        x: 0.8, y: 3.8, w: 11.7, h: 2.7,
        fill: { color: 'EFF6FF' }, line: { color: 'BFDBFE', width: 1.2 }, rectRadius: 0.12
    });

    slide8.addText('REKOMENDASI & LANGKAH SELANJUTNYA UNTUK MANAJEMEN', {
        x: 1.1, y: 4.05, w: 11.1, h: 0.35,
        fontSize: 11, fontFace: 'Plus Jakarta Sans', color: C_BLUE, bold: true
    });

    const recommendations = [
        '1. Penerapan Penuh (Go-Live): Sistem SILAKAN v1.0 siap diberlakukan sebagai satu-satunya kanal resmi pemesanan ruangan rapat kantor.',
        '2. Sosialisasi Unit Kerja: Buku Panduan Resmi (Manual Book SILAKAN v1.0 — 14 Halaman) telah selesai dan siap didistribusikan ke 10 unit kerja.',
        '3. Peningkatan Layanan Fasilitas: Petugas dapat memanfaatkan fitur Live Monitoring & Early Release untuk memaksimalkan utilitas ruangan.'
    ];

    slide8.addText(recommendations.join('\n\n'), {
        x: 1.1, y: 4.45, w: 11.1, h: 1.8,
        fontSize: 10, fontFace: 'Plus Jakarta Sans', color: C_DARK, lineSpacing: 18
    });

    // Write Presentation File
    await pres.writeFile({ fileName: OUT_PPTX });
    fs.copyFileSync(OUT_PPTX, OUT_PPTX_PUB);

    console.log('Saved PowerPoint to:', OUT_PPTX);
    console.log('Saved PowerPoint to Public:', OUT_PPTX_PUB);
}

createPresentation().catch(err => {
    console.error('Error generating PPTX:', err);
    process.exit(1);
});
