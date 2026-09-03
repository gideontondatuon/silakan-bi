<?php

require 'vendor/autoload.php';

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\SimpleType\JcTable;
use PhpOffice\PhpWord\Style\Table as TableStyle;

$phpWord = new PhpWord();

// Define global styles
$phpWord->setDefaultFontName('Calibri');
$phpWord->setDefaultFontSize(10.5);

// Custom Font Styles
$phpWord->addFontStyle('CoverTitle', ['name' => 'Calibri', 'size' => 28, 'bold' => true, 'color' => '003B73']);
$phpWord->addFontStyle('CoverSubtitle', ['name' => 'Calibri', 'size' => 18, 'bold' => true, 'color' => '005BAA']);
$phpWord->addFontStyle('CoverTag', ['name' => 'Calibri', 'size' => 10, 'bold' => true, 'color' => '0284C7']);
$phpWord->addFontStyle('CoverDesc', ['name' => 'Calibri', 'size' => 11, 'color' => '475569']);

$phpWord->addFontStyle('Heading1Style', ['name' => 'Calibri', 'size' => 16, 'bold' => true, 'color' => '003B73']);
$phpWord->addFontStyle('Heading2Style', ['name' => 'Calibri', 'size' => 13, 'bold' => true, 'color' => '005BAA']);
$phpWord->addFontStyle('Heading3Style', ['name' => 'Calibri', 'size' => 11.5, 'bold' => true, 'color' => '1E293B']);

$phpWord->addFontStyle('BoldText', ['name' => 'Calibri', 'size' => 10.5, 'bold' => true, 'color' => '1E293B']);
$phpWord->addFontStyle('CaptionStyle', ['name' => 'Calibri', 'size' => 9, 'bold' => true, 'italic' => true, 'color' => '475569']);
$phpWord->addFontStyle('CalloutTitle', ['name' => 'Calibri', 'size' => 10, 'bold' => true, 'color' => '003B73']);
$phpWord->addFontStyle('CalloutText', ['name' => 'Calibri', 'size' => 9.5, 'color' => '334155']);

// Paragraph Styles
$phpWord->addParagraphStyle('CenterPara', ['alignment' => Jc::CENTER, 'spaceAfter' => 100]);
$phpWord->addParagraphStyle('JustifyPara', ['alignment' => Jc::BOTH, 'spaceAfter' => 120, 'lineHeight' => 1.2]);
$phpWord->addParagraphStyle('Heading1Para', ['spaceBefore' => 240, 'spaceAfter' => 120, 'keepNext' => true]);
$phpWord->addParagraphStyle('Heading2Para', ['spaceBefore' => 200, 'spaceAfter' => 80, 'keepNext' => true]);
$phpWord->addParagraphStyle('Heading3Para', ['spaceBefore' => 140, 'spaceAfter' => 60, 'keepNext' => true]);
$phpWord->addParagraphStyle('CaptionPara', ['alignment' => Jc::CENTER, 'spaceBefore' => 60, 'spaceAfter' => 160]);

// Table Styles
$tableHeaderStyle = ['bgColor' => '005BAA'];
$tableHeaderFontStyle = ['name' => 'Calibri', 'size' => 9.5, 'bold' => true, 'color' => 'FFFFFF'];
$tableRowEvenStyle = ['bgColor' => 'F8FAFC'];
$tableCellPadding = ['top' => 80, 'bottom' => 80, 'left' => 120, 'right' => 120];

// ==========================================
// 1. COVER PAGE SECTION
// ==========================================
$coverSection = $phpWord->addSection([
    'paperSize' => 'A4',
    'marginTop' => Converter::cmToTwip(2.5),
    'marginBottom' => Converter::cmToTwip(2.5),
    'marginLeft' => Converter::cmToTwip(2.5),
    'marginRight' => Converter::cmToTwip(2.5),
]);

// Logo BI
$logoPath = 'public/images/logo-bi4.png';
if (file_exists($logoPath)) {
    $coverSection->addImage($logoPath, ['width' => 180, 'alignment' => Jc::START]);
}

$coverSection->addTextBreak(2);
$coverSection->addText('BANK INDONESIA', ['size' => 12, 'bold' => true, 'color' => '003B73', 'letterSpacing' => 10]);
$coverSection->addText('KANTOR PERWAKILAN PROVINSI SULAWESI UTARA', ['size' => 10, 'bold' => true, 'color' => '005BAA']);

$coverSection->addTextBreak(3);
$coverSection->addText('DOKUMENTASI RESMI SISTEM', 'CoverTag');
$coverSection->addText('MANUAL BOOK', 'CoverTitle');
$coverSection->addText('SILAKAN', 'CoverSubtitle');
$coverSection->addText('Sistem Informasi Layanan Kantor — Panduan Operasional Terpadu Pengelolaan dan Pemesanan Ruangan Rapat Internal Berbasis Web', 'CoverDesc');

$coverSection->addTextBreak(4);

// Metadata Box Table
$metaTable = $coverSection->addTable([
    'borderSize' => 6,
    'borderColor' => 'CBD5E1',
    'alignment' => JcTable::CENTER,
    'cellMargin' => 100,
]);
$metaTable->addRow();
$c1 = $metaTable->addCell(Converter::cmToTwip(5.5), ['bgColor' => 'F1F5F9']);
$c1->addText('VERSI DOKUMEN', ['size' => 8, 'bold' => true, 'color' => '64748B']);
$c1->addText('Versi 1.0 (Final Rilis)', 'BoldText');

$c2 = $metaTable->addCell(Converter::cmToTwip(5.5), ['bgColor' => 'F1F5F9']);
$c2->addText('TANGGAL PENERBITAN', ['size' => 8, 'bold' => true, 'color' => '64748B']);
$c2->addText('September 2026', 'BoldText');

$c3 = $metaTable->addCell(Converter::cmToTwip(5.5), ['bgColor' => 'F1F5F9']);
$c3->addText('KLASIFIKASI DOKUMEN', ['size' => 8, 'bold' => true, 'color' => '64748B']);
$c3->addText('Internal Bank Indonesia', 'BoldText');


// ==========================================
// 2. MAIN BODY SECTION (WITH HEADER & FOOTER)
// ==========================================
$mainSection = $phpWord->addSection([
    'paperSize' => 'A4',
    'marginTop' => Converter::cmToTwip(2.5),
    'marginBottom' => Converter::cmToTwip(2.5),
    'marginLeft' => Converter::cmToTwip(2.5),
    'marginRight' => Converter::cmToTwip(2.5),
]);

// Header
$header = $mainSection->addHeader();
$headerTable = $header->addTable(['borderSize' => 0, 'alignment' => JcTable::CENTER]);
$headerTable->addRow();
$headerTable->addCell(Converter::cmToTwip(10))->addText('SILAKAN — Sistem Informasi Layanan Kantor', ['size' => 8.5, 'color' => '64748B']);
$headerTable->addCell(Converter::cmToTwip(6.5))->addText('KPwBI Prov. Sulut', ['size' => 8.5, 'color' => '64748B'], ['alignment' => Jc::RIGHT]);

