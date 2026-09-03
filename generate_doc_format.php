<?php

/**
 * Script untuk membuat dokumen Microsoft Word (.doc) resmi SILAKAN
 * Menggunakan format MHTML / Office Word Document yang didukung oleh semua versi Microsoft Word.
 */

$htmlFile = __DIR__ . '/manual_book.html';
if (!file_exists($htmlFile)) {
    die("File manual_book.html tidak ditemukan!\n");
}

$html = file_get_contents($htmlFile);

// Hapus tombol print preview di browser
$html = preg_replace('/<div class="action-bar-top no-print">.*?<\/div>/s', '', $html);

// Ekstrak dan embed seluruh gambar ke MHTML parts
$imageParts = [];
$boundary = "----=_NextPart_SILAKAN_DOC_" . md5(time());

// Cari seluruh tag img
preg_match_all('/<img[^>]+src=["\']([^"\']+)["\'][^>]*>/i', $html, $matches);

$imgReplacements = [];
$counter = 1;

if (!empty($matches[1])) {
    foreach (array_unique($matches[1]) as $src) {
        // Path fisik file
        $realPath = __DIR__ . '/' . ltrim(str_replace('\\', '/', $src), '/');
        if (file_exists($realPath)) {
            $ext = strtolower(pathinfo($realPath, PATHINFO_EXTENSION));
            $mime = ($ext === 'png') ? 'image/png' : (($ext === 'jpg' || $ext === 'jpeg') ? 'image/jpeg' : 'image/png');
            $imgName = "image_" . str_pad($counter++, 2, '0', STR_PAD_LEFT) . "." . $ext;
            
            $imgData = base64_encode(file_get_contents($realPath));
            
            // Siapkan MHTML part
            $part = "--{$boundary}\r\n";
            $part .= "Content-Type: {$mime}\r\n";
            $part .= "Content-Transfer-Encoding: base64\r\n";
            $part .= "Content-Location: {$imgName}\r\n\r\n";
            $part .= chunk_split($imgData, 76, "\r\n") . "\r\n";
            
            $imageParts[] = $part;
            
            // Ganti src di HTML agar merujuk ke Content-Location
            $html = str_replace($src, $imgName, $html);
        }
    }
}

// Tambahkan Word Document XML Header & Margins
$wordHeader = '<html xmlns:v="urn:schemas-microsoft-com:vml"
xmlns:o="urn:schemas-microsoft-com:office:office"
xmlns:w="urn:schemas-microsoft-com:office:word"
xmlns:m="http://schemas.openxmlformats.org/officeDocument/2006/math"
xmlns="http://www.w3.org/TR/REC-html40">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!--[if gte mso 9]>
<xml>
 <w:WordDocument>
  <w:View>Print</w:View>
  <w:Zoom>100</w:Zoom>
  <w:DoNotOptimizeForBrowser/>
 </w:WordDocument>
