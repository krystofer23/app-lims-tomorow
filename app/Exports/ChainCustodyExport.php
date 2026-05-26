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
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
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
        /*
         * $maxParameters = cantidad máxima de análisis requeridos.
         * +1 porque la primera columna es Código de Laboratorio.
         */
        $maxParameters = max((int) ($this->payload['maxColumns'] ?? 1), 1);
        $totalColumns = $maxParameters + 1;

        $blankRow = fn() => array_fill(0, $totalColumns, '');

        $row1 = $blankRow();
        $row1[2] = 'Identificación: F-PR-01-2';

        $row2 = $blankRow();
        $row2[2] = 'Revisión: 01';

        $row3 = $blankRow();
        $row3[2] = 'Inicio de Vigencia: 2025-09-19';

        $titleRow = $blankRow();
        $titleRow[0] = 'Orden de Trabajo';

        $noteRow = $blankRow();
        $noteRow[0] = '* Este documento debe ser entregado junto con los siguientes análisis requeridos *';

        $headerTable = $blankRow();
        $headerTable[0] = 'Código de Laboratorio (muestras)';
        $headerTable[1] = 'Análisis Requeridos';

        $dataRows = [];

        foreach (($this->payload['parameters'] ?? []) as $param) {
            $row = [];

            $row[] = $param['cod_lab'] ?? '-';

            foreach (($param['parameters'] ?? []) as $item) {
                $row[] = $item['parameter']['description'] ?? '-';
            }

            while (count($row) < $totalColumns) {
                $row[] = '-';
            }

            $dataRows[] = $row;
        }

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

            ['Orden de servicio:', $this->payload['os'] ?? '-', ...array_fill(0, max($totalColumns - 3, 0), '')],
            ['Informe de Ensayo:', $this->payload['number_report'] ?? '-', ...array_fill(0, max($totalColumns - 3, 0), '')],
            ['Cadena de Custodia:', $this->payload['number_chain'] ?? '-', ...array_fill(0, max($totalColumns - 3, 0), '')],
            ['Matriz:', $this->payload['matrix'] ?? '-', ...array_fill(0, max($totalColumns - 3, 0), '')],
            ['Fecha de entrega:', $this->payload['delivery_date'] ?? '-', ...array_fill(0, max($totalColumns - 3, 0), '')],
            ['Hora:', $this->payload['hour'] ?? '-', ...array_fill(0, max($totalColumns - 3, 0), '')],

            $blankRow(),
            $noteRow,
            $blankRow(),
            $headerTable,

            ...$dataRows,
        ];
    }

    public function columnWidths(): array
    {
        $maxParameters = max((int) ($this->payload['maxColumns'] ?? 1), 1);
        $totalColumns = $maxParameters + 1;

        $widths = [
            'A' => 32,
        ];

        for ($i = 2; $i <= $totalColumns; $i++) {
            $widths[$this->columnLetter($i)] = 30;
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
        $drawing->setHeight(70);
        $drawing->setCoordinates('A1');
        $drawing->setOffsetX(10);
        $drawing->setOffsetY(5);

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

                $maxParameters = max((int) ($this->payload['maxColumns'] ?? 1), 1);
                $totalColumns = $maxParameters + 1;

                $lastColumn = $this->columnLetter($totalColumns);

                $parameters = $this->payload['parameters'] ?? [];
                $lastRow = 16 + max(count($parameters), 1);

                $sheet->setShowGridlines(false);

                $sheet->getPageSetup()
                    ->setOrientation(PageSetup::ORIENTATION_PORTRAIT)
                    ->setFitToWidth(1)
                    ->setFitToHeight(0);

                /*
                 * Header superior
                 */
                $sheet->mergeCells('A1:B3');
                $sheet->mergeCells("C1:{$lastColumn}1");
                $sheet->mergeCells("C2:{$lastColumn}2");
                $sheet->mergeCells("C3:{$lastColumn}3");

                $sheet->getStyle("A1:{$lastColumn}3")
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN)
                    ->getColor()
                    ->setRGB('111827');

                $sheet->getStyle("A1:{$lastColumn}3")
                    ->getBorders()
                    ->getBottom()
                    ->setBorderStyle(Border::BORDER_THIN)
                    ->getColor()
                    ->setRGB('16A34A');

                $sheet->getStyle("C1:{$lastColumn}3")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_RIGHT)
                    ->setVertical(Alignment::VERTICAL_CENTER);

                /*
                 * Título
                 */
                $sheet->mergeCells("A5:{$lastColumn}5");

                $sheet->getStyle("A5:{$lastColumn}5")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER);

                $sheet->getStyle("A5:{$lastColumn}5")
                    ->getFont()
                    ->setBold(true)
                    ->setSize(18);

                /*
                 * Datos generales
                 */
                $sheet->getStyle('A7:A12')
                    ->getFont()
                    ->setBold(true);

                $sheet->getStyle('B7:B12')
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->getStyle("A7:{$lastColumn}12")
                    ->getAlignment()
                    ->setVertical(Alignment::VERTICAL_CENTER);

                $sheet->getStyle("A7:{$lastColumn}12")
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN)
                    ->getColor()
                    ->setRGB('D1D5DB');

                /*
                 * Nota
                 */
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
                if ($totalColumns >= 2) {
                    $sheet->mergeCells("B16:{$lastColumn}16");
                }

                $sheet->getStyle("A16:{$lastColumn}16")
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setRGB('92D050');

                $sheet->getStyle("A16:{$lastColumn}16")
                    ->getFont()
                    ->setBold(true)
                    ->setSize(10);

                $sheet->getStyle("A16:{$lastColumn}16")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER)
                    ->setWrapText(true);

                /*
                 * Tabla principal
                 */
                $sheet->getStyle("A16:{$lastColumn}{$lastRow}")
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN)
                    ->getColor()
                    ->setRGB('111827');

                $sheet->getStyle("A16:{$lastColumn}{$lastRow}")
                    ->getBorders()
                    ->getOutline()
                    ->setBorderStyle(Border::BORDER_THIN)
                    ->getColor()
                    ->setRGB('111827');

                $sheet->getStyle("A17:{$lastColumn}{$lastRow}")
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
                 * Fuente general
                 */
                $sheet->getStyle("A1:{$lastColumn}{$lastRow}")
                    ->getFont()
                    ->setName('Arial')
                    ->setSize(10);

                /*
                 * Alturas
                 */
                $sheet->getRowDimension(1)->setRowHeight(24);
                $sheet->getRowDimension(2)->setRowHeight(24);
                $sheet->getRowDimension(3)->setRowHeight(24);
                $sheet->getRowDimension(4)->setRowHeight(10);
                $sheet->getRowDimension(5)->setRowHeight(30);
                $sheet->getRowDimension(6)->setRowHeight(10);

                for ($i = 7; $i <= 12; $i++) {
                    $sheet->getRowDimension($i)->setRowHeight(22);
                }

                $sheet->getRowDimension(13)->setRowHeight(8);
                $sheet->getRowDimension(14)->setRowHeight(25);
                $sheet->getRowDimension(15)->setRowHeight(8);
                $sheet->getRowDimension(16)->setRowHeight(36);

                for ($i = 17; $i <= $lastRow; $i++) {
                    $sheet->getRowDimension($i)->setRowHeight(35);
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
