<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ChainCustodyExport implements FromArray, WithStyles, WithEvents, WithColumnWidths, WithDrawings
{
    protected array $payload;

    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }

    public function array(): array
    {
        $maxColumns = max((int) ($this->payload['maxColumns'] ?? 2), 2);

        /*
         * Para el diseño general usamos mínimo 5 columnas:
         * A = label/código
         * B = :
         * C en adelante = valores / análisis
         */
        $sheetColumns = max($maxColumns, 5);

        $blankRow = fn() => array_fill(0, $sheetColumns, '');

        $row1 = $blankRow();
        $row1[3] = 'Identificación: F-PR-01-2';

        $row2 = $blankRow();
        $row2[3] = 'Revisión: 01';

        $row3 = $blankRow();
        $row3[3] = 'Inicio de Vigencia: 2025-09-19';

        $titleRow = $blankRow();
        $titleRow[0] = 'Orden de Trabajo';

        $noteRow = $blankRow();
        $noteRow[0] = '* Este documento debe ser entregado junto con los siguientes análisis requeridos *';

        $headerTable = $blankRow();
        $headerTable[0] = 'Código de Laboratorio (muestras)';
        $headerTable[1] = 'Análisis Requeridos';

        $dataRows = collect($this->payload['rows'] ?? [])
            ->map(function ($row) use ($sheetColumns) {
                $row = array_values($row);

                while (count($row) < $sheetColumns) {
                    $row[] = '';
                }

                return $row;
            })
            ->toArray();

        if (empty($dataRows)) {
            $empty = $blankRow();
            $empty[0] = 'No hay análisis requeridos';
            $dataRows[] = $empty;
        }

        return [
            $row1,
            $row2,
            $row3,
            $blankRow(),
            $titleRow,
            $blankRow(),

            ['Orden de servicio', ':', $this->payload['os'] ?? '-', ...array_fill(0, $sheetColumns - 3, '')],
            ['Informe de Ensayo', ':', $this->payload['number_report'] ?? '-', ...array_fill(0, $sheetColumns - 3, '')],
            ['Cadena de Custodia', ':', $this->payload['number_chain'] ?? '-', ...array_fill(0, $sheetColumns - 3, '')],
            ['Matriz', ':', $this->payload['matriz'] ?? '-', ...array_fill(0, $sheetColumns - 3, '')],
            ['Fecha de entrega', ':', $this->payload['date_agreed'] ?? '-', ...array_fill(0, $sheetColumns - 3, '')],
            ['Hora', ':', $this->payload['hour'] ?? '-', ...array_fill(0, $sheetColumns - 3, '')],

            $blankRow(),
            $noteRow,
            $blankRow(),
            $headerTable,

            ...$dataRows,
        ];
    }

    public function columnWidths(): array
    {
        $maxColumns = max((int) ($this->payload['maxColumns'] ?? 2), 2);
        $sheetColumns = max($maxColumns, 5);

        $widths = [
            'A' => 34,
            'B' => 4,
            'C' => 28,
            'D' => 28,
            'E' => 28,
        ];

        for ($i = 6; $i <= $sheetColumns; $i++) {
            $widths[$this->columnLetter($i)] = 28;
        }

        return $widths;
    }

    public function drawings()
    {
        $path = storage_path('app/public/logos/logo.jpg');

        if (!file_exists($path)) {
            return [];
        }

        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo GreenLab');
        $drawing->setPath($path);
        $drawing->setHeight(65);
        $drawing->setCoordinates('A1');
        $drawing->setOffsetX(10);
        $drawing->setOffsetY(8);

        return [$drawing];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            5 => [
                'font' => [
                    'bold' => true,
                    'size' => 18,
                ],
            ],
            7 => [
                'font' => [
                    'bold' => true,
                    'size' => 16,
                ],
            ],
            16 => [
                'font' => [
                    'bold' => true,
                    'size' => 10,
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $maxColumns = max((int) ($this->payload['maxColumns'] ?? 2), 2);
                $sheetColumns = max($maxColumns, 5);

                $lastColumn = $this->columnLetter($sheetColumns);
                $tableLastColumn = $this->columnLetter($maxColumns);

                $rows = $this->payload['rows'] ?? [];
                $lastRow = 16 + max(count($rows), 1);

                $sheet->setShowGridlines(false);

                $sheet->getPageSetup()
                    ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE)
                    ->setFitToWidth(1)
                    ->setFitToHeight(0);

                $sheet->mergeCells('A1:C3');
                $sheet->mergeCells("D1:{$lastColumn}1");
                $sheet->mergeCells("D2:{$lastColumn}2");
                $sheet->mergeCells("D3:{$lastColumn}3");

                $sheet->getStyle("A1:{$lastColumn}3")
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN)
                    ->getColor()
                    ->setRGB('111827');

                $sheet->getStyle("A1:{$lastColumn}3")
                    ->getBorders()
                    ->getOutline()
                    ->setBorderStyle(Border::BORDER_MEDIUM)
                    ->getColor()
                    ->setRGB('111827');

                $sheet->getStyle("A1:{$lastColumn}3")
                    ->getBorders()
                    ->getBottom()
                    ->setBorderStyle(Border::BORDER_MEDIUM)
                    ->getColor()
                    ->setRGB('16A34A');

                $sheet->getStyle("D1:{$lastColumn}3")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_RIGHT)
                    ->setVertical(Alignment::VERTICAL_CENTER);

                $sheet->getStyle("D1:{$lastColumn}1")
                    ->getFont()
                    ->setBold(true)
                    ->setSize(10);

                $sheet->getStyle("D2:{$lastColumn}3")
                    ->getFont()
                    ->setSize(10);

                $sheet->mergeCells("A5:{$lastColumn}5");

                $sheet->getStyle("A5:{$lastColumn}5")
                    ->getBorders()
                    ->getOutline()
                    ->setBorderStyle(Border::BORDER_THIN)
                    ->getColor()
                    ->setRGB('111827');

                $sheet->getStyle("A5:{$lastColumn}5")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER);

                $sheet->getStyle('A7:A12')
                    ->getFont()
                    ->setBold(true);

                $sheet->getStyle('A7:C12')
                    ->getAlignment()
                    ->setVertical(Alignment::VERTICAL_CENTER);

                $sheet->getStyle('B7:B12')
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->getStyle('C7:C12')
                    ->getBorders()
                    ->getBottom()
                    ->setBorderStyle(Border::BORDER_THIN)
                    ->getColor()
                    ->setRGB('6B7280');

                $sheet->getStyle('A7:C12')
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN)
                    ->getColor()
                    ->setRGB('D1D5DB');

                $sheet->getStyle('A7:C12')
                    ->getBorders()
                    ->getOutline()
                    ->setBorderStyle(Border::BORDER_MEDIUM)
                    ->getColor()
                    ->setRGB('111827');

                $sheet->mergeCells("A14:{$lastColumn}14");

                $sheet->getStyle("A14:{$lastColumn}14")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER)
                    ->setWrapText(true);

                $sheet->getStyle("A14:{$lastColumn}14")
                    ->getFont()
                    ->setBold(true)
                    ->setSize(9);

                /*
                 * Encabezado de tabla
                 */
                if ($maxColumns > 1) {
                    $sheet->mergeCells("B16:{$tableLastColumn}16");
                }

                $sheet->getStyle("A16:{$tableLastColumn}16")
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setRGB('92D050');

                $sheet->getStyle("A16:{$tableLastColumn}16")
                    ->getFont()
                    ->setBold(true)
                    ->setSize(10);

                $sheet->getStyle("A16:{$tableLastColumn}16")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER)
                    ->setWrapText(true);

                $sheet->getStyle("A16:{$tableLastColumn}{$lastRow}")
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN)
                    ->getColor()
                    ->setRGB('111827');

                $sheet->getStyle("A16:{$tableLastColumn}{$lastRow}")
                    ->getBorders()
                    ->getOutline()
                    ->setBorderStyle(Border::BORDER_MEDIUM)
                    ->getColor()
                    ->setRGB('111827');

                $sheet->getStyle("A16:{$tableLastColumn}16")
                    ->getBorders()
                    ->getOutline()
                    ->setBorderStyle(Border::BORDER_MEDIUM)
                    ->getColor()
                    ->setRGB('111827');

                $sheet->getStyle("A17:{$tableLastColumn}{$lastRow}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER)
                    ->setWrapText(true);

                if ($lastRow >= 17) {
                    $sheet->getStyle("A17:A{$lastRow}")
                        ->getFont()
                        ->setBold(true);
                }

                /*
                 * Limpiar rellenos raros fuera de la tabla
                 */
                $sheet->getStyle("A1:{$lastColumn}15")
                    ->getFill()
                    ->setFillType(Fill::FILL_NONE);

                /*
                 * Alturas
                 */
                $sheet->getRowDimension(1)->setRowHeight(26);
                $sheet->getRowDimension(2)->setRowHeight(24);
                $sheet->getRowDimension(3)->setRowHeight(24);
                $sheet->getRowDimension(4)->setRowHeight(10);
                $sheet->getRowDimension(5)->setRowHeight(28);
                $sheet->getRowDimension(6)->setRowHeight(10);

                for ($i = 7; $i <= 12; $i++) {
                    $sheet->getRowDimension($i)->setRowHeight(20);
                }

                $sheet->getRowDimension(13)->setRowHeight(8);
                $sheet->getRowDimension(14)->setRowHeight(24);
                $sheet->getRowDimension(15)->setRowHeight(8);
                $sheet->getRowDimension(16)->setRowHeight(34);

                for ($i = 17; $i <= $lastRow; $i++) {
                    $sheet->getRowDimension($i)->setRowHeight(28);
                }

                /*
                 * Tamaños de fuente generales
                 */
                $sheet->getStyle("A1:{$lastColumn}{$lastRow}")
                    ->getFont()
                    ->setName('Arial');

                $sheet->getStyle("A7:{$lastColumn}{$lastRow}")
                    ->getFont()
                    ->setSize(10);

                /*
                 * Quitar columnas extra visuales cuando la tabla usa menos columnas.
                 */
                if ($sheetColumns > $maxColumns) {
                    $extraStart = $this->columnLetter($maxColumns + 1);
                    $sheet->getStyle("{$extraStart}16:{$lastColumn}{$lastRow}")
                        ->getBorders()
                        ->getAllBorders()
                        ->setBorderStyle(Border::BORDER_NONE);
                }
            },
        ];
    }

    private function columnLetter(int $columnNumber): string
    {
        $letter = '';

        while ($columnNumber > 0) {
            $mod = ($columnNumber - 1) % 26;
            $letter = chr(65 + $mod) . $letter;
            $columnNumber = intdiv($columnNumber - $mod, 26);
        }

        return $letter;
    }
}
