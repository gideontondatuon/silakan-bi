<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class PemesananExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle, WithCustomStartCell, WithEvents
{
    protected $data;
    protected $rowNumber = 0;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data;
    }

    public function title(): string
    {
        return 'Laporan Pemesanan Ruangan';
    }

    public function startCell(): string
    {
        return 'A7';
    }

    public function headings(): array
    {
        return [
            'NO',
            'KODE',
            'TANGGAL',
            'WAKTU (WITA)',
            'RUANGAN',
            'LAYOUT',
            'JUDUL KEGIATAN',
            'UNIT / PEMOHON',
            'PIC KEGIATAN',
            'STATUS',
            'APPROVED BY',
        ];
    }

    public function map($row): array
    {
        $this->rowNumber++;
        $statusVal = is_object($row->status) ? $row->status->value : $row->status;

        return [
            $this->rowNumber,
            $row->kode_pemesanan,
            $row->tanggal_kegiatan ? $row->tanggal_kegiatan->format('d/m/Y') : '-',
            $row->waktu_mulai . ' - ' . $row->waktu_selesai,
            $row->ruangan->nama_ruangan ?? '-',
            $row->layout->nama_layout ?? '-',
            $row->judul_kegiatan,
            $row->user->nama_unit ?? $row->user->name,
            $row->pic_kegiatan,
            strtoupper($statusVal),
            $row->approver->name ?? '-',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Kop Surat Bank Indonesia
                $sheet->mergeCells('A1:K1');
                $sheet->setCellValue('A1', 'BANK INDONESIA');
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('003B73'));

                $sheet->mergeCells('A2:K2');
                $sheet->setCellValue('A2', 'Kantor Perwakilan Bank Indonesia Provinsi Sulawesi Utara');
                $sheet->getStyle('A2')->getFont()->setSize(11)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('475569'));

                $sheet->mergeCells('A3:K3');
                $sheet->setCellValue('A3', 'SILAKAN — Sistem Informasi Layanan Kantor');
                $sheet->getStyle('A3')->getFont()->setSize(10)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('64748b'));

                // Judul Laporan
                $sheet->mergeCells('A5:K5');
                $sheet->setCellValue('A5', 'LAPORAN REKAPITULASI PEMESANAN RUANGAN');
                $sheet->getStyle('A5')->getFont()->setBold(true)->setSize(14)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('003B73'));
                $sheet->getStyle('A5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->mergeCells('A6:K6');
                $sheet->setCellValue('A6', 'Dicetak pada: ' . now()->translatedFormat('l, d F Y H:i') . ' WITA');
                $sheet->getStyle('A6')->getFont()->setSize(10)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('64748b'));
                $sheet->getStyle('A6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Footer Total & Tanda Tangan
                $lastDataRow = 6 + count($this->data) + 1; // 7 + data length
                $footerRow = $lastDataRow + 2;

                $sheet->setCellValue("A{$footerRow}", 'Total Record: ' . count($this->data) . ' Data');
                $sheet->getStyle("A{$footerRow}")->getFont()->setBold(true);

                $sigRow = $footerRow;
                $sheet->setCellValue("I{$sigRow}", 'Manado, ' . now()->translatedFormat('d F Y'));
                $sheet->setCellValue("I" . ($sigRow + 1), 'Administrator SILAKAN');
                $sheet->setCellValue("I" . ($sigRow + 4), 'KPwBI Prov. Sulut');
                $sheet->getStyle("I" . ($sigRow + 4))->getFont()->setBold(true);
            },
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Styling Table Header Row (Row 7)
        $sheet->getStyle('A7:K7')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 11,
                'name' => 'Segoe UI'
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '003B73'] // Bank Indonesia Primary Blue
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ]);

        $lastDataRow = 7 + count($this->data);

        // Style data rows
        if ($lastDataRow >= 8) {
            $sheet->getStyle("A7:K{$lastDataRow}")->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'CBD5E1']
                    ]
                ]
            ]);

            // Center alignments for specific columns
            $sheet->getStyle("A8:D{$lastDataRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("J8:J{$lastDataRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        $sheet->getRowDimension(7)->setRowHeight(26);

        return [];
    }
}