// Footer
$footer = $mainSection->addFooter();
$footerTable = $footer->addTable(['borderSize' => 0, 'alignment' => JcTable::CENTER]);
$footerTable->addRow();
$footerTable->addCell(Converter::cmToTwip(10))->addText('Buku Panduan Penggunaan Sistem v1.0', ['size' => 8.5, 'color' => '64748B']);
$footerCell = $footerTable->addCell(Converter::cmToTwip(6.5));
$footerCell->addPreserveText('Halaman {PAGE} dari {NUMPAGES}', ['size' => 8.5, 'color' => '64748B'], ['alignment' => Jc::RIGHT]);


// ----------------------------------------------------
// LEMBAR IDENTITAS & RIWAYAT PERUBAHAN
// ----------------------------------------------------
$mainSection->addText('LEMBAR IDENTITAS DOKUMEN', 'Heading1Style', 'Heading1Para');

$infoTable = $mainSection->addTable(['borderSize' => 6, 'borderColor' => 'E2E8F0', 'alignment' => JcTable::CENTER]);
$infoRows = [
    ['Judul Dokumen', 'Buku Panduan Penggunaan Sistem (Manual Book) — SILAKAN'],
    ['Nama Sistem', 'SILAKAN (Sistem Informasi Layanan Kantor)'],
    ['Instansi Pemilik', 'Kantor Perwakilan Bank Indonesia Provinsi Sulawesi Utara'],
    ['Alamat Kantor', 'Gedung Kantor Perwakilan BI Prov. Sulut, Jl. 17 Agustus No. 4, Manado'],
    ['Unit Pengelola (Admin)', 'Unit Rumah Tangga & Logistik / Sekretariat Pimpinan (Sarpras)'],
    ['Sasaran Pengguna', 'Seluruh Pegawai Unit Kerja KPwBI Prov. Sulut & Administrator'],
    ['Versi Dokumen', '1.0 (Rilis Resmi 2026)'],
    ['Status Publikasi', 'Final & Disetujui Siap Operasional'],
];

foreach ($infoRows as $row) {
    $infoTable->addRow();
    $infoTable->addCell(Converter::cmToTwip(5), ['bgColor' => 'F8FAFC'])->addText($row[0], 'BoldText');
    $infoTable->addCell(Converter::cmToTwip(11))->addText($row[1]);
}

$mainSection->addTextBreak();
$mainSection->addText('Riwayat Perubahan Dokumen', 'Heading2Style', 'Heading2Para');

$revTable = $mainSection->addTable(['borderSize' => 6, 'borderColor' => 'CBD5E1', 'alignment' => JcTable::CENTER]);
$revTable->addRow();
$revTable->addCell(Converter::cmToTwip(2), $tableHeaderStyle)->addText('Versi', $tableHeaderFontStyle);
$revTable->addCell(Converter::cmToTwip(3.5), $tableHeaderStyle)->addText('Tanggal', $tableHeaderFontStyle);
$revTable->addCell(Converter::cmToTwip(7.5), $tableHeaderStyle)->addText('Deskripsi Perubahan', $tableHeaderFontStyle);
$revTable->addCell(Converter::cmToTwip(3), $tableHeaderStyle)->addText('Penyusun', $tableHeaderFontStyle);

$revTable->addRow();
$revTable->addCell(Converter::cmToTwip(2))->addText('v1.0', 'BoldText');
$revTable->addCell(Converter::cmToTwip(3.5))->addText('02 September 2026');
$revTable->addCell(Converter::cmToTwip(7.5))->addText('Rilis perdana Buku Panduan SILAKAN lengkap mencakup panduan User Unit Kerja, panduan Administrator, Kiosk TV Display, matriks validasi bentrok, serta integrasi WhatsApp Gateway.');
$revTable->addCell(Converter::cmToTwip(3))->addText('Tim Analis & Pengembang SILAKAN');

$mainSection->addTextBreak();
$mainSection->addText('Kata Pengantar', 'Heading2Style', 'Heading2Para');
$mainSection->addText('Puji dan syukur kita panjatkan ke hadirat Tuhan Yang Maha Esa atas terselesaikannya pengembangan dan penyusunan Buku Panduan Penggunaan Sistem (Manual Book) SILAKAN (Sistem Informasi Layanan Kantor) di lingkungan Kantor Perwakilan Bank Indonesia Provinsi Sulawesi Utara (KPwBI Prov. Sulut).', null, 'JustifyPara');
$mainSection->addText('Buku panduan ini disusun sebagai rujukan resmi dan terstandarisasi bagi seluruh insan Bank Indonesia di KPwBI Prov. Sulut dalam memanfaatkan aplikasi SILAKAN. Sistem ini hadir guna mendigitalisasi, memodernisasi, serta mentransparansikan tata kelola sarana dan prasarana rapat internal, menghilangkan potensi tumpang tindih (bentrok) jadwal kegiatan, mempercepat proses persetujuan (approval) oleh pengelola, dan menyediakan informasi ketersediaan ruangan rapat secara waktu-nyata (real-time).', null, 'JustifyPara');
$mainSection->addText('Diharapkan dengan hadirnya buku panduan ini, setiap unit kerja dapat menjalankan prosedur pemesanan sarana kantor dengan efektif, akurat, dan tertib administrasi.', null, 'JustifyPara');

$mainSection->addPageBreak();


// ----------------------------------------------------
// DAFTAR ISI & DAFTAR GAMBAR
// ----------------------------------------------------
$mainSection->addText('DAFTAR ISI', 'Heading1Style', 'Heading1Para');

$tocEntries = [
    ['BAB 1: PENDAHULUAN', '1.1 Latar Belakang & Tujuan, 1.2 Manfaat, 1.3 Ruang Lingkup'],
    ['BAB 2: GAMBARAN UMUM SISTEM', '2.1 Deskripsi Fungsional, 2.2 Arsitektur MVC, 2.3 Matriks Role, 2.4 Sitemap'],
    ['BAB 3: PERSIAPAN PENGGUNA', '3.1 Kebutuhan Hardware, 3.2 Browser, 3.3 Jaringan Intranet, 3.4 Alamat URL'],
    ['BAB 4: AUTENTIKASI & AKUN', '4.1 Halaman Login, 4.2 Prosedur Login, 4.3 Logout, 4.4 Profil, 4.5 Ubah Password'],
    ['BAB 5: PANDUAN PENGGUNA (USER)', '5.1 Dashboard User, 5.2 Formulir Pemesanan, 5.3 Smart Layout, 5.4 Validasi Bentrok, 5.5 Disposisi, 5.6 Riwayat, 5.7 Pembatalan, 5.8 Selesai Awal, 5.9 Kalender, 5.10 Notifikasi'],
    ['BAB 6: PANDUAN ADMINISTRATOR', '6.1 Dashboard Analytics, 6.2 Approval, 6.3 Rejection, 6.4 Live Monitoring, 6.5 Master Ruangan, 6.6 Master Layout, 6.7 Hari Libur & Sync API, 6.8 Manajemen User, 6.9 Laporan Excel/PDF, 6.10 Audit Log'],
    ['BAB 7: TAMPILAN TV MONITOR LOBBY', '7.1 Akses Mode Display (/display), 7.2 Auto Refresh JSON 30 Detik'],
    ['BAB 8: PANDUAN TROUBLESHOOTING', 'Tabel Matriks Kendala Teknis & Solusi Tuntas'],
    ['BAB 9: FREQUENTLY ASKED QUESTIONS (FAQ)', 'Tanya Jawab Praktis Operasional Ruang Rapat'],
    ['BAB 10: GLOSARIUM & PENUTUP', 'Definisi Istilah Teknis & Layanan Helpdesk'],
];