</xml>
<![endif]-->
<style>
@page {
    size: 21.0cm 29.7cm;
    margin: 2.5cm 2.0cm 2.5cm 2.0cm;
    mso-header-margin: 1.25cm;
    mso-footer-margin: 1.25cm;
}
@page Section1 {
    mso-header: h1;
    mso-footer: f1;
}
div.Section1 {
    page: Section1;
}
table#hrdftrtbl {
    margin: 0in 0in 0in 9in;
}
body {
    font-family: "Segoe UI", Calibri, Arial, sans-serif;
    font-size: 10.5pt;
    line-height: 1.5;
    color: #1e293b;
}
h1, h2, h3, h4 {
    font-family: "Segoe UI", Calibri, Arial, sans-serif;
}
h1 {
    font-size: 17pt;
    color: #003b73;
    font-weight: bold;
    border-bottom: 2pt solid #005baa;
    padding-bottom: 4pt;
    margin-top: 18pt;
    margin-bottom: 10pt;
    page-break-before: always;
}
h2 {
    font-size: 13pt;
    color: #005baa;
    font-weight: bold;
    margin-top: 14pt;
    margin-bottom: 6pt;
}
h3 {
    font-size: 11pt;
    color: #1e293b;
    font-weight: bold;
    margin-top: 10pt;
    margin-bottom: 4pt;
}
p {
    margin-top: 0;
    margin-bottom: 8pt;
    text-align: justify;
}
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 8pt;
    margin-bottom: 12pt;
}
th {
    background-color: #005baa;
    color: #ffffff;
    font-weight: bold;
    padding: 7pt 10pt;
    border: 1pt solid #003b73;
    font-size: 9.5pt;
    text-align: left;
}
td {
    border: 1pt solid #cbd5e1;
    padding: 6pt 10pt;
    font-size: 9.5pt;
    vertical-align: top;
}
tr:nth-child(even) td {
    background-color: #f8fafc;
}
.callout {
    border: 1.5pt solid #005baa;
    background-color: #f0fdf4;
    padding: 10pt 14pt;
    margin: 10pt 0;
    border-radius: 4pt;
}
.screenshot-container {
    text-align: center;
    margin: 14pt 0 16pt 0;
    page-break-inside: avoid;
}
.screenshot-frame {
    display: inline-block;
    border: 1pt solid #cbd5e1;
    padding: 3pt;
    background: #ffffff;
}
.screenshot-frame img {
    max-width: 580px;
    height: auto;
}
.screenshot-caption {
    font-size: 9pt;
    font-weight: bold;
    font-style: italic;
    color: #475569;
    margin-top: 4pt;
    display: block;
}
.step-box {
    border: 1pt solid #e2e8f0;
    background: #f8fafc;
    padding: 10pt 14pt;
    margin-bottom: 10pt;
    border-radius: 4pt;
}
.badge {
    padding: 2pt 6pt;
    font-size: 8pt;
    font-weight: bold;
    border-radius: 3pt;
}
.badge-pending { background: #fef3c7; color: #92400e; }
.badge-disetujui { background: #dcfce7; color: #166534; }
.badge-ditolak { background: #fee2e2; color: #991b1b; }
.badge-cancel { background: #f1f5f9; color: #475569; }
</style>
</head>
<body class="Section1">
';

// Ganti bagian <html> dan <head> bawaan
$htmlBody = preg_replace('/^.*?<body[^>]*>/is', '', $html);
$htmlBody = preg_replace('/<\/body>.*?<\/html>/is', '', $htmlBody);

$fullHtmlContent = $wordHeader . $htmlBody . '
<!-- Header and Footer Definition for Word -->
<table id="hrdftrtbl" border="0" cellspacing="0" cellpadding="0">
<tr>
    <td>
        <div style="mso-element:header" id="h1">
            <p style="text-align:right;font-size:8.5pt;color:#64748b;margin:0;">
                SILAKAN &mdash; KPwBI Provinsi Sulawesi Utara | Manual Book v1.0
            </p>
        </div>
    </td>
    <td>
        <div style="mso-element:footer" id="f1">
            <p style="text-align:right;font-size:8.5pt;color:#64748b;margin:0;">
                Halaman <span style="mso-field-code: PAGE "></span> dari <span style="mso-field-code: NUMPAGES "></span>
            </p>
        </div>
    </td>
</tr>
</table>
</body>
</html>';

// Susun berkas MHTML Document
$docMimeContent = "MIME-Version: 1.0\r\n";
$docMimeContent .= "Content-Type: multipart/related; boundary=\"{$boundary}\"; type=\"text/html\"\r\n\r\n";

// HTML Part
$docMimeContent .= "--{$boundary}\r\n";
$docMimeContent .= "Content-Type: text/html; charset=\"utf-8\"\r\n";
$docMimeContent .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
$docMimeContent .= $fullHtmlContent . "\r\n\r\n";

// Image Parts
foreach ($imageParts as $imgPart) {
    $docMimeContent .= $imgPart;
}

$docMimeContent .= "--{$boundary}--\r\n";

// Simpan ke berkas .doc
$docFile1 = __DIR__ . '/Manual_Book_SILAKAN_v1.0.doc';
$docFile2 = __DIR__ . '/MANUAL_BOOK_SILAKAN.doc';
$docFile3 = __DIR__ . '/public/Manual_Book_SILAKAN_v1.0.doc';
$docFile4 = __DIR__ . '/public/MANUAL_BOOK_SILAKAN.doc';

file_put_contents($docFile1, $docMimeContent);
file_put_contents($docFile2, $docMimeContent);
file_put_contents($docFile3, $docMimeContent);
file_put_contents($docFile4, $docMimeContent);

echo "DOC files generated successfully!\n";
echo "1. " . $docFile1 . " (" . number_format(filesize($docFile1)) . " bytes)\n";
echo "2. " . $docFile2 . " (" . number_format(filesize($docFile2)) . " bytes)\n";
echo "3. " . $docFile3 . " (" . number_format(filesize($docFile3)) . " bytes)\n";
echo "4. " . $docFile4 . " (" . number_format(filesize($docFile4)) . " bytes)\n";
