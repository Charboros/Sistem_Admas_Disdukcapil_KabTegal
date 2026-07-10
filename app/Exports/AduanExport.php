<?php

namespace App\Exports;

use App\Models\Aduan;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class AduanExport implements
    FromCollection,
    WithHeadings,
    WithStyles,
    WithColumnWidths,
    WithTitle,
    WithEvents
{
    protected $aduans;

    public function __construct($request = null)
    {
        $query = Aduan::with(['petugas', 'respon.user']);

        if ($request) {
            $query->filterAduan($request);
        }

        $this->aduans = $query->orderBy('tanggal_aduan', 'desc')->get();
    }

    public function collection()
    {
        $no = 1;
        return $this->aduans->map(function ($aduan) use (&$no) {
            return [
                'No'            => $no++,
                'Tanggal'       => $aduan->tanggal_aduan
                                    ? $aduan->tanggal_aduan->format('d/m/Y')
                                    : '-',
                'Waktu'         => $aduan->waktu_aduan ?? '-',
                'Kanal'         => $aduan->kanal        ?? '-',
                'Klasifikasi'   => $aduan->klasifikasi  ?? '-',
                'Nama Akun'     => $aduan->nama_akun    ?? '-',
                'Isi Aduan'     => $aduan->isi_aduan,
                'Status Respon' => $aduan->sudah_direspon ? 'Sudah Direspon' : 'Belum Direspon',
                'Isi Respon'    => $aduan->respon->count() > 0 
                                     ? $aduan->respon->pluck('isi_respon')->implode("\n\n") 
                                     : '-',
                'Petugas'       => $aduan->petugas->name ?? '-',
                'Foto'          => '',  // placeholder untuk kolom gambar
            ];
        });
    }

    public function headings(): array
    {
        return [
            'No', 'Tanggal', 'Waktu', 'Kanal', 'Klasifikasi',
            'Nama Akun', 'Isi Aduan', 'Status Respon', 'Isi Respon',
            'Petugas', 'Foto',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,   // No
            'B' => 15,  // Tanggal
            'C' => 10,  // Waktu
            'D' => 15,  // Kanal
            'E' => 30,  // Klasifikasi
            'F' => 20,  // Nama Akun
            'G' => 50,  // Isi Aduan
            'H' => 18,  // Status Respon
            'I' => 45,  // Isi Respon
            'J' => 18,  // Petugas
            'K' => 22,  // Foto
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $totalRows = $this->aduans->count() + 1;

        // Header row
        $sheet->getStyle('A1:K1')->applyFromArray([
            'font' => [
                'bold'  => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size'  => 11,
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1E40AF'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
                'wrapText'   => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => ['rgb' => 'FFFFFF'],
                ],
            ],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(32);

        // Data rows
        if ($totalRows > 1) {
            for ($row = 2; $row <= $totalRows; $row++) {
                $bgColor = ($row % 2 === 0) ? 'EFF6FF' : 'FFFFFF';

                $sheet->getStyle("A{$row}:K{$row}")->applyFromArray([
                    'fill' => [
                        'fillType'   => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => $bgColor],
                    ],
                    'font'      => ['size' => 10],
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_TOP,
                        'wrapText' => true,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color'       => ['rgb' => 'DBEAFE'],
                        ],
                    ],
                ]);
                $sheet->getRowDimension($row)->setRowHeight(70);
            }

            // Center alignment columns
            $sheet->getStyle("A2:A{$totalRows}")->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("C2:D{$totalRows}")->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("E2:F{$totalRows}")->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Status respon color
            for ($row = 2; $row <= $totalRows; $row++) {
                $val = $sheet->getCell("J{$row}")->getValue();
                $color = $val === 'Sudah Direspon' ? '15803D' : 'DC2626';
                $sheet->getStyle("J{$row}")->applyFromArray([
                    'font'      => ['color' => ['rgb' => $color], 'bold' => true],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);
            }
        }

        return [];
    }

    public function title(): string
    {
        return 'Data Aduan';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet     = $event->sheet->getDelegate();
                $spreadsheet = $event->sheet->getDelegate()->getParent();

                // Row 1 keterangan instansi di atas header (insert 2 baris)
                $sheet->insertNewRowBefore(1, 2);

                // Merge dan isi keterangan
                $sheet->mergeCells('A1:K1');
                $sheet->setCellValue('A1', 'REKAP ADUAN LAYANAN — DISDUKCAPIL KABUPATEN TEGAL');
                $sheet->getStyle('A1')->applyFromArray([
                    'font'      => ['bold' => true, 'size' => 13, 'color' => ['rgb' => '1E3A8A']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);
                $sheet->getRowDimension(1)->setRowHeight(30);

                $sheet->mergeCells('A2:K2');
                $sheet->setCellValue('A2', 'Tanggal Export: ' . now()->isoFormat('D MMMM Y'));
                $sheet->getStyle('A2')->applyFromArray([
                    'font'      => ['size' => 10, 'color' => ['rgb' => '64748B'], 'italic' => true],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);
                $sheet->getRowDimension(2)->setRowHeight(20);

                // Embed gambar screenshot
                $row = 4; // data starts at row 4 (header at row 3, 2 info rows above)
                $tempDir = storage_path('app/temp_excel_images');
                if (!is_dir($tempDir)) {
                    @mkdir($tempDir, 0777, true);
                }

                foreach ($this->aduans as $aduan) {
                    if ($aduan->screenshot) {
                        $tempPath = $tempDir . '/aduan_img_' . $aduan->id . '.jpg';
                        file_put_contents($tempPath, $aduan->screenshot);

                        if (file_exists($tempPath)) {
                            $drawing = new Drawing();
                            $drawing->setName('Screenshot');
                            $drawing->setDescription('Bukti Aduan');
                            $drawing->setPath($tempPath);
                            $drawing->setHeight(60);
                            $drawing->setCoordinates('K' . $row);
                            $drawing->setOffsetX(4);
                            $drawing->setOffsetY(4);
                            $drawing->setWorksheet($sheet);
                        }
                    }
                    $row++;
                }

                // Freeze header
                $sheet->freezePane('A4');

                // Auto filter
                $lastRow = $this->aduans->count() + 3;
                $sheet->setAutoFilter('A3:K3');

                // Print settings
                $sheet->getPageSetup()
                    ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE)
                    ->setFitToWidth(1)
                    ->setFitToHeight(0)
                    ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A3);

                $sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(3, 3);
                $sheet->getPageMargins()->setTop(0.5)->setBottom(0.5)->setLeft(0.5)->setRight(0.5);

                // Tab name
                $sheet->setTitle('Aduan_' . date('Ymd'));
            },
        ];
    }
}