$tocTable = $mainSection->addTable(['borderSize' => 0, 'alignment' => JcTable::CENTER]);
foreach ($tocEntries as $toc) {
    $tocTable->addRow();
    $tocTable->addCell(Converter::cmToTwip(6))->addText($toc[0], 'BoldText');
    $tocTable->addCell(Converter::cmToTwip(10))->addText($toc[1], ['size' => 9.5, 'color' => '475569']);
}

$mainSection->addPageBreak();


// Helper function for adding screenshots with border & caption
function insertScreenshot($section, $filename, $captionNumber, $captionTitle) {
    $path = "public/images/manual/{$filename}";
    if (file_exists($path)) {
        $section->addImage($path, [
            'width' => 460,
            'alignment' => Jc::CENTER,
        ]);
        $section->addText("Gambar {$captionNumber} — {$captionTitle}", 'CaptionStyle', 'CaptionPara');
    }
}

// ----------------------------------------------------
// BAB 1: PENDAHULUAN
// ----------------------------------------------------
$mainSection->addText('BAB 1: PENDAHULUAN', 'Heading1Style', 'Heading1Para');

$mainSection->addText('1.1 Latar Belakang Penerapan Sistem', 'Heading2Style', 'Heading2Para');
$mainSection->addText('Sebagai bank sentral dengan mobilitas koordinasi yang tinggi, Kantor Perwakilan Bank Indonesia Provinsi Sulawesi Utara (KPwBI Prov. Sulut) secara berkala menyelenggarakan rapat koordinasi internal, focus group discussion (FGD), pertemuan bilateral dengan perbankan dan otoritas daerah, diseminasi laporan perekonomian, hingga kegiatan pengembangan UMKM daerah.', null, 'JustifyPara');
$mainSection->addText('Sebelum adanya sistem informasi terintegrasi, pemesanan ruangan rapat dilakukan melalui pengajuan memo fisik atau pesan singkat kepada pengelola sarana prasarana. Metode tersebut kerap menimbulkan risiko jadwal ganda (bentrok), ketiadaan informasi ketersediaan ruangan waktu nyata, serta ketidaksesuaian layout meja-kursi terhadap kemampuan fisik ruangan.', null, 'JustifyPara');
$mainSection->addText('Untuk mengatasi kendala tersebut, dibangunlah SILAKAN (Sistem Informasi Layanan Kantor) sebagai solusi digital modern yang menghubungkan seluruh unit kerja pemohon dengan Administrator Sarana & Prasarana secara langsung dan transparan.', null, 'JustifyPara');

$mainSection->addText('1.2 Tujuan Penerapan SILAKAN', 'Heading2Style', 'Heading2Para');
$mainSection->addListItem('Otomasi Validasi Jadwal Rapat: Mencegah secara mutlak terjadinya jadwal rapat bentrok melalui algoritma pendeteksi konflik waktu (WITA).', 0, null, null, 'JustifyPara');
$mainSection->addListItem('Transparansi Ketersediaan Ruangan: Menyediakan kalender visual terpusat yang dapat diakses oleh seluruh pegawai unit kerja.', 0, null, null, 'JustifyPara');
$mainSection->addListItem('Standarisasi Layout Ruangan: Menghubungkan secara dinamis layout yang diizinkan untuk masing-masing ruangan rapat.', 0, null, null, 'JustifyPara');
$mainSection->addListItem('Percepatan Alur Persetujuan (Approval): Mempermudah Admin Sarpras dalam meninjau berkas disposisi digital dan memberikan persetujuan atau penolakan tertulis.', 0, null, null, 'JustifyPara');
$mainSection->addListItem('Penyebaran Informasi Publik: Mengintegrasikan jadwal rapat harian ke layar TV Monitor Lobby (Kiosk Mode) guna memudahkan para tamu dan peserta rapat.', 0, null, null, 'JustifyPara');
$mainSection->addListItem('Notifikasi Cepat WhatsApp & Web: Memberikan pemberitahuan otomatis ke WhatsApp penanggung jawab kegiatan saat pemesanan disetujui atau ditolak.', 0, null, null, 'JustifyPara');

$mainSection->addText('1.3 Manfaat bagi Pengguna & Manajemen', 'Heading2Style', 'Heading2Para');
$benefitTable = $mainSection->addTable(['borderSize' => 6, 'borderColor' => 'CBD5E1', 'alignment' => JcTable::CENTER]);
$benefitTable->addRow();
$benefitTable->addCell(Converter::cmToTwip(4.5), $tableHeaderStyle)->addText('Penerima Manfaat', $tableHeaderFontStyle);
$benefitTable->addCell(Converter::cmToTwip(11.5), $tableHeaderStyle)->addText('Manfaat yang Diperoleh', $tableHeaderFontStyle);

$benefitTable->addRow();
$benefitTable->addCell(Converter::cmToTwip(4.5))->addText('Pegawai Unit Kerja', 'BoldText');
$benefitTable->addCell(Converter::cmToTwip(11.5))->addText('Kemudahan mengajukan ruangan kapan saja secara mandiri; kepastian status persetujuan instan via WhatsApp; visibilitas kalender interaktif; dan fitur pengosongan ruangan lebih awal.');

$benefitTable->addRow();
$benefitTable->addCell(Converter::cmToTwip(4.5))->addText('Admin Sarpras / Sekpr', 'BoldText');
$benefitTable->addCell(Converter::cmToTwip(11.5))->addText('Peninjauan berkas disposisi digital tanpa kertas; pemantauan live countdown rapat; pengelolaan master ruangan dan layout; serta rekapitulasi laporan resmi Excel dan PDF.');

$mainSection->addText('1.4 Ruang Lingkup Sistem', 'Heading2Style', 'Heading2Para');
$mainSection->addText('Sistem SILAKAN versi 1.0 mencakup 8 pilar modul fungsional: (1) Autentikasi & Otorisasi RBAC, (2) Pemesanan Ruangan & Validasi Bentrok WITA, (3) Verifikasi & Approval Berkas, (4) Live Monitoring & Countdown Timer, (5) Kalender Ruangan Terpadu Libur Nasional, (6) Notifikasi Multi-Saluran In-App & WhatsApp, (7) Master Data Terpadu, dan (8) TV Display Kiosk Lobby & Pelaporan Eksekutif.', null, 'JustifyPara');

