<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InformDesignExport implements FromArray, WithStyles, WithColumnWidths, WithEvents, WithTitle
{
    protected array $mapData;

    public function __construct(array $_mapData)
    {
        $this->mapData = $_mapData;
    }

    public function array(): array
    {
        $rows = array_fill(0, 65, array_fill(0, 12, ''));

        $rows[0][2] = 'INFORME DE ENSAYO N° XXX-XX-I';
        $rows[1][2] = 'CON VALOR OFICIAL';

        $labels = [
            4 => 'Razón Social del cliente',
            5 => 'Dirección',
            6 => 'Solicitado Por',
            7 => 'Referencia',
            8 => 'Proyecto',
            9 => 'Procedencia',
            10 => 'Muestreo Realizado Por',
            11 => 'Cantidad de Muestra',
            12 => 'Producto',
            13 => 'Plan de Muestreo',
            14 => 'Fecha de Recepción',
            15 => 'Hora de Recepción',
            16 => 'Periodo de Ensayo',
            17 => 'Fecha de Emisión',
        ];

        foreach ($labels as $row => $label) {
            $rows[$row - 1][0] = $label;
            $rows[$row - 1][2] = ':';
        }

        $rows[3][3] = $this->mapData['company'];
        $rows[4][3] = $this->mapData['direction'];
        $rows[5][3] = $this->mapData['application'];

        // $rows[6][3] = $this->mapData['project'];
        // $rows[7][3] = $this->mapData['origin'];
        $rows[6][3] = $this->mapData['reference'];
        $rows[9][3] = $this->mapData['sampling_performed_by'];
        $rows[10][3] = $this->mapData['sample_quantity'];
        $rows[11][3] = $this->mapData['product'];

        $rows[12][3] = 'PM N°';
        $rows[13][3] = $this->mapData['date_of_receipt'];
        $rows[14][3] = $this->mapData['time_of_receipt'];
        $rows[15][3] = $this->mapData['test_period'];
        $rows[16][3] = $this->mapData['date_of_issue'];

        $rows[18][0] = 'Gracias por utilizar los servicios GREENLAB PERÚ S.A.C Póngase en contacto con el Ejecutivo de Ventas, si desea información adicional o cualquier aclaración';
        $rows[19][0] = 'que pertenezca a este informe.';
        $rows[20][0] = 'Informe Autorizado por:';

        $rows[31][2] = 'INFORME DE ENSAYO N° XXX-XX-I';
        $rows[32][2] = 'CON VALOR OFICIAL';

        $rows[34][0] = 'I. RESULTADOS DE ANALISIS';

        $rows[37][0] = 'Código del Laboratorio';
        $rows[37][6] = '2311-172-1';
        $rows[37][8] = '2311-172-2';
        $rows[37][10] = '2311-172-3';

        $rows[38][0] = 'Código de la muestra';
        $rows[38][6] = 'LM-01 (Corrida N° 01)';
        $rows[38][8] = 'LM-01 (Corrida N° 02)';
        $rows[38][10] = 'LM-01 (Corrida N° 03)';

        $rows[39][0] = 'Fecha de muestreo';
        $rows[39][6] = '2023-11-10';
        $rows[39][8] = '2023-11-10';
        $rows[39][10] = '2023-11-10';

        $rows[40][0] = 'Hora de muestreo';
        $rows[40][6] = '14:45:00';
        $rows[40][8] = '14:45:00';
        $rows[40][10] = '14:45:00';

        $rows[41][0] = 'Categoría';
        $rows[41][8] = 'EMISIONES';

        $rows[42][0] = 'Coordenadas (WGS-84)';
        $rows[42][6] = 'E:';
        $rows[42][8] = 'E:';
        $rows[42][10] = 'E:';
        $rows[43][6] = 'N:';
        $rows[43][8] = 'N:';
        $rows[43][10] = 'N:';

        $rows[44][0] = 'Descripción del punto de Muestreo';

        $rows[45][0] = 'Tipo de Ensayo';
        $rows[45][3] = 'Unidad';
        $rows[45][4] = 'L.C.M.';
        $rows[45][8] = 'Resultados';

        $currentRow = 46;

        foreach (($this->mapData['analysis_groups'] ?? []) as $group) {
            $rows[$currentRow][0] = $group['type_of_analysis'] ?? 'SIN TIPO DE ENSAYO';

            $currentRow++;

            foreach (($group['parameters'] ?? []) as $parameter) {
                $rows[$currentRow][0] = '- ' . ($parameter['parameter'] ?? '-');
                $rows[$currentRow][3] = $parameter['unit'] ?? '-';
                $rows[$currentRow][4] = $parameter['lcm'] ?? '-';

                $currentRow++;
            }
        }

        return $rows;
    }

    public function title(): string
    {
        return 'Primera página';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 22,
            'B' => 18,
            'C' => 5,
            'D' => 14,
            'E' => 10,
            'F' => 10,
            'G' => 17,
            'H' => 12,
            'I' => 17,
            'J' => 12,
            'K' => 17,
            'L' => 12,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 16]],
            2 => ['font' => ['bold' => true, 'size' => 16]],
            32 => ['font' => ['bold' => true, 'size' => 16]],
            33 => ['font' => ['bold' => true, 'size' => 16]],
            35 => ['font' => ['bold' => true]],
            46 => ['font' => ['bold' => true]],
            47 => ['font' => ['bold' => true]],
            54 => ['font' => ['bold' => true]],
            63 => ['font' => ['bold' => true]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $sheet->getPageSetup()
                    ->setOrientation(PageSetup::ORIENTATION_PORTRAIT)
                    ->setPaperSize(PageSetup::PAPERSIZE_A4)
                    ->setFitToWidth(1)
                    ->setFitToHeight(0);

                $sheet->getPageMargins()->setTop(0.25);
                $sheet->getPageMargins()->setBottom(0.25);
                $sheet->getPageMargins()->setLeft(0.20);
                $sheet->getPageMargins()->setRight(0.20);

                // Merge de títulos principales
                $sheet->mergeCells('C1:H1');
                $sheet->mergeCells('C2:H2');
                $sheet->mergeCells('A19:K19');
                $sheet->mergeCells('A20:K20');
                $sheet->mergeCells('C32:H32');
                $sheet->mergeCells('C33:H33');
                $sheet->mergeCells('A35:L35');
                $sheet->mergeCells('I46:L46');

                // Apariencia general
                $sheet->getStyle('A1:L65')->applyFromArray([
                    'font' => [
                        'name' => 'Arial',
                        'size' => 9,
                        'color' => ['rgb' => '111111'],
                    ],
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],
                ]);

                $sheet->getStyle('A1:L2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A32:L33')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A4:A17')->getFont()->setBold(true);
                $sheet->getStyle('A21')->getFont()->setBold(true);
                $sheet->getStyle('A35')->getFont()->setBold(true);
                $sheet->getStyle('A37:A45')->getFont()->setBold(true);
                $sheet->getStyle('A46:L46')->getFont()->setBold(true);
                $sheet->getStyle('A47')->getFont()->setBold(true);
                $sheet->getStyle('A54')->getFont()->setBold(true);
                $sheet->getStyle('A63')->getFont()->setBold(true);

                $sheet->getStyle('G37:L44')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('D46:L65')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('L6:L7')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('L6:L7')->getFont()->setSize(5);

                // Líneas superiores/inferiores como en el formato
                $this->bottomBorder($sheet, 'A18:K18');
                $this->bottomBorder($sheet, 'A35:L35');
                $this->topBorder($sheet, 'A35:L35');
                $this->bottomBorder($sheet, 'A37:L37');
                $this->topBorder($sheet, 'A37:L37');
                $this->bottomBorder($sheet, 'A45:L45');
                $this->bottomBorder($sheet, 'A46:L46');
                $this->topBorder($sheet, 'A46:L46');
                $this->topBorder($sheet, 'A53:L53');
                $this->bottomBorder($sheet, 'A53:L53');
                $this->topBorder($sheet, 'A62:L62');
                $this->bottomBorder($sheet, 'A62:L62');
                $this->bottomBorder($sheet, 'A65:L65');

                // Alturas aproximadas del diseño original
                for ($i = 1; $i <= 65; $i++) {
                    $sheet->getRowDimension($i)->setRowHeight(18);
                }

                foreach ([3, 18, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 36] as $row) {
                    $sheet->getRowDimension($row)->setRowHeight(24);
                }

                foreach (range(4, 17) as $row) {
                    $sheet->getRowDimension($row)->setRowHeight(30);
                }

                $sheet->getRowDimension(19)->setRowHeight(20);
                $sheet->getRowDimension(20)->setRowHeight(20);
                $sheet->getRowDimension(32)->setRowHeight(22);
                $sheet->getRowDimension(33)->setRowHeight(22);

                // Fondo blanco y sin gridlines al abrir
                $sheet->getStyle('A1:L65')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FFFFFF');
                $sheet->setShowGridlines(false);

                // Área de impresión de la primera página/diseño
                $sheet->getPageSetup()->setPrintArea('A1:L65');

                $currentRow = 47;

                foreach (($this->mapData['analysis_groups'] ?? []) as $group) {
                    $sheet->getStyle("A{$currentRow}:L{$currentRow}")
                        ->getFont()
                        ->setBold(true);

                    $this->topBorder($sheet, "A{$currentRow}:L{$currentRow}");
                    $this->bottomBorder($sheet, "A{$currentRow}:L{$currentRow}");

                    $currentRow++;

                    foreach (($group['parameters'] ?? []) as $parameter) {
                        $currentRow++;
                    }
                }
            },
        ];
    }

    private function bottomBorder(Worksheet $sheet, string $range): void
    {
        $sheet->getStyle($range)->applyFromArray([
            'borders' => [
                'bottom' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);
    }

    private function topBorder(Worksheet $sheet, string $range): void
    {
        $sheet->getStyle($range)->applyFromArray([
            'borders' => [
                'top' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);
    }
}
