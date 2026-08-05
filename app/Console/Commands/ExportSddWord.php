<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ExportSddWord extends Command
{
    protected $signature = 'export:sdd-word';
    protected $description = 'Mengkonversi SDD_SILAKAN.md menjadi dokumen Microsoft Word (.doc / .docx) berformat resmi Bank Indonesia';

    public function handle()
    {
        $mdPath = base_path('SDD_SILAKAN.md');
        if (!file_exists($mdPath)) {
            $this->error('File SDD_SILAKAN.md tidak ditemukan!');
            return 1;
        }

        $this->info('Membaca berkas SDD_SILAKAN.md...');
        $content = file_get_contents($mdPath);

        $htmlContent = $this->markdownToWordHtml($content);

        $docPath = base_path('SDD_SILAKAN.doc');
        $docxPath = base_path('SDD_SILAKAN.docx');
        $publicDocPath = public_path('SDD_SILAKAN.doc');
        $publicDocxPath = public_path('SDD_SILAKAN.docx');

        file_put_contents($docPath, $htmlContent);
        file_put_contents($docxPath, $htmlContent);
        file_put_contents($publicDocPath, $htmlContent);
        file_put_contents($publicDocxPath, $htmlContent);

        $this->info("✅ Berhasil mengkonversi Software Design Document ke Microsoft Word!");
        $this->info("📄 Berkas disimpan di: " . $docPath);
        $this->info("📄 Berkas docx disimpan di: " . $docxPath);

        return 0;
    }

    private function markdownToWordHtml(string $markdown): string
    {
        $lines = explode("\n", $markdown);
        $body = '';

        $inTable = false;
        $tableRows = [];

        foreach ($lines as $line) {
            $trimmed = trim($line);

            if (empty($trimmed)) {
                if ($inTable) {
                    $body .= $this->buildHtmlTable($tableRows);
                    $tableRows = [];
                    $inTable = false;
                }
                continue;
            }

            // Headings
            if (str_starts_with($trimmed, '# ')) {
                $body .= '<h1>' . htmlspecialchars(substr($trimmed, 2)) . '</h1>';
                continue;
            }
            if (str_starts_with($trimmed, '## ')) {
                $body .= '<h2>' . htmlspecialchars(substr($trimmed, 3)) . '</h2>';
                continue;
            }
            if (str_starts_with($trimmed, '### ')) {
                $body .= '<h3>' . htmlspecialchars(substr($trimmed, 4)) . '</h3>';
                continue;
            }
            if (str_starts_with($trimmed, '#### ')) {
                $body .= '<h4>' . htmlspecialchars(substr($trimmed, 5)) . '</h4>';
                continue;
            }

            // Tables
            if (str_starts_with($trimmed, '|')) {
                if (!str_contains($trimmed, '---')) {
                    $cols = array_values(array_filter(array_map('trim', explode('|', $trimmed))));
                    if (count($cols) > 0) {
                        $tableRows[] = $cols;
                    }
                }
                $inTable = true;
                continue;
            } else if ($inTable) {
                $body .= $this->buildHtmlTable($tableRows);
                $tableRows = [];
                $inTable = false;
            }

            // Bullet Lists
            if (str_starts_with($trimmed, '- ') || str_starts_with($trimmed, '* ')) {
                $item = htmlspecialchars(preg_replace('/^[-*]\s+/', '', $trimmed));
                $item = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $item);
                $body .= '<ul><li>' . $item . '</li></ul>';
                continue;
            }

            // Numbered Lists
            if (preg_match('/^\d+\.\s+(.*)/', $trimmed, $matches)) {
                $item = htmlspecialchars($matches[1]);
                $item = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $item);
                $body .= '<ol><li>' . $item . '</li></ol>';
                continue;
            }

            // Horizontal Rules
            if ($trimmed === '---' || $trimmed === '***') {
                $body .= '<hr>';
                continue;
            }

            // Code Blocks / Diagrams
            if (str_starts_with($trimmed, '```')) {
                continue;
            }

            // Paragraphs
            $p = htmlspecialchars($trimmed);
            $p = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $p);
            $body .= '<p>' . $p . '</p>';
        }

        if ($inTable && !empty($tableRows)) {
            $body .= $this->buildHtmlTable($tableRows);
        }

        return '<html xmlns:v="urn:schemas-microsoft-com:vml"
xmlns:o="urn:schemas-microsoft-com:office:office"
xmlns:w="urn:schemas-microsoft-com:office:word"
xmlns:m="http://schemas.openxmlformats.org/officeDocument/2006/math"
xmlns="http://www.w3.org/TR/REC-html40">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>SOFTWARE DESIGN DOCUMENT SILAKAN BI SULUT</title>
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
    size: 21cm 29.7cm;
    margin: 2.54cm 2.54cm 2.54cm 2.54cm;
    mso-header-margin: 1.27cm;
    mso-footer-margin: 1.27cm;
}
body {
    font-family: "Segoe UI", Arial, sans-serif;
    font-size: 11pt;
    line-height: 1.4;
    color: #1e293b;
}
h1 {
    font-size: 18pt;
    color: #003b73;
    font-weight: bold;
    margin-top: 18pt;
    margin-bottom: 8pt;
    border-bottom: 2pt solid #003b73;
    padding-bottom: 4pt;
}
h2 {
    font-size: 14pt;
    color: #005baa;
    font-weight: bold;
    margin-top: 14pt;
    margin-bottom: 6pt;
}
h3 {
    font-size: 12pt;
    color: #0f172a;
    font-weight: bold;
    margin-top: 10pt;
    margin-bottom: 4pt;
}
h4 {
    font-size: 11pt;
    color: #334155;
    font-weight: bold;
}
p {
    margin-top: 0;
    margin-bottom: 6pt;
    text-align: justify;
}
ul, ol {
    margin-top: 0;
    margin-bottom: 6pt;
    padding-left: 20pt;
}
li {
    margin-bottom: 3pt;
}
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 8pt;
    margin-bottom: 12pt;
}
th {
    background-color: #f1f5f9;
    color: #003b73;
    font-weight: bold;
    border: 1pt solid #cbd5e1;
    padding: 6pt 8pt;
    font-size: 9.5pt;
    text-align: left;
}
td {
    border: 1pt solid #cbd5e1;
    padding: 6pt 8pt;
    font-size: 9.5pt;
}
tr:nth-child(even) td {
    background-color: #f8fafc;
}
hr {
    border: 0;
    border-top: 1pt solid #cbd5e1;
    margin-top: 12pt;
    margin-bottom: 12pt;
}
</style>
</head>
<body>
' . $body . '
</body>
</html>';
    }

    private function buildHtmlTable(array $rows): string
    {
        if (empty($rows)) return '';
        $html = '<table>';
        foreach ($rows as $index => $row) {
            $html .= '<tr>';
            $isHeader = ($index === 0);
            $tag = $isHeader ? 'th' : 'td';
            foreach ($row as $cell) {
                $html .= "<{$tag}>" . htmlspecialchars($cell) . "</{$tag}>";
            }
            $html .= '</tr>';
        }
        $html .= '</table>';
        return $html;
    }
}