$mainSection->addPageBreak();


// ----------------------------------------------------
// BAB 2: GAMBARAN UMUM SISTEM & ARSITEKTUR
// ----------------------------------------------------
$mainSection->addText('BAB 2: GAMBARAN UMUM SISTEM', 'Heading1Style', 'Heading1Para');

$mainSection->addText('2.1 Deskripsi Fungsional Sistem', 'Heading2Style', 'Heading2Para');
$mainSection->addText('SILAKAN adalah aplikasi berbasis web yang responsif, beroperasi di jaringan intranet internal Bank Indonesia, dan dirancang dengan standar visual korporat bank sentral (Biru BI #005baa, Navy #003b73, dan Gold #b8972a).', null, 'JustifyPara');

$mainSection->addText('2.2 Arsitektur Sistem', 'Heading2Style', 'Heading2Para');
$mainSection->addText('SILAKAN dibangun menggunakan arsitektur Model-View-Controller (MVC) pada framework Laravel 12, PHP 8.4, dan basis data MySQL. Alur kerja sistem memisahkan lapisan antarmuka pengguna (View), pengarah rute (Controller), logika bisnis (Action Layer: CreatePemesananAction, ApprovePemesananAction, RejectPemesananAction, CancelPemesananAction), serta lapisan model Eloquent ORM.', null, 'JustifyPara');

$mainSection->addText('2.3 Matriks Role dan Hak Akses Pengguna', 'Heading2Style', 'Heading2Para');
$roleTable = $mainSection->addTable(['borderSize' => 6, 'borderColor' => 'CBD5E1', 'alignment' => JcTable::CENTER]);
$roleTable->addRow();
$roleTable->addCell(Converter::cmToTwip(3), $tableHeaderStyle)->addText('Role', $tableHeaderFontStyle);
$roleTable->addCell(Converter::cmToTwip(4), $tableHeaderStyle)->addText('Deskripsi Pengguna', $tableHeaderFontStyle);
$roleTable->addCell(Converter::cmToTwip(5.5), $tableHeaderStyle)->addText('Hak Akses Utama', $tableHeaderFontStyle);
$roleTable->addCell(Converter::cmToTwip(3.5), $tableHeaderStyle)->addText('Batasan Akses', $tableHeaderFontStyle);

$roleTable->addRow();
$roleTable->addCell(Converter::cmToTwip(3))->addText('User (Unit Kerja)', 'BoldText');
$roleTable->addCell(Converter::cmToTwip(4))->addText('Pegawai perwakilan unit internal KPwBI Sulut.');
$roleTable->addCell(Converter::cmToTwip(5.5))->addText('Input pemesanan, riwayat, pembatalan, selesai awal, kalender, notifikasi, ubah profil.');
$roleTable->addCell(Converter::cmToTwip(3.5))->addText('Tidak dapat akses approval, master data, dan laporan.');

$roleTable->addRow();
$roleTable->addCell(Converter::cmToTwip(3))->addText('Administrator', 'BoldText');
$roleTable->addCell(Converter::cmToTwip(4))->addText('Pengelola Sarpras / Rumah Tangga / Sekpr.');
$roleTable->addCell(Converter::cmToTwip(5.5))->addText('Dashboard analytics, approve/reject, live monitoring, master data, user, laporan, audit log.');
$roleTable->addCell(Converter::cmToTwip(3.5))->addText('Proteksi self-delete akun aktif.');

$roleTable->addRow();
$roleTable->addCell(Converter::cmToTwip(3))->addText('Kiosk (TV Lobby)', 'BoldText');
$roleTable->addCell(Converter::cmToTwip(4))->addText('Layar monitor Smart TV Lobby kantor.');
$roleTable->addCell(Converter::cmToTwip(5.5))->addText('Jadwal rapat hari ini, status ruangan, auto refresh 30 detik.');
$roleTable->addCell(Converter::cmToTwip(3.5))->addText('Mode display publik tanpa login.');

$mainSection->addPageBreak();


// ----------------------------------------------------
// BAB 3: PERSIAPAN PENGGUNA
// ----------------------------------------------------
$mainSection->addText('BAB 3: PERSIAPAN PENGGUNA', 'Heading1Style', 'Heading1Para');

$mainSection->addText('3.1 Kebutuhan Perangkat Keras & Monitor', 'Heading2Style', 'Heading2Para');
$mainSection->addText('SILAKAN dapat dioperasikan menggunakan PC desktop, laptop kerja, tablet, maupun smartphone dengan spesifikasi minimal RAM 2 GB dan resolusi layar 1366 x 768 piksel (Disarankan Full HD 1920 x 1080 piksel). Layar TV Display di lobby menggunakan resolusi 1080p dengan rasio 16:9.', null, 'JustifyPara');

$mainSection->addText('3.2 Peramban (Browser) yang Direkomendasikan', 'Heading2Style', 'Heading2Para');
$mainSection->addText('Sistem bekerja optimal pada Google Chrome (Versi 100+), Microsoft Edge (Versi 100+), Mozilla Firefox (Versi 100+), dan Safari (Versi 15+). Tidak disarankan menggunakan Internet Explorer.', null, 'JustifyPara');

$mainSection->addText('3.3 Koneksi Jaringan & Intranet Bank Indonesia', 'Heading2Style', 'Heading2Para');
$mainSection->addText('Aplikasi diakses melalui jaringan intranet kantor KPwBI Prov. Sulut. Jika mengakses di luar kantor (WFH / Dinas), pastikan koneksi VPN internal Bank Indonesia telah terhubung.', null, 'JustifyPara');

$mainSection->addText('3.4 Akun Pengguna & Alamat URL Akses', 'Heading2Style', 'Heading2Para');
$mainSection->addListItem('Alamat URL Aplikasi: http://localhost:8000 (atau IP Server Lokal BI)', 0, null, null, 'JustifyPara');
$mainSection->addListItem('Alamat URL TV Display Lobby: http://localhost:8000/display (atau /kiosk)', 0, null, null, 'JustifyPara');
$mainSection->addListItem('Akun Unit: Menggunakan username unit masing-masing (contoh: uk_kpwbisulut, sp_kpwbisulut, admin)', 0, null, null, 'JustifyPara');

$mainSection->addPageBreak();


// ----------------------------------------------------
// BAB 4: AUTENTIKASI DAN PENGELOLAAN AKUN
// ----------------------------------------------------
$mainSection->addText('BAB 4: AUTENTIKASI DAN PENGELOLAAN AKUN', 'Heading1Style', 'Heading1Para');

$mainSection->addText('4.1 Membuka Halaman Login & 4.2 Prosedur Masuk', 'Heading2Style', 'Heading2Para');
$mainSection->addText('Buka peramban web dan arahkan ke alamat URL SILAKAN. Sistem akan menampilkan formulir login masuk ber-KOP resmi Bank Indonesia KPwBI Prov. Sulut.', null, 'JustifyPara');

