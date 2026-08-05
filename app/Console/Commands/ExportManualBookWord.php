<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\Style\Font;

class ExportManualBookWord extends Command
{
    /**
     * Nama dan deskripsi perintah artisan.
     */
    protected $signature = 'export:manual-word';
    protected $description = 'Mengkonversi MANUAL_BOOK_SILAKAN.md menjadi dokumen Microsoft Word (.docx) berformat resmi Bank Indonesia';

    public function handle()
    {
        $mdPath = base_path('MANUAL_BOOK_SILAKAN.md');
        if (!file_exists($mdPath)) {
            $this->error('File MANUAL_BOOK_SILAKAN.md tidak ditemukan!');
            return 1;
        }

        $this->info('Membaca berkas MANUAL_BOOK_SILAKAN.md...');
        $content = file_get_contents($mdPath);

        $this->info('Menggenerasi dokumen Microsoft Word (.docx)...');

        $phpWord = new PhpWord();

        // ---------------------------------------------------------
        // Styling Dokumen Resmi Bank Indonesia
        // ---------------------------------------------------------
        $phpWord->setDefaultFontName('Segoe UI');
        $phpWord->setDefaultFontSize(11);

        // Header & Title Styles
        $phpWord->addTitleStyle(1, ['name' => 'Segoe UI', 'size' => 18, 'bold' => true, 'color' => '003B73'], ['spaceBefore' => 240, 'spaceAfter' => 120]);
        $phpWord->addTitleStyle(2, ['name' => 'Segoe UI', 'size' => 14, 'bold' => true, 'color' => '005BAA'], ['spaceBefore' => 200, 'spaceAfter' => 100]);
        $phpWord->addTitleStyle(3, ['name' => 'Segoe UI', 'size' => 12, 'bold' => true, 'color' => '0F172A'], ['spaceBefore' => 160, 'spaceAfter' => 80]);

        $section = $phpWord->addSection([
            'marginTop' => 1440, // 1 inch
            'marginBottom' => 1440,
            'marginLeft' => 1440,
            'marginRight' => 1440,
        ]);

        // ---------------------------------------------------------
        // Header & Footer Dokumen Resmi
        // ---------------------------------------------------------
        $header = $section->addHeader();
        $headerTable = $header->addTable();
        $headerTable->addRow();
        $headerTable->addCell(5000)->addText('BANK INDONESIA — KPwBI Prov. Sulut', ['size' => 9, 'bold' => true, 'color' => '003B73']);
        $headerTable->addCell(4000)->addText('SILAKAN Manual Book', ['size' => 9, 'color' => '64748B'], ['alignment' => Jc::RIGHT]);

        $footer = $section->addFooter();
        $footer->addPreserveText('Halaman {PAGE} dari {NUMPAGES}', ['size' => 9, 'color' => '64748B'], ['alignment' => Jc::CENTER]);

        // ---------------------------------------------------------
        // Cover / Judul Utama
        // ---------------------------------------------------------
        $section->addText('BUKU PANDUAN PENGGUNAAN SISTEM', ['size' => 22, 'bold' => true, 'color' => '003B73'], ['alignment' => Jc::CENTER, 'spaceAfter' => 60]);
        $section->addText('SILAKAN — Sistem Informasi Layanan Kantor', ['size' => 16, 'bold' => true, 'color' => '005BAA'], ['alignment' => Jc::CENTER, 'spaceAfter' => 40]);
        $section->addText('Kantor Perwakilan Bank Indonesia Provinsi Sulawesi Utara', ['size' => 12, 'color' => '475569', 'italic' => true], ['alignment' => Jc::CENTER, 'spaceAfter' => 300]);
        $section->addTextBreak(1);

        // ---------------------------------------------------------
        // Parse Markdown Content Line by Line
        // ---------------------------------------------------------
        $lines = explode("\n", $content);
        $inTable = false;
        $tableData = [];

        foreach ($lines as $line) {
            $trimmed = trim($line);

            // Skip title heading lines already covered by Cover
            if (str_contains($trimmed, 'BUKU PANDUAN PENGGUNAAN SISTEM') || str_contains($trimmed, 'Sistem Informasi Layanan Kantor')) {
                continue;
            }

            // Headings
            if (str_starts_with($trimmed, '# ')) {
                $text = trim(substr($trimmed, 2));
                $section->addTitle($text, 1);
                continue;
            }
            if (str_starts_with($trimmed, '## ')) {
                $text = trim(substr($trimmed, 3));
                $section->addTitle($text, 1);
                continue;
            }
            if (str_starts_with($trimmed, '### ')) {
                $text = trim(substr($trimmed, 4));
                $section->addTitle($text, 2);
                continue;
            }
            if (str_starts_with($trimmed, '#### ')) {
                $text = trim(substr($trimmed, 5));
                $section->addTitle($text, 3);
                continue;
            }

            // Image Placeholder Callouts
            if (str_contains($trimmed, '🖼️') || str_contains($trimmed, '[TANGKAPAN LAYAR')) {
                $boxTable = $section->addTable(['borderColor' => '005BAA', 'borderSize' => 6, 'cellMargin' => 120]);
                $boxTable->addRow();
                $cell = $boxTable->addCell(9000, ['bgColor' => 'F0Fdf4']);
                $cell->addText('🖼️ SISIPKAN GAMBAR / TANGKAPAN LAYAR:', ['bold' => true, 'color' => '005BAA', 'size' => 10]);
                $cleanText = preg_replace('/[>#*`]/', '', $trimmed);
                $cell->addText($cleanText, ['italic' => true, 'color' => '1E293B', 'size' => 9.5]);
                $section->addTextBreak(1);
                continue;
            }

            // Tables (Markdown pipe format |)
            if (str_starts_with($trimmed, '|')) {
                if (!str_contains($trimmed, '---')) {
                    $cols = array_values(array_filter(array_map('trim', explode('|', $trimmed))));
                    if (count($cols) > 0) {
                        $tableData[] = $cols;
                    }
                }
                $inTable = true;
                continue;
            } else if ($inTable && !empty($tableData)) {
                // Render Table accumulated
                $wordTable = $section->addTable(['borderColor' => 'CBD5E1', 'borderSize' => 4, 'cellMargin' => 100]);
                foreach ($tableData as $rowIndex => $row) {
                    $wordTable->addRow();
                    $isHeader = ($rowIndex === 0);
                    foreach ($row as $cellText) {
                        $bgColor = $isHeader ? 'F1F5F9' : ($rowIndex % 2 === 0 ? 'F8FAFC' : 'FFFFFF');
                        $cell = $wordTable->addCell(3000, ['bgColor' => $bgColor]);
                        $cell->addText($cellText, ['bold' => $isHeader, 'size' => 9.5, 'color' => '1E293B']);
                    }
                }
                $section->addTextBreak(1);
                $tableData = [];
                $inTable = false;
            }

            // List items
            if (str_starts_with($trimmed, '- ') || str_starts_with($trimmed, '* ')) {
                $itemText = preg_replace('/^[-*]\s+/', '', $trimmed);
                $itemText = preg_replace('/\*\*(.*?)\*\*/', '$1', $itemText);
                $section->addListItem($itemText, 0, ['size' => 10.5, 'color' => '334155']);
                continue;
            }

            // Numbered list items
            if (preg_match('/^\d+\.\s+(.*)/', $trimmed, $matches)) {
                $itemText = preg_replace('/\*\*(.*?)\*\*/', '$1', $matches[1]);
                $section->addListItem($itemText, 0, ['size' => 10.5, 'color' => '334155'], \PhpOffice\PhpWord\Style\ListItem::TYPE_NUMBER);
                continue;
            }

            // Horizontal Line
            if ($trimmed === '---' || $trimmed === '***') {
                $section->addTextBreak(1);
                continue;
            }

            // Normal Paragraph
            if (!empty($trimmed)) {
                $cleanParagraph = preg_replace('/\*\*(.*?)\*\*/', '$1', $trimmed);
                $cleanParagraph = str_replace(['#', '`', '>'], '', $cleanParagraph);
                $section->addText(trim($cleanParagraph), ['size' => 10.5, 'color' => '1E293B'], ['spaceAfter' => 120, 'lineSpacing' => 1.15]);
            }
        }

        // Save Word Document
        $outputPath = base_path('MANUAL_BOOK_SILAKAN.docx');
        $publicPath = public_path('MANUAL_BOOK_SILAKAN.docx');

        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($outputPath);
        $objWriter->save($publicPath);

        $this->info("✅ Berhasil mengkonversi Manual Book ke Microsoft Word!");
        $this->info("📄 Berkas disimpan di: " . $outputPath);
        $this->info("🌐 Berkas publik disimpan di: " . $publicPath);

        return 0;
    }
}
