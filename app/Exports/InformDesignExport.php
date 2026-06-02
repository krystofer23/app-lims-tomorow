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
        $totalRows = $this->getTotalRows();
        $totalColumns = $this->getTotalColumns();

        $rows = array_fill(0, $totalRows, array_fill(0, $totalColumns, ''));

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

        $rows[3][3] = $this->mapData['company'] ?? '';
        $rows[4][3] = $this->mapData['direction'] ?? '';
        $rows[5][3] = $this->mapData['application'] ?? '';
        $rows[6][3] = $this->mapData['reference'] ?? '';
        $rows[7][3] = $this->mapData['project'] ?? '';
        $rows[8][3] = $this->mapData['origin'] ?? '';

        $rows[9][3] = $this->mapData['sampling_performed_by'] ?? '';
        $rows[10][3] = $this->mapData['sample_quantity'] ?? '';
        $rows[11][3] = $this->mapData['product'] ?? '';
        $rows[12][3] = $this->mapData['sampling_plan'] ?? 'PM N°';
        $rows[13][3] = $this->mapData['date_of_receipt'] ?? '';
        $rows[14][3] = $this->mapData['time_of_receipt'] ?? '';
        $rows[15][3] = $this->mapData['test_period'] ?? '';
        $rows[16][3] = $this->mapData['date_of_issue'] ?? '';

        $rows[18][0] = 'Gracias por utilizar los servicios GREENLAB PERÚ S.A.C Póngase en contacto con el Ejecutivo de Ventas, si desea información adicional o cualquier aclaración';
        $rows[19][0] = 'que pertenezca a este informe.';
        $rows[20][0] = 'Informe Autorizado por:';

        /*
        |--------------------------------------------------------------------------
        | RESULTADOS DE ANÁLISIS
        |--------------------------------------------------------------------------
        */

        $rows[29][2] = 'INFORME DE ENSAYO N° XXX-XX-I';
        $rows[30][2] = 'CON VALOR OFICIAL';

        $rows[31][0] = 'I. RESULTADOS DE ANALISIS';

        $rows[32][0] = 'Código del Laboratorio';
        $rows[33][0] = 'Código de la muestra';
        $rows[34][0] = 'Fecha de muestreo';
        $rows[35][0] = 'Hora de muestreo';
        $rows[36][0] = 'Categoría';
        $rows[37][0] = 'Coordenadas (WGS-84)';

        $samples = $this->mapData['samples'] ?? [];
        $category = $this->mapData['category'] ?? '-';

        $firstResultColumn = 5; // F

        foreach ($samples as $index => $sample) {
            $column = $firstResultColumn + $index;

            $rows[32][$column] = $sample['code_lab'] ?? '-';
            $rows[33][$column] = $sample['code_sample'] ?? '-';
            $rows[34][$column] = $sample['date_sample'] ?? '-';
            $rows[35][$column] = $sample['hour_sample'] ?? '-';
            $rows[36][$column] = $category;

            $coordinate = $sample['coordinate'] ?? '-';
            $parsedCoordinate = $this->parseCoordinate($coordinate);

            $rows[37][$column] = 'E: ' . $parsedCoordinate['east'];
            $rows[38][$column] = 'N: ' . $parsedCoordinate['north'];
        }

        $rows[39][0] = 'Descripción del punto de Muestreo';

        $rows[40][0] = 'Tipo de Ensayo';
        $rows[40][3] = 'Unidad';
        $rows[40][4] = 'L.C.M.';
        $rows[40][5] = 'Resultados';

        foreach ($samples as $index => $sample) {
            $column = $firstResultColumn + $index;
            // $rows[40][$column] = $sample['code_lab'] ?? 'Resultado';
        }

        $currentRow = 41;

        foreach (($this->mapData['analysis_groups'] ?? []) as $group) {
            $rows[$currentRow][0] = $group['type_of_analysis'] ?? 'SIN TIPO DE ENSAYO';
            $currentRow++;

            foreach (($group['parameters'] ?? []) as $parameter) {
                $rows[$currentRow][0] = $parameter['parameter'] ?? '-';
                $rows[$currentRow][3] = $parameter['unit'] ?? '-';
                $rows[$currentRow][4] = $parameter['lcm'] ?? '-';

                foreach (($parameter['results'] ?? []) as $index => $result) {
                    $column = $firstResultColumn + $index;

                    $rows[$currentRow][$column] = $result['result'] ?? '';
                }

                $currentRow++;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | II. MÉTODOS Y REFERENCIAS
        |--------------------------------------------------------------------------
        */

        $methodsStartRow = $currentRow + 2;

        $rows[$methodsStartRow][0] = 'II. MÉTODOS Y REFERENCIAS';

        $rows[$methodsStartRow + 2][0] = 'TIPO ENSAYO';
        $rows[$methodsStartRow + 2][3] = 'NORMA REFERENCIA';
        $rows[$methodsStartRow + 2][7] = 'TITULO';

        $methodRow = $methodsStartRow + 3;

        foreach (($this->mapData['analysis_groups_methodology'] ?? []) as $group) {
            foreach (($group['parameters'] ?? []) as $parameter) {
                $rows[$methodRow][0] = '' . ($parameter['parameter'] ?? '-');
                $rows[$methodRow][3] = $parameter['code'] ?? '-';
                $rows[$methodRow][7] = $parameter['title'] ?? '-';

                $methodRow++;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | III. PROCEDIMIENTOS DE MUESTREO
        |--------------------------------------------------------------------------
        */

        $procedureStartRow = $methodRow + 2;

        $rows[$procedureStartRow][0] = 'III. PROCEDIMIENTOS DE MUESTREO';

        $procedures = [
            'P-01 Procedimiento General de Muestreo',
            'P-02 Transporte, almacenamiento-mantenimiento de equipos y materiales',
            'P-03 Aseguramiento de Calidad en el Muestreo',
            'P-04 Análisis de Mediciones de Agua en Campo',
            'P-07 Muestreo de calidad de aguas',
        ];

        $procedureRow = $procedureStartRow + 2;

        foreach ($procedures as $procedure) {
            $rows[$procedureRow][0] = $procedure;
            $procedureRow++;
        }

        /*
        |--------------------------------------------------------------------------
        | IV. OBSERVACIONES
        |--------------------------------------------------------------------------
        */

        $observationStartRow = $procedureRow + 2;

        $rows[$observationStartRow][0] = 'IV. OBSERVACIONES';

        $rows[$observationStartRow + 2][3] = '- Los resultados presentados corresponden sólo a la muestra indicada, según la cadena de custodia correspondiente.';
        $rows[$observationStartRow + 3][3] = '- El tiempo de conservación de la muestra se mantendrá desde la recepción y en función al período de perecibilidad del parámetro que se está analizando.';

        $rows[$observationStartRow + 6][4] = '***FIN DEL INFORME***';

        return $rows;
    }

    public function title(): string
    {
        return 'Primera página';
    }

    public function columnWidths(): array
    {
        $widths = [
            'A' => 22,
            'B' => 18,
            'C' => 5,
            'D' => 18,
            'E' => 10,
        ];

        $firstResultColumn = 5; // F

        for ($i = 0; $i < $this->getSamplesCount(); $i++) {
            $widths[$this->columnLetter($firstResultColumn + $i)] = 17;
        }

        return $widths;
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 16]],
            2 => ['font' => ['bold' => true, 'size' => 16]],
            30 => ['font' => ['bold' => true, 'size' => 16]],
            31 => ['font' => ['bold' => true, 'size' => 16]],
            32 => ['font' => ['bold' => true]],
            41 => ['font' => ['bold' => true]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $totalRows = $this->getTotalRows();
                $lastColumn = $this->columnLetter($this->getTotalColumns() - 1);

                $sheet->getPageSetup()
                    ->setOrientation(PageSetup::ORIENTATION_PORTRAIT)
                    ->setPaperSize(PageSetup::PAPERSIZE_A4)
                    ->setFitToWidth(1)
                    ->setFitToHeight(0);

                $sheet->getPageMargins()->setTop(0.25);
                $sheet->getPageMargins()->setBottom(0.25);
                $sheet->getPageMargins()->setLeft(0.20);
                $sheet->getPageMargins()->setRight(0.20);

                /*
                |--------------------------------------------------------------------------
                | MERGES BASE
                |--------------------------------------------------------------------------
                */

                $sheet->mergeCells('C1:H1');
                $sheet->mergeCells('C2:H2');

                $sheet->mergeCells('A19:K19');
                $sheet->mergeCells('A20:K20');

                $sheet->mergeCells('C30:H30');
                $sheet->mergeCells('C31:H31');

                $sheet->mergeCells('A32:L32');

                // $sheet->mergeCells('G33:H33');
                // $sheet->mergeCells('I33:J33');
                // $sheet->mergeCells('K33:L33');

                // $sheet->mergeCells('G34:H34');
                // $sheet->mergeCells('I34:J34');
                // $sheet->mergeCells('K34:L34');

                // $sheet->mergeCells('G35:H35');
                // $sheet->mergeCells('I35:J35');
                // $sheet->mergeCells('K35:L35');

                // $sheet->mergeCells('G36:H36');
                // $sheet->mergeCells('I36:J36');
                // $sheet->mergeCells('K36:L36');

                // $sheet->mergeCells('I37:L37');

                // $sheet->mergeCells('G38:H38');
                // $sheet->mergeCells('I38:J38');
                // $sheet->mergeCells('K38:L38');

                // $sheet->mergeCells('G39:H39');
                // $sheet->mergeCells('I39:J39');
                // $sheet->mergeCells('K39:L39');

                // $sheet->mergeCells('I41:L41');

                /*
                |--------------------------------------------------------------------------
                | ESTILO GENERAL
                |--------------------------------------------------------------------------
                */

                $sheet->getStyle("A1:{$lastColumn}{$totalRows}")->applyFromArray([
                    'font' => [
                        'name' => 'Arial',
                        'size' => 9,
                        'color' => ['rgb' => '111111'],
                    ],
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FFFFFF'],
                    ],
                ]);

                $sheet->getStyle('A1:L2')
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->getStyle("A30:{$lastColumn}31")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->getStyle('A4:A17')->getFont()->setBold(true);
                $sheet->getStyle('A21')->getFont()->setBold(true);

                $sheet->getStyle('A32')->getFont()->setBold(true);
                $sheet->getStyle('A33:A41')->getFont()->setBold(true);
                $sheet->getStyle("A41:{$lastColumn}41")->getFont()->setBold(true);

                $sheet->getStyle("F33:{$lastColumn}41")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->getStyle("D42:{$lastColumn}{$totalRows}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $this->bottomBorder($sheet, 'A18:K18');

                $this->topBorder($sheet, "A32:{$lastColumn}32");
                $this->bottomBorder($sheet, "A32:{$lastColumn}32");

                $this->topBorder($sheet, "A33:{$lastColumn}33");
                $this->bottomBorder($sheet, "A33:{$lastColumn}33");

                $this->bottomBorder($sheet, "A37:{$lastColumn}37");

                $this->topBorder($sheet, "A41:{$lastColumn}41");
                $this->bottomBorder($sheet, "A41:{$lastColumn}41");

                /*
                |--------------------------------------------------------------------------
                | ALTURAS DE FILAS
                |--------------------------------------------------------------------------
                */

                for ($i = 1; $i <= $totalRows; $i++) {
                    $sheet->getRowDimension($i)->setRowHeight(18);
                }

                foreach (range(4, 17) as $row) {
                    $sheet->getRowDimension($row)->setRowHeight(30);
                }

                foreach ([3, 18, 22, 23, 24, 25, 26, 27, 28, 29, 36, 40] as $row) {
                    $sheet->getRowDimension($row)->setRowHeight(24);
                }

                $sheet->getRowDimension(19)->setRowHeight(20);
                $sheet->getRowDimension(20)->setRowHeight(20);
                $sheet->getRowDimension(30)->setRowHeight(22);
                $sheet->getRowDimension(31)->setRowHeight(22);
                $sheet->getRowDimension(32)->setRowHeight(24);
                $sheet->getRowDimension(41)->setRowHeight(24);

                /*
                |--------------------------------------------------------------------------
                | ESTILO DINÁMICO DE RESULTADOS
                |--------------------------------------------------------------------------
                */

                $currentRow = 42;

                foreach (($this->mapData['analysis_groups'] ?? []) as $group) {
                    $sheet->getStyle("A{$currentRow}:{$lastColumn}{$currentRow}")
                        ->getFont()
                        ->setBold(true);

                    $sheet->getStyle("A{$currentRow}:{$lastColumn}{$currentRow}")
                        ->getAlignment()
                        ->setHorizontal(Alignment::HORIZONTAL_LEFT);

                    $this->topBorder($sheet, "A{$currentRow}:{$lastColumn}{$currentRow}");
                    $this->bottomBorder($sheet, "A{$currentRow}:{$lastColumn}{$currentRow}");

                    $currentRow++;

                    foreach (($group['parameters'] ?? []) as $parameter) {
                        $this->bottomBorder($sheet, "A{$currentRow}:{$lastColumn}{$currentRow}");

                        $sheet->getStyle("D{$currentRow}:{$lastColumn}{$currentRow}")
                            ->getAlignment()
                            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                        $currentRow++;
                    }
                }

                $methodsStartRow = $this->getMethodsStartExcelRow();
                $methodsHeaderRow = $methodsStartRow + 2;
                $methodsFirstDataRow = $methodsHeaderRow + 1;
                $methodsLastDataRow = $methodsFirstDataRow + $this->getMethodologyRowsCount() - 1;

                $procedureStartRow = $methodsLastDataRow + 3;
                $procedureFirstDataRow = $procedureStartRow + 2;
                $procedureLastDataRow = $procedureFirstDataRow + 4;

                $observationStartRow = $procedureLastDataRow + 3;
                $observationTextRow1 = $observationStartRow + 2;
                $observationTextRow2 = $observationStartRow + 3;
                $finalReportRow = $observationStartRow + 6;

                /*
                 * II. MÉTODOS Y REFERENCIAS
                 */
                $sheet->mergeCells("A{$methodsStartRow}:{$lastColumn}{$methodsStartRow}");
                $sheet->getStyle("A{$methodsStartRow}:L{$methodsStartRow}")
                    ->getFont()
                    ->setBold(true);

                $this->topBorder($sheet, "A{$methodsStartRow}:L{$methodsStartRow}");
                $this->bottomBorder($sheet, "A{$methodsStartRow}:L{$methodsStartRow}");

                $sheet->mergeCells("A{$methodsHeaderRow}:C{$methodsHeaderRow}");
                $sheet->mergeCells("D{$methodsHeaderRow}:G{$methodsHeaderRow}");
                $sheet->mergeCells("H{$methodsHeaderRow}:L{$methodsHeaderRow}");

                $sheet->getStyle("A{$methodsHeaderRow}:L{$methodsHeaderRow}")
                    ->getFont()
                    ->setBold(true);

                $sheet->getStyle("A{$methodsHeaderRow}:L{$methodsHeaderRow}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $this->topBorder($sheet, "A{$methodsHeaderRow}:L{$methodsHeaderRow}");
                $this->bottomBorder($sheet, "A{$methodsHeaderRow}:L{$methodsHeaderRow}");

                for ($row = $methodsFirstDataRow; $row <= $methodsLastDataRow; $row++) {
                    $sheet->mergeCells("A{$row}:C{$row}");
                    $sheet->mergeCells("D{$row}:G{$row}");
                    $sheet->mergeCells("H{$row}:L{$row}");

                    $sheet->getRowDimension($row)->setRowHeight(36);
                }

                $this->bottomBorder($sheet, "A{$methodsLastDataRow}:L{$methodsLastDataRow}");

                $sheet->mergeCells("A{$procedureStartRow}:{$lastColumn}{$procedureStartRow}");
                $sheet->getStyle("A{$procedureStartRow}:L{$procedureStartRow}")
                    ->getFont()
                    ->setBold(true);

                $this->topBorder($sheet, "A{$procedureStartRow}:L{$procedureStartRow}");
                $this->bottomBorder($sheet, "A{$procedureStartRow}:L{$procedureStartRow}");

                for ($row = $procedureFirstDataRow; $row <= $procedureLastDataRow; $row++) {
                    $sheet->mergeCells("A{$row}:{$lastColumn}{$row}");
                    $sheet->getRowDimension($row)->setRowHeight(22);
                }

                $this->bottomBorder($sheet, "A{$procedureLastDataRow}:L{$procedureLastDataRow}");

                $sheet->mergeCells("A{$observationStartRow}:{$lastColumn}{$observationStartRow}");
                $sheet->getStyle("A{$observationStartRow}:L{$observationStartRow}")
                    ->getFont()
                    ->setBold(true);

                $this->topBorder($sheet, "A{$observationStartRow}:L{$observationStartRow}");
                $this->bottomBorder($sheet, "A{$observationStartRow}:L{$observationStartRow}");

                $sheet->mergeCells("D{$observationTextRow1}:L{$observationTextRow1}");
                $sheet->mergeCells("D{$observationTextRow2}:L{$observationTextRow2}");

                $sheet->getRowDimension($observationTextRow1)->setRowHeight(30);
                $sheet->getRowDimension($observationTextRow2)->setRowHeight(38);

                $sheet->mergeCells("A{$finalReportRow}:{$lastColumn}{$finalReportRow}");
                $sheet->getStyle("A{$finalReportRow}:L{$finalReportRow}")
                    ->getFont()
                    ->setBold(true);

                $sheet->getStyle("A{$finalReportRow}:L{$finalReportRow}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $this->topBorder($sheet, "A{$finalReportRow}:L{$finalReportRow}");

                /*
                |--------------------------------------------------------------------------
                | CONFIG FINAL
                |--------------------------------------------------------------------------
                */

                $sheet->setShowGridlines(false);
                $sheet->getPageSetup()->setPrintArea("A1:{$lastColumn}{$totalRows}");
            },
        ];
    }

    private function getAnalysisRowsCount(): int
    {
        $count = 0;

        foreach (($this->mapData['analysis_groups'] ?? []) as $group) {
            $count++;

            foreach (($group['parameters'] ?? []) as $parameter) {
                $count++;
            }
        }

        return $count;
    }

    private function getMethodsStartExcelRow(): int
    {
        /*
         * La tabla de resultados inicia en fila 42.
         * Luego se suma la cantidad de filas dinámicas.
         * Después se dejan 2 filas de separación.
         */
        return 42 + $this->getAnalysisRowsCount() + 2;
    }

    private function getTotalRows(): int
    {
        $methodsStartRow = $this->getMethodsStartExcelRow();

        return $methodsStartRow
            + 3 // título, espacio, cabecera
            + $this->getMethodologyRowsCount()
            + 3 // separación antes de procedimientos
            + 7 // procedimientos
            + 8; // observaciones y fin
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

    private function getMethodologyRowsCount(): int
    {
        $count = 0;

        foreach (($this->mapData['analysis_groups_methodology'] ?? []) as $group) {
            foreach (($group['parameters'] ?? []) as $parameter) {
                $count++;
            }
        }

        return max($count, 1);
    }

    private function parseCoordinate(?string $coordinate): array
    {
        $coordinate = trim((string) $coordinate);

        if ($coordinate === '' || $coordinate === '-') {
            return [
                'east' => '-',
                'north' => '-',
            ];
        }

        $east = '-';
        $north = '-';

        if (preg_match('/E\s*:\s*([^\r\n,;]+).*?N\s*:\s*([^\r\n,;]+)/is', $coordinate, $matches)) {
            $east = trim($matches[1] ?? '-');
            $north = trim($matches[2] ?? '-');
        } elseif (str_contains($coordinate, ',')) {
            $parts = array_map('trim', explode(',', $coordinate));

            $east = $parts[0] ?? '-';
            $north = $parts[1] ?? '-';
        } else {
            $east = $coordinate;
        }

        return [
            'east' => $east ?: '-',
            'north' => $north ?: '-',
        ];
    }

    private int $baseColumns = 5;

    private function getSamplesCount(): int
    {
        return max(count($this->mapData['samples'] ?? []), 1);
    }

    private function getTotalColumns(): int
    {
        return $this->baseColumns + $this->getSamplesCount();
    }

    private function columnLetter(int $columnIndex): string
    {
        $letter = '';
        $columnIndex++;

        while ($columnIndex > 0) {
            $mod = ($columnIndex - 1) % 26;
            $letter = chr(65 + $mod) . $letter;
            $columnIndex = intdiv($columnIndex - $mod, 26);
        }

        return $letter;
    }
}