insertScreenshot($mainSection, '01_login_page.png', '4.1', 'Halaman Form Login Masuk Sistem SILAKAN');

$mainSection->addText('Langkah Login:', 'BoldText');
$mainSection->addListItem('Masukkan Username ID resmi unit kerja Anda (contoh: uk_kpwbisulut) pada kolom Username / Email.', 0, null, null, 'JustifyPara');
$mainSection->addListItem('Masukkan Password akun Anda pada kolom Password. Gunakan ikon mata untuk melihat karakter.', 0, null, null, 'JustifyPara');
$mainSection->addListItem('Klik tombol biru Masuk. Sistem akan memvalidasi data dan mengarahkan ke dashboard.', 0, null, null, 'JustifyPara');

$mainSection->addText('4.3 Prosedur Keluar Sistem (Logout Aman)', 'Heading2Style', 'Heading2Para');
$mainSection->addText('Untuk keluar, buka panel navigasi sidebar kiri, gulir ke bagian paling bawah, lalu klik tombol merah Keluar. Sesi browser akan dihapus dan diarahkan kembali ke halaman login.', null, 'JustifyPara');

$mainSection->addText('4.4 Pengelolaan Profil Pengguna & Nomor WhatsApp', 'Heading2Style', 'Heading2Para');
$mainSection->addText('Klik menu Profil pada sidebar kiri untuk memperbarui email dan nomor WhatsApp aktif penanggung jawab unit (format: 081234567890). Nomor ini menjadi rujukan default penerimaan pesan konfirmasi WhatsApp otomatis.', null, 'JustifyPara');

insertScreenshot($mainSection, '10_profil_user.png', '4.2', 'Halaman Pengaturan Profil Saya & Keamanan Password');

$mainSection->addText('4.5 Penggantian Password Akun Unit', 'Heading2Style', 'Heading2Para');
$mainSection->addText('Pada halaman Profil, masukkan Kata Sandi Saat Ini, Kata Sandi Baru (minimal 8 karakter kombinasi), dan Konfirmasi Kata Sandi Baru, lalu klik Perbarui Password.', null, 'JustifyPara');

$mainSection->addPageBreak();


// ----------------------------------------------------
// BAB 5: PANDUAN PENGGUNA (USER / UNIT KERJA)
// ----------------------------------------------------
$mainSection->addText('BAB 5: PANDUAN PENGGUNA (USER / UNIT KERJA)', 'Heading1Style', 'Heading1Para');

$mainSection->addText('5.1 Memahami Antarmuka Dashboard User', 'Heading2Style', 'Heading2Para');
$mainSection->addText('Dashboard User menampilkan ringkasan metrik pemesanan unit, daftar rapat yang sedang aktif berlangsung hari ini, agenda rapat mendatang, dan permohonan terkini yang dibuat oleh unit Anda.', null, 'JustifyPara');

insertScreenshot($mainSection, '02_dashboard_user.png', '5.1', 'Tampilan Halaman Dashboard Pengguna (Unit Kerja)');

$mainSection->addText('5.2 Tata Cara Membuat Pemesanan Ruangan Baru', 'Heading2Style', 'Heading2Para');
$mainSection->addText('Klik menu Pemesanan pada sidebar kiri. Lengkapi seluruh kolom formulir peminjaman ruangan:', null, 'JustifyPara');

insertScreenshot($mainSection, '03_form_pemesanan.png', '5.2', 'Formulir Pembuatan Pengajuan Pemesanan Ruangan Baru');

$mainSection->addText('Panduan Isian Formulir:', 'BoldText');
$mainSection->addListItem('Ruangan Rapat: Pilih salah satu dari 11 ruangan rapat di KPwBI Sulut (Tondano, Bunaken, Tomohon, Klabat, Lokon, dll).', 0, null, null, 'JustifyPara');
$mainSection->addListItem('Layout Ruangan: Pilihan tata letak meja-kursi yang didukung oleh ruangan terpilih.', 0, null, null, 'JustifyPara');
$mainSection->addListItem('Tanggal Kegiatan: Tanggal dilaksanakannya rapat.', 0, null, null, 'JustifyPara');
$mainSection->addListItem('Waktu Mulai & Selesai: Jam pelaksanaan dalam zona WITA (contoh: 09:00 s/d 11:30 WITA).', 0, null, null, 'JustifyPara');
$mainSection->addListItem('Judul Kegiatan: Nama lengkap agenda rapat / acara.', 0, null, null, 'JustifyPara');
$mainSection->addListItem('PIC Kegiatan & Jenis PIC: Nama penanggung jawab dan kategori Organik / Non Organik.', 0, null, null, 'JustifyPara');
$mainSection->addListItem('Nomor WhatsApp PIC: Nomor aktif untuk pengiriman notifikasi persetujuan instan.', 0, null, null, 'JustifyPara');
$mainSection->addListItem('Jumlah Tamu: Perkiraan peserta (divalidasi terhadap kapasitas maksimal ruangan).', 0, null, null, 'JustifyPara');
$mainSection->addListItem('Unggah Disposisi: Lampiran berkas lembar disposisi pimpinan (PDF, JPG, PNG maks. 5 MB).', 0, null, null, 'JustifyPara');

$mainSection->addPageBreak();

$mainSection->addText('5.3 Pemilihan Ruangan & Smart Layout Filtering Dinamis', 'Heading2Style', 'Heading2Para');
$mainSection->addText('SILAKAN menerapkan Smart Layout Filtering secara otomatis: ruangan fleksibel (seperti Tondano) menampilkan pilihan layout U-Shape, Classroom, Teater, Round Table; sedangkan ruangan konfigurasi tetap (seperti Bunaken atau Linow) menampilkan keterangan otomatis "-- Tidak ada layout khusus untuk ruangan ini --".', null, 'JustifyPara');

insertScreenshot($mainSection, '04_layout_filtering.png', '5.3a', 'Dropdown Layout Ruangan Tondano (Mendukung Layout Dinamis)');
insertScreenshot($mainSection, '05_dropdown_no_layout.png', '5.3b', 'Dropdown Layout Ruangan Bunaken (Layout Standar / Tetap)');

$mainSection->addText('5.4 Validasi Bentrok Jadwal, Tanggal/Waktu Lampau, & Hari Libur', 'Heading2Style', 'Heading2Para');
$mainSection->addText('Sistem secara otomatis menghitung bentrok jam rapat (WITA) terhadap pemesanan yang telah disetujui sebelumnya. Selain itu, sistem secara real-time menolak pemilihan tanggal dan waktu yang sudah lewat (termasuk jam mulai yang telah berlalu untuk hari ini). Jika bentrok atau waktu lampau, kotak peringatan merah muncul dan formulir tidak dapat dikirim. Jika tanggal bertepatan dengan Hari Libur Nasional / Cuti Bersama, sistem memberikan catatan informasi khusus.', null, 'JustifyPara');

$mainSection->addText('5.6 Memantau Riwayat & Status Pemesanan', 'Heading2Style', 'Heading2Para');
$mainSection->addText('Menu Riwayat menampilkan daftar seluruh permohonan unit Anda beserta status badge indikator: PENDING (Kuning), DISETUJUI (Hijau), SELESAI (Biru - kegiatan telah berakhir), DITOLAK (Merah), dan CANCEL (Abu-abu). Agenda yang telah berstatus Selesai otomatis diarsipkan dari Kegiatan Mendatang.', null, 'JustifyPara');

insertScreenshot($mainSection, '06_riwayat_pemesanan.png', '5.4', 'Halaman Riwayat Pemesanan Unit Kerja & Status Badge');

$mainSection->addPageBreak();

$mainSection->addText('5.7 Memeriksa Rincian Detail Pemesanan', 'Heading2Style', 'Heading2Para');
$mainSection->addText('Klik ikon Detail pada tabel riwayat untuk membuka informasi lengkap pemesanan, melihat berkas disposisi yang diunggah, serta opsi tindakan pembatalan atau selesai awal.', null, 'JustifyPara');

insertScreenshot($mainSection, '07_detail_pemesanan_user.png', '5.5', 'Halaman Rincian Detail Pemesanan Ruangan Rapat');

$mainSection->addText('5.8 Pembatalan Pemesanan & 5.9 Fitur Selesai Lebih Awal (Early Release)', 'Heading2Style', 'Heading2Para');
$mainSection->addListItem('Batalkan Pemesanan: Klik tombol merah Batalkan Pemesanan pada bagian bawah halaman rincian untuk membebaskan ruangan jika agenda batal.', 0, null, null, 'JustifyPara');
$mainSection->addListItem('Selesai Lebih Awal: Jika rapat Anda selesai lebih cepat dari jam rencana, klik tombol hijau Selesaikan Rapat Sekarang. Jam selesai seketika diperbarui ke jam saat ini, dan ruangan di TV Lobby langsung berstatus kosong.', 0, null, null, 'JustifyPara');

$mainSection->addText('5.10 Pemanfaatan Kalender Ruangan Interaktif', 'Heading2Style', 'Heading2Para');
$mainSection->addText('Menu Kalender Ruangan menyajikan visualisasi seluruh jadwal pemakaian ruangan rapat KPwBI Sulut terintegrasi dengan penanda warna: Biru BI (Rapat Disetujui), Merah (Libur Nasional), Kuning (Cuti Bersama), dan Biru Muda (Libur Internal BI). Klik pada agenda rapat untuk melihat modal popup detail acara.', null, 'JustifyPara');

insertScreenshot($mainSection, '08_kalender_ruangan.png', '5.6', 'Kalender Ruangan Interaktif Terintegrasi Libur Nasional');

$mainSection->addText('5.11 Pengelolaan Notifikasi In-App', 'Heading2Style', 'Heading2Para');
$mainSection->addText('Menu Notifikasi menampilkan riwayat pemberitahuan status pengajuan. Anda dapat mengeklik notifikasi untuk langsung menuju detail pengajuan, menandai semua dibaca, menghapus notifikasi satuan, atau menghapus seluruh riwayat pesan.', null, 'JustifyPara');

insertScreenshot($mainSection, '09_halaman_notifikasi.png', '5.7', 'Halaman Notifikasi In-App & Fitur Pembersihan Pesan');

$mainSection->addPageBreak();


// ----------------------------------------------------
// BAB 6: PANDUAN ADMINISTRATOR (ADMIN SARPRAS)
// ----------------------------------------------------
$mainSection->addText('BAB 6: PANDUAN ADMINISTRATOR (ADMIN SARPRAS)', 'Heading1Style', 'Heading1Para');

$mainSection->addText('6.1 Dashboard Analytics Administrator', 'Heading2Style', 'Heading2Para');
$mainSection->addText('Admin disajikan dashboard analitik yang memuat grafik tren pemesanan 6 bulan terakhir, bar chart ruangan terpopuler, distribusi pemakaian per unit kerja, dan daftar antrean permohonan waiting list.', null, 'JustifyPara');

insertScreenshot($mainSection, '11_dashboard_admin.png', '6.1', 'Dashboard Analytics Administrator & Visualisasi Diagram');

$mainSection->addText('6.2 Alur Persetujuan (Approval) Pemesanan Ruangan', 'Heading2Style', 'Heading2Para');
$mainSection->addText('Buka menu Pemesanan Ruangan pada sidebar Admin. Pada tab Pending, klik tombol Review Approval pada pengajuan yang masuk.', null, 'JustifyPara');

insertScreenshot($mainSection, '12_approval_index.png', '6.2', 'Halaman Daftar Approval Pemesanan dengan Tab Filter');

insertScreenshot($mainSection, '13_approval_review.png', '6.3', 'Halaman Review Approval Detail & Pemeriksaan Berkas Disposisi');

$mainSection->addText('Periksa kelayakan acara dan berkas disposisi terlampir. Klik tombol hijau Setujui Pemesanan. Sistem secara otomatis memperbarui status menjadi Disetujui dan mengirimkan pesan WhatsApp konfirmasi resmi kepada nomor PIC kegiatan.', null, 'JustifyPara');

$mainSection->addText('6.2b Penjadwalan Rapat Mandiri oleh Administrator (Otomatis Disetujui)', 'Heading2Style', 'Heading2Para');
$mainSection->addText('Administrator dapat menjadwalkan rapat secara langsung melalui tombol "+ Tambah Rapat" di header Dashboard maupun halaman Pemesanan Ruangan. Rapat seketika berstatus Disetujui tanpa antrean approval, aktif di kalender, dan langsung tayang di TV Lobby. Admin dapat memilih penyelenggara sebagai Administrator atau unit kerja terkait, dengan lampiran disposisi yang bersifat opsional.', null, 'JustifyPara');

$mainSection->addPageBreak();

$mainSection->addText('6.3 Alur Penolakan (Rejection) Disertai Alasan', 'Heading2Style', 'Heading2Para');
$mainSection->addText('Jika pengajuan tidak dapat disetujui, klik tombol merah Tolak Pemesanan. Ketikkan alasan penolakan pada modal form (contoh: Ruangan dialihkan untuk kegiatan pimpinan), lalu klik Kirim Penolakan. Status berubah menjadi Ditolak dan alasan tertulis terkirim otomatis ke WhatsApp PIC.', null, 'JustifyPara');

insertScreenshot($mainSection, '13b_modal_penolakan.png', '6.4', 'Modal Form Input Alasan Penolakan Pemesanan Ruangan');

$mainSection->addText('6.4 Pemantauan Live Monitoring & Countdown Timer', 'Heading2Style', 'Heading2Para');
$mainSection->addText('Buka menu Kegiatan Berlangsung untuk memantau ruangan rapat yang sedang aktif digunakan saat ini. Sistem menyediakan Live Countdown Timer yang menghitung mundur sisa waktu rapat secara akurat.', null, 'JustifyPara');

insertScreenshot($mainSection, '14_live_monitoring.png', '6.5', 'Halaman Live Monitoring Ruangan Aktif & Countdown Timer Real-Time');

$mainSection->addPageBreak();

$mainSection->addText('6.5 Kelola Master Data Ruangan & Pivot Layout', 'Heading2Style', 'Heading2Para');
$mainSection->addText('Buka menu Master Data -> Data Ruangan untuk menambah ruangan baru, mengedit nama, lokasi lantai, kapasitas hadirin, dan status operasional.', null, 'JustifyPara');

insertScreenshot($mainSection, '15_master_ruangan.png', '6.6', 'Tabel Master Data Ruangan Rapat KPwBI Prov. Sulut');

insertScreenshot($mainSection, '16_form_ruangan_create.png', '6.7', 'Form Input Ruangan dengan Komponen Rounded Badge Checkbox Layout');

$mainSection->addText('6.6 Kelola Master Data Layout Ruangan', 'Heading2Style', 'Heading2Para');
$mainSection->addText('Buka menu Master Data -> Data Layout untuk mengelola variasi tata letak meja-kursi (U-Shape, Classroom, Teater, Interview Set, Transit Room, Round Table).', null, 'JustifyPara');

insertScreenshot($mainSection, '17_master_layout.png', '6.8', 'Halaman Pengelolaan Master Layout Ruangan');

$mainSection->addPageBreak();

$mainSection->addText('6.7 Kelola Master Hari Libur & Sinkronisasi API Nasional', 'Heading2Style', 'Heading2Para');
$mainSection->addText('Buka menu Master Data -> Hari Libur. Klik tombol Sync API Libur Nasional untuk menarik daftar hari libur resmi pemerintah secara otomatis, atau klik Tambah Hari Libur untuk mendaftarkan hari libur internal Bank Indonesia secara manual.', null, 'JustifyPara');

insertScreenshot($mainSection, '18_master_hari_libur.png', '6.9', 'Halaman Master Hari Libur Terintegrasi Tombol Sync API');

$mainSection->addText('6.8 Manajemen Akun Pengguna & Proteksi Administrator', 'Heading2Style', 'Heading2Para');
$mainSection->addText('Buka menu Master Data -> Data User. Halaman ini memisahkan Akun Administrator pada kotak khusus di bagian atas dan Daftar Akun Unit Kerja pada tabel di bagian bawah. Akun Admin dilindungi penuh oleh sistem (tidak dapat dihapus dan role administrator dikunci permanen). Admin juga dapat melihat dan menyalin kata sandi user unit kerja secara transparan melalui fitur Lihat Password (ikon mata dan tombol salin).', null, 'JustifyPara');

insertScreenshot($mainSection, '19_master_user.png', '6.10', 'Halaman Manajemen Akun Pengguna & Proteksi Akun Aktif');

$mainSection->addPageBreak();

$mainSection->addText('6.9 Rekapitulasi Laporan, Ekspor Excel & Cetak PDF KOP BI', 'Heading2Style', 'Heading2Para');
$mainSection->addText('Buka menu Sistem -> Laporan. Admin dapat memfilter laporan berdasarkan rentang tanggal, ruangan, unit kerja, jenis PIC, dan status. Klik Export Excel untuk mengunduh berkas spreadsheet .xlsx, atau klik Cetak Laporan / PDF untuk membuka dokumen siap cetak ber-KOP resmi Bank Indonesia.', null, 'JustifyPara');

insertScreenshot($mainSection, '20_laporan_filter.png', '6.11', 'Halaman Filter Laporan Pemakaian Ruangan Multikriteria');

insertScreenshot($mainSection, '21_laporan_cetak_pdf.png', '6.12', 'Pratinjau Dokumen Cetak Laporan PDF Ber-KOP Resmi Bank Indonesia');

$mainSection->addText('6.10 Pemantauan Rekam Jejak Terpusat (Audit Log)', 'Heading2Style', 'Heading2Para');
$mainSection->addText('Menu Sistem -> Audit Log merekam seluruh tindakan pengguna dan administrator mencakup Nama User, Aksi, Modul, Alamat IP, Keterangan Aktivitas, dan Timestamp WITA secara mendalam.', null, 'JustifyPara');

insertScreenshot($mainSection, '22_audit_log.png', '6.13', 'Halaman Audit Log Sistem Pemantau Rekam Jejak Aktivitas');

$mainSection->addPageBreak();


// ----------------------------------------------------
// BAB 7: TAMPILAN TV MONITOR LOBBY (KIOSK MODE)
// ----------------------------------------------------
$mainSection->addText('BAB 7: TAMPILAN TV MONITOR LOBBY (KIOSK MODE)', 'Heading1Style', 'Heading1Para');

$mainSection->addText('7.1 Mengakses Mode Layar TV Lobby (/display)', 'Heading2Style', 'Heading2Para');
$mainSection->addText('Buka browser pada Smart TV Lobby Kantor dan akses alamat URL http://localhost:8000/display (atau /kiosk). Tekan F11 pada keyboard untuk mengaktifkan tampilan layar penuh (Full Screen).', null, 'JustifyPara');

insertScreenshot($mainSection, '23_tv_display_kiosk.png', '7.1', 'Tampilan Layar TV Monitor Lobby (Kiosk Mode) Bank Indonesia Sulut');

$mainSection->addText('7.2 Fitur Auto Refresh Real-Time Latar Belakang', 'Heading2Style', 'Heading2Para');
$mainSection->addText('Layar TV Display mengimplementasikan Background Polling via endpoint JSON /api/display-data setiap 30 detik tanpa reload browser (zero-flicker). Status ruangan otomatis berganti antara SEDANG DIGUNAKAN dan TERSEDIA, dilengkapi jam digital WITA dan tema gelap ber-KOP BI yang elegan.', null, 'JustifyPara');

$mainSection->addPageBreak();


// ----------------------------------------------------
// BAB 8: PANDUAN TROUBLESHOOTING
// ----------------------------------------------------
$mainSection->addText('BAB 8: PANDUAN TROUBLESHOOTING', 'Heading1Style', 'Heading1Para');

$troubleTable = $mainSection->addTable(['borderSize' => 6, 'borderColor' => 'CBD5E1', 'alignment' => JcTable::CENTER]);
$troubleTable->addRow();
$troubleTable->addCell(Converter::cmToTwip(4), $tableHeaderStyle)->addText('Gejala / Kendala', $tableHeaderFontStyle);
$troubleTable->addCell(Converter::cmToTwip(5), $tableHeaderStyle)->addText('Kemungkinan Penyebab', $tableHeaderFontStyle);
$troubleTable->addCell(Converter::cmToTwip(7), $tableHeaderStyle)->addText('Langkah Solusi Penyelesaian', $tableHeaderFontStyle);

$troubleRows = [
    ['Gagal Login', 'Salah username/password atau Caps Lock aktif.', 'Periksa tombol Caps Lock. Pastikan username sesuai (contoh: uk_kpwbisulut). Hubungi Admin Sarpras jika perlu reset password.'],
    ['Peringatan "Jadwal Bentrok"', 'Ruangan telah disetujui untuk kegiatan unit lain pada jam yang bersinggungan.', 'Buka menu Kalender Ruangan untuk memeriksa jam rapat yang ada. Pilih jam sebelum/sesudah rapat tersebut atau gunakan ruangan lain.'],
    ['Kapasitas Tamu Ditolak', 'Jumlah tamu melebihi kapasitas ruangan.', 'Sesuaikan jumlah peserta atau pilih ruangan yang lebih besar (contoh: Balai Kerapuan / Tondano untuk > 50 orang).'],
    ['Layout "Tidak Ada Layout Khusus"', 'Ruangan memiliki meja-kursi permanen (seperti Bunaken atau Linow).', 'Kondisi ini normal sistem; lanjutkan mengisi form dan tuliskan kebutuhan tambahan pada catatan user.'],
    ['File Disposisi Gagal Diunggah', 'Ukuran file > 5 MB atau format tidak didukung.', 'Konversikan dokumen menjadi format PDF atau kompres ukuran gambar (JPG/PNG) di bawah 5 MB.'],
    ['Notifikasi WA Tidak Terkirim', 'Nomor salah format atau saldo token gateway habis.', 'Pastikan nomor berawalan 08... atau 628... dan laporkan ke teknisi IT untuk pengecekan server gateway.'],
    ['Admin Tidak Bisa Hapus Akun', 'Akun yang ingin dihapus adalah akun admin yang sedang aktif login.', 'Sistem memproteksi akun aktif demi keamanan sesi. Gunakan akun admin lain jika ingin menghapusnya.'],
];

foreach ($troubleRows as $tRow) {
    $troubleTable->addRow();
    $troubleTable->addCell(Converter::cmToTwip(4))->addText($tRow[0], 'BoldText');
    $troubleTable->addCell(Converter::cmToTwip(5))->addText($tRow[1]);
    $troubleTable->addCell(Converter::cmToTwip(7))->addText($tRow[2]);
}

$mainSection->addPageBreak();


// ----------------------------------------------------
// BAB 9 & 10: FAQ & GLOSARIUM
// ----------------------------------------------------
$mainSection->addText('BAB 9: FREQUENTLY ASKED QUESTIONS (FAQ)', 'Heading1Style', 'Heading1Para');

$faqs = [
    ['Kapan batas waktu pengajuan peminjaman ruangan rapat?', 'Disarankan mengajukan selambat-lambatnya 1 (satu) hari kerja sebelum acara agar pengelola sarpras memiliki waktu yang cukup untuk menyiapkan kebersihan dan penataan layout ruangan.'],
    ['Apakah pengajuan yang sudah disetujui bisa diedit jadwalnya?', 'Pengajuan yang telah disetujui tidak dapat diedit langsung demi mencegah konflik jadwal. Batalkan pemesanan tersebut lalu ajukan permohonan baru dengan jam yang diinginkan.'],
    ['Siapa yang dapat melihat dokumen disposisi yang diunggah?', 'Berkas disposisi bersifat rahasia internal dan hanya dapat dilihat oleh unit kerja pemohon dan Administrator Sarpras yang memverifikasi.'],
    ['Apa yang harus dilakukan jika rapat selesai lebih cepat?', 'Buka menu Riwayat -> Detail Pemesanan, lalu klik tombol Selesaikan Rapat Sekarang agar ruangan seketika berstatus kosong di layar TV lobby dan siap dipakai unit lain.'],
];

foreach ($faqs as $faq) {
    $mainSection->addText('Q: ' . $faq[0], 'BoldText');
    $mainSection->addText('A: ' . $faq[1], null, 'JustifyPara');
    $mainSection->addTextBreak();
}

$mainSection->addText('BAB 10: GLOSARIUM & PUSAT LAYANAN BANTUAN', 'Heading1Style', 'Heading1Para');

$glossary = [
    ['SILAKAN', 'Sistem Informasi Layanan Kantor — platform digital internal KPwBI Prov. Sulut.'],
    ['WITA', 'Waktu Indonesia Tengah (UTC+8) — zona waktu resmi operasional sistem di Manado.'],
    ['Smart Layout Filtering', 'Penyaringan cerdas opsi layout hanya sesuai kemampuan fisik ruangan terpilih.'],
    ['Early Release', 'Fitur penyelesaian rapat lebih awal untuk membebaskan ruangan seketika.'],
    ['Kiosk Mode', 'Mode penayangan informasi layar penuh pada TV lobby tanpa autentikasi login.'],
    ['Audit Log', 'Buku catatan digital yang merekam seluruh jejak aktivitas pengguna demi keamanan.'],
];

$glossaryTable = $mainSection->addTable(['borderSize' => 6, 'borderColor' => 'CBD5E1', 'alignment' => JcTable::CENTER]);
$glossaryTable->addRow();
$glossaryTable->addCell(Converter::cmToTwip(4.5), $tableHeaderStyle)->addText('Istilah', $tableHeaderFontStyle);
$glossaryTable->addCell(Converter::cmToTwip(11.5), $tableHeaderStyle)->addText('Penjelasan Sederhana', $tableHeaderFontStyle);

foreach ($glossary as $g) {
    $glossaryTable->addRow();
    $glossaryTable->addCell(Converter::cmToTwip(4.5))->addText($g[0], 'BoldText');
    $glossaryTable->addCell(Converter::cmToTwip(11.5))->addText($g[1]);
}

$mainSection->addTextBreak();
$mainSection->addText('Pusat Layanan Bantuan (Helpdesk Sarpras):', 'BoldText');
$mainSection->addText('Unit Pengelola: Unit Rumah Tangga & Logistik / Sekpr KPwBI Prov. Sulut');
$mainSection->addText('Lokasi: Gedung Kantor Perwakilan Bank Indonesia Prov. Sulut, Lantai 2, Manado');
$mainSection->addText('Email: sarpras_kpwbisulut@bi.go.id | Ext Telepon Internal: 8200 / 8201');

// Save Word Documents
$outputFileDocx = 'Manual_Book_SILAKAN_v1.0.docx';
$publicOutputDocx = 'public/Manual_Book_SILAKAN_v1.0.docx';
$legacyDocx = 'MANUAL_BOOK_SILAKAN.docx';
$legacyDoc = 'MANUAL_BOOK_SILAKAN.doc';

$xmlWriter = IOFactory::createWriter($phpWord, 'Word2007');
$xmlWriter->save($outputFileDocx);
copy($outputFileDocx, $publicOutputDocx);
copy($outputFileDocx, $legacyDocx);

echo "Word documents generated successfully!\n";
echo "1. {$outputFileDocx}\n";
echo "2. {$publicOutputDocx}\n";
echo "3. {$legacyDocx}\n";
echo "4. {$legacyDoc}\n";
