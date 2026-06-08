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

    private int $totalColumns = 9; // A:I
    private int $firstResultColumn = 5; // F en índice base 0
    private int $maxResultsPerBlock = 4;
    private int $firstResultsExcelRow = 30;

    public function __construct(array $_mapData)
    {
        $this->mapData = $_mapData;
    }

    public function array(): array
    {
        $totalRows = $this->getTotalRows();
        $totalColumns = $this->getTotalColumns();

        $rows = array_fill(0, $totalRows, array_fill(0, $totalColumns, ''));

        /*
        |--------------------------------------------------------------------------
        | PÁGINA 1 - DATOS GENERALES
        |--------------------------------------------------------------------------
        */

        $reportNumber = $this->mapData['report_number'] ?? 'XXX-XX-I';

        $this->setCell($rows, 1, 2, "INFORME DE ENSAYO N° {$reportNumber}");
        $this->setCell($rows, 2, 2, 'CON VALOR OFICIAL');

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

        foreach ($labels as $excelRow => $label) {
            $this->setCell($rows, $excelRow, 0, $label);
            $this->setCell($rows, $excelRow, 2, ':');
        }

        $this->setCell($rows, 4, 3, $this->mapData['company'] ?? '');
        $this->setCell($rows, 5, 3, $this->mapData['direction'] ?? '');
        $this->setCell($rows, 6, 3, $this->mapData['application'] ?? '');
        $this->setCell($rows, 7, 3, $this->mapData['reference'] ?? '');
        $this->setCell($rows, 8, 3, $this->mapData['project'] ?? '');
        $this->setCell($rows, 9, 3, $this->mapData['origin'] ?? '');
        $this->setCell($rows, 10, 3, $this->mapData['sampling_performed_by'] ?? '');
        $this->setCell($rows, 11, 3, $this->mapData['sample_quantity'] ?? '');
        $this->setCell($rows, 12, 3, $this->mapData['product'] ?? '');
        $this->setCell($rows, 13, 3, $this->mapData['sampling_plan'] ?? 'NO APLICA');
        $this->setCell($rows, 14, 3, $this->mapData['date_of_receipt'] ?? '');
        $this->setCell($rows, 15, 3, $this->mapData['time_of_receipt'] ?? '');
        $this->setCell($rows, 16, 3, $this->mapData['test_period'] ?? '');
        $this->setCell($rows, 17, 3, $this->mapData['date_of_issue'] ?? '');

        $this->setCell($rows, 19, 0, 'Gracias por utilizar los servicios GREENLAB PERÚ S.A.C. Póngase en contacto con el Ejecutivo de Ventas, si desea información adicional o cualquier aclaración');
        $this->setCell($rows, 20, 0, 'que pertenezca a este informe.');
        $this->setCell($rows, 21, 0, 'Informe Autorizado por:');

        /*
        |--------------------------------------------------------------------------
        | RESULTADOS DE ANÁLISIS - BLOQUES DE 4 CADENAS
        |--------------------------------------------------------------------------
        */

        $currentExcelRow = $this->firstResultsExcelRow;
        $sampleChunks = $this->getSampleChunks();

        foreach ($sampleChunks as $chunkIndex => $sampleChunk) {
            $isLastChunk = $chunkIndex === count($sampleChunks) - 1;

            $currentExcelRow = $this->fillResultsBlock(
                $rows,
                $currentExcelRow,
                $sampleChunk,
                $chunkIndex,
                $isLastChunk
            );

            $currentExcelRow += 3;
        }

        /*
        |--------------------------------------------------------------------------
        | II. MÉTODOS Y REFERENCIAS
        |--------------------------------------------------------------------------
        */

        $methodsStartRow = $this->getMethodsStartExcelRow();

        $this->setCell($rows, $methodsStartRow, 0, 'II. MÉTODOS Y REFERENCIAS');

        $methodsHeaderRow = $methodsStartRow + 2;

        $this->setCell($rows, $methodsHeaderRow, 0, 'TIPO ENSAYO');
        $this->setCell($rows, $methodsHeaderRow, 3, 'NORMA REFERENCIA');
        $this->setCell($rows, $methodsHeaderRow, 5, 'TITULO');

        $methodRow = $methodsHeaderRow + 1;
        $hasMethodRows = false;

        foreach (($this->mapData['analysis_groups_methodology'] ?? []) as $group) {
            foreach (($group['parameters'] ?? []) as $parameter) {
                $this->setCell($rows, $methodRow, 0, $parameter['parameter'] ?? '-');
                $this->setCell($rows, $methodRow, 3, $parameter['code'] ?? '-');
                $this->setCell($rows, $methodRow, 5, $parameter['title'] ?? '-');

                $methodRow++;
                $hasMethodRows = true;
            }
        }

        if (!$hasMethodRows) {
            $this->setCell($rows, $methodRow, 0, '-');
            $this->setCell($rows, $methodRow, 3, '-');
            $this->setCell($rows, $methodRow, 5, '-');
            $methodRow++;
        }

        /*
        |--------------------------------------------------------------------------
        | III. PROCEDIMIENTOS DE MUESTREO
        |--------------------------------------------------------------------------
        */

        $samplingPerformedBy = $this->mapData['sampling_performed_by'] ?? '';
        $showProcedures = $samplingPerformedBy === 'GREENLAB PERÚ S.A.C.';

        if ($showProcedures) {
            $procedureStartRow = $methodRow + 2;

            $this->setCell($rows, $procedureStartRow, 0, 'III. PROCEDIMIENTOS DE MUESTREO');

            $procedureRow = $procedureStartRow + 2;
            $procedures = $this->getProcedures();

            foreach ($procedures as $procedure) {
                $this->setCell($rows, $procedureRow, 0, $procedure);
                $procedureRow++;
            }

            $observationStartRow = $procedureRow + 2;
            $observationTitle = 'IV. OBSERVACIONES';
        } else {
            $observationStartRow = $methodRow + 2;
            $observationTitle = 'III. OBSERVACIONES';
        }

        /*
        |--------------------------------------------------------------------------
        | OBSERVACIONES
        |--------------------------------------------------------------------------
        */

        $this->setCell($rows, $observationStartRow, 0, $observationTitle);

        $this->setCell(
            $rows,
            $observationStartRow + 2,
            3,
            '- Los resultados presentados corresponden sólo a la muestra indicada, según la cadena de custodia correspondiente.'
        );

        $this->setCell(
            $rows,
            $observationStartRow + 3,
            3,
            '- El tiempo de conservación de la muestra se mantendrá desde la recepción y en función al período de perecibilidad del parámetro que se está analizando.'
        );

        $this->setCell(
            $rows,
            $observationStartRow + 4,
            3,
            '- Descripción del punto de muestreo: ' . ($this->mapData['sampling_point_description'] ?? '-')
        );

        $this->setCell($rows, $observationStartRow + 7, 0, '***FIN DEL INFORME***');

        return $rows;
    }

    private function fillResultsBlock(
        array &$rows,
        int $startExcelRow,
        array $sampleChunk,
        int $chunkIndex,
        bool $isLastChunk
    ): int {
        $reportNumber = $this->mapData['report_number'] ?? 'XXX-XX-I';
        $category = $this->mapData['category'] ?? $this->mapData['product'] ?? '-';
        $subCategory = $this->mapData['sub_category'] ?? $this->mapData['product'] ?? '-';

        $this->setCell($rows, $startExcelRow, 2, "INFORME DE ENSAYO N° {$reportNumber}");
        $this->setCell($rows, $startExcelRow + 1, 2, 'CON VALOR OFICIAL');

        $this->setCell($rows, $startExcelRow + 2, 0, 'I. RESULTADOS DE ANÁLISIS');

        $infoRows = [
            $startExcelRow + 3 => 'Código del Laboratorio',
            $startExcelRow + 4 => 'Código de la muestra ¤',
            $startExcelRow + 5 => 'Fecha muestreo ¤',
            $startExcelRow + 6 => 'Hora muestreo ¤',
            $startExcelRow + 7 => 'Categoría ¤',
            $startExcelRow + 8 => 'Sub categoría',
            $startExcelRow + 9 => 'Coordenadas (WGS-84) ¤',
            $startExcelRow + 10 => '',
        ];

        foreach ($infoRows as $excelRow => $label) {
            $this->setCell($rows, $excelRow, 0, $label);

            if ($label !== '') {
                $this->setCell($rows, $excelRow, 2, ':');
            }
        }

        foreach ($sampleChunk as $localIndex => $sample) {
            $column = $this->firstResultColumn + $localIndex;

            $this->setCell($rows, $startExcelRow + 3, $column, $sample['code_lab'] ?? '-');
            $this->setCell($rows, $startExcelRow + 4, $column, $sample['code_sample'] ?? '-');
            $this->setCell($rows, $startExcelRow + 5, $column, $sample['date_sample'] ?? '-');
            $this->setCell($rows, $startExcelRow + 6, $column, $sample['hour_sample'] ?? '-');
            $this->setCell($rows, $startExcelRow + 7, $column, $category);
            $this->setCell($rows, $startExcelRow + 8, $column, $subCategory);

            $coordinate = $sample['coordinate'] ?? '-';
            $parsedCoordinate = $this->parseCoordinate($coordinate);

            $this->setCell($rows, $startExcelRow + 9, $column, 'E: ' . $parsedCoordinate['east']);
            $this->setCell($rows, $startExcelRow + 10, $column, 'N: ' . $parsedCoordinate['north']);
        }

        $headerRow = $startExcelRow + 12;

        $this->setCell($rows, $headerRow, 0, 'Parámetros');
        $this->setCell($rows, $headerRow, 3, 'Unidad');
        $this->setCell($rows, $headerRow, 4, 'L.C.M.');
        $this->setCell($rows, $headerRow, 5, 'Resultados');

        $currentRow = $headerRow + 1;

        foreach (($this->mapData['analysis_groups'] ?? []) as $group) {
            $this->setCell($rows, $currentRow, 0, $group['type_of_analysis'] ?? 'SIN TIPO DE ENSAYO');
            $currentRow++;

            foreach (($group['parameters'] ?? []) as $parameter) {
                $this->setCell($rows, $currentRow, 0, $parameter['parameter'] ?? '-');
                $this->setCell($rows, $currentRow, 3, $parameter['unit'] ?? '-');
                $this->setCell($rows, $currentRow, 4, $parameter['lcm'] ?? '-');

                foreach ($sampleChunk as $localIndex => $sample) {
                    $globalIndex = ($chunkIndex * $this->maxResultsPerBlock) + $localIndex;
                    $column = $this->firstResultColumn + $localIndex;

                    $results = array_values($parameter['results'] ?? []);

                    $this->setCell(
                        $rows,
                        $currentRow,
                        $column,
                        $results[$globalIndex]['result'] ?? ''
                    );
                }

                $currentRow++;
            }
        }

        if ($isLastChunk && $this->hasLegend()) {
            $currentRow++;
            $this->setCell($rows, $currentRow, 0, trim((string) ($this->mapData['legend'] ?? '')));
            $currentRow++;
        }

        return $currentRow;
    }

    public function title(): string
    {
        return 'Informe de Ensayo';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 24,
            'B' => 12,
            'C' => 4,
            'D' => 13,
            'E' => 10,
            'F' => 14,
            'G' => 14,
            'H' => 14,
            'I' => 14,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [];
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

                for ($row = 1; $row <= $totalRows; $row++) {
                    $sheet->getRowDimension($row)->setRowHeight(18);
                }

                /*
                |--------------------------------------------------------------------------
                | PÁGINA 1
                |--------------------------------------------------------------------------
                */

                $sheet->mergeCells('C1:I1');
                $sheet->mergeCells('C2:I2');

                $sheet->getStyle('C1:I2')
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->getStyle('C1:I2')
                    ->getFont()
                    ->setBold(true)
                    ->setSize(16);

                $sheet->getStyle('A4:A17')->getFont()->setBold(true);
                $sheet->getStyle('A21')->getFont()->setBold(true);

                foreach (range(4, 17) as $row) {
                    $sheet->getRowDimension($row)->setRowHeight(28);
                }

                $sheet->mergeCells('A19:I19');
                $sheet->mergeCells('A20:I20');

                $this->bottomBorder($sheet, 'A18:I18');

                /*
                |--------------------------------------------------------------------------
                | BLOQUES DINÁMICOS DE RESULTADOS
                |--------------------------------------------------------------------------
                */

                $currentStartRow = $this->firstResultsExcelRow;
                $sampleChunks = $this->getSampleChunks();

                foreach ($sampleChunks as $chunkIndex => $sampleChunk) {
                    $isLastChunk = $chunkIndex === count($sampleChunks) - 1;

                    $nextRow = $this->styleResultsBlock(
                        $sheet,
                        $currentStartRow,
                        $isLastChunk
                    );

                    $sheet->setBreak("A" . ($nextRow + 1), Worksheet::BREAK_ROW);

                    $currentStartRow = $nextRow + 3;
                }

                /*
                |--------------------------------------------------------------------------
                | MÉTODOS Y REFERENCIAS
                |--------------------------------------------------------------------------
                */

                $methodsStartRow = $this->getMethodsStartExcelRow();
                $methodsHeaderRow = $methodsStartRow + 2;
                $methodsFirstDataRow = $methodsHeaderRow + 1;
                $methodsLastDataRow = $methodsFirstDataRow + $this->getMethodologyRowsCount() - 1;

                $sheet->mergeCells("A{$methodsStartRow}:{$lastColumn}{$methodsStartRow}");
                $sheet->getStyle("A{$methodsStartRow}:{$lastColumn}{$methodsStartRow}")
                    ->getFont()
                    ->setBold(true);

                $this->topBorder($sheet, "A{$methodsStartRow}:{$lastColumn}{$methodsStartRow}");
                $this->bottomBorder($sheet, "A{$methodsStartRow}:{$lastColumn}{$methodsStartRow}");

                $sheet->mergeCells("A{$methodsHeaderRow}:C{$methodsHeaderRow}");
                $sheet->mergeCells("D{$methodsHeaderRow}:E{$methodsHeaderRow}");
                $sheet->mergeCells("F{$methodsHeaderRow}:I{$methodsHeaderRow}");

                $sheet->getStyle("A{$methodsHeaderRow}:{$lastColumn}{$methodsHeaderRow}")
                    ->getFont()
                    ->setBold(true);

                $sheet->getStyle("A{$methodsHeaderRow}:{$lastColumn}{$methodsHeaderRow}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $this->topBorder($sheet, "A{$methodsHeaderRow}:{$lastColumn}{$methodsHeaderRow}");
                $this->bottomBorder($sheet, "A{$methodsHeaderRow}:{$lastColumn}{$methodsHeaderRow}");

                for ($row = $methodsFirstDataRow; $row <= $methodsLastDataRow; $row++) {
                    $sheet->mergeCells("A{$row}:C{$row}");
                    $sheet->mergeCells("D{$row}:E{$row}");
                    $sheet->mergeCells("F{$row}:I{$row}");
                    $sheet->getRowDimension($row)->setRowHeight(36);
                }

                $this->bottomBorder($sheet, "A{$methodsLastDataRow}:{$lastColumn}{$methodsLastDataRow}");

                /*
                |--------------------------------------------------------------------------
                | PROCEDIMIENTOS Y OBSERVACIONES
                |--------------------------------------------------------------------------
                */

                $samplingPerformedBy = $this->mapData['sampling_performed_by'] ?? '';
                $showProcedures = $samplingPerformedBy === 'GREENLAB PERÚ S.A.C.';

                if ($showProcedures) {
                    $procedureStartRow = $methodsLastDataRow + 3;
                    $procedureFirstDataRow = $procedureStartRow + 2;
                    $procedureLastDataRow = $procedureFirstDataRow + count($this->getProcedures()) - 1;

                    $sheet->mergeCells("A{$procedureStartRow}:{$lastColumn}{$procedureStartRow}");
                    $sheet->getStyle("A{$procedureStartRow}:{$lastColumn}{$procedureStartRow}")
                        ->getFont()
                        ->setBold(true);

                    $this->topBorder($sheet, "A{$procedureStartRow}:{$lastColumn}{$procedureStartRow}");
                    $this->bottomBorder($sheet, "A{$procedureStartRow}:{$lastColumn}{$procedureStartRow}");

                    for ($row = $procedureFirstDataRow; $row <= $procedureLastDataRow; $row++) {
                        $sheet->mergeCells("A{$row}:{$lastColumn}{$row}");
                        $sheet->getRowDimension($row)->setRowHeight(22);
                    }

                    $this->bottomBorder($sheet, "A{$procedureLastDataRow}:{$lastColumn}{$procedureLastDataRow}");

                    $observationStartRow = $procedureLastDataRow + 3;
                } else {
                    $observationStartRow = $methodsLastDataRow + 3;
                }

                $observationTextRow1 = $observationStartRow + 2;
                $observationTextRow2 = $observationStartRow + 3;
                $observationTextRow3 = $observationStartRow + 4;
                $finalReportRow = $observationStartRow + 7;

                $sheet->mergeCells("A{$observationStartRow}:{$lastColumn}{$observationStartRow}");
                $sheet->getStyle("A{$observationStartRow}:{$lastColumn}{$observationStartRow}")
                    ->getFont()
                    ->setBold(true);

                $this->topBorder($sheet, "A{$observationStartRow}:{$lastColumn}{$observationStartRow}");
                $this->bottomBorder($sheet, "A{$observationStartRow}:{$lastColumn}{$observationStartRow}");

                $sheet->mergeCells("D{$observationTextRow1}:I{$observationTextRow1}");
                $sheet->mergeCells("D{$observationTextRow2}:I{$observationTextRow2}");
                $sheet->mergeCells("D{$observationTextRow3}:I{$observationTextRow3}");

                $sheet->getRowDimension($observationTextRow1)->setRowHeight(30);
                $sheet->getRowDimension($observationTextRow2)->setRowHeight(38);
                $sheet->getRowDimension($observationTextRow3)->setRowHeight(30);

                $sheet->mergeCells("A{$finalReportRow}:{$lastColumn}{$finalReportRow}");
                $sheet->getStyle("A{$finalReportRow}:{$lastColumn}{$finalReportRow}")
                    ->getFont()
                    ->setBold(true);

                $sheet->getStyle("A{$finalReportRow}:{$lastColumn}{$finalReportRow}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $this->topBorder($sheet, "A{$finalReportRow}:{$lastColumn}{$finalReportRow}");

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

    private function styleResultsBlock(Worksheet $sheet, int $startExcelRow, bool $isLastChunk): int
    {
        $lastColumn = $this->columnLetter($this->getTotalColumns() - 1);

        $titleRow = $startExcelRow;
        $subtitleRow = $startExcelRow + 1;
        $sectionRow = $startExcelRow + 2;

        $infoStartRow = $startExcelRow + 3;
        $infoEndRow = $startExcelRow + 10;

        $headerRow = $startExcelRow + 12;
        $dataStartRow = $headerRow + 1;

        $analysisRowsCount = $this->getAnalysisRowsCount();
        $dataEndRow = $dataStartRow + $analysisRowsCount - 1;

        $sheet->mergeCells("C{$titleRow}:{$lastColumn}{$titleRow}");
        $sheet->mergeCells("C{$subtitleRow}:{$lastColumn}{$subtitleRow}");
        $sheet->mergeCells("A{$sectionRow}:{$lastColumn}{$sectionRow}");

        $sheet->getStyle("C{$titleRow}:{$lastColumn}{$subtitleRow}")
            ->getFont()
            ->setBold(true)
            ->setSize(16);

        $sheet->getStyle("C{$titleRow}:{$lastColumn}{$subtitleRow}")
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle("A{$sectionRow}:{$lastColumn}{$sectionRow}")
            ->getFont()
            ->setBold(true);

        $this->topBorder($sheet, "A{$sectionRow}:{$lastColumn}{$sectionRow}");
        $this->bottomBorder($sheet, "A{$sectionRow}:{$lastColumn}{$sectionRow}");

        for ($row = $infoStartRow; $row <= $infoEndRow; $row++) {
            $sheet->mergeCells("A{$row}:B{$row}");
            $sheet->getStyle("A{$row}:A{$row}")->getFont()->setBold(true);
            $sheet->getStyle("F{$row}:I{$row}")
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        $this->bottomBorder($sheet, "A{$infoEndRow}:{$lastColumn}{$infoEndRow}");

        $sheet->mergeCells("A{$headerRow}:C{$headerRow}");
        $sheet->mergeCells("F{$headerRow}:I{$headerRow}");

        $sheet->getStyle("A{$headerRow}:{$lastColumn}{$headerRow}")
            ->getFont()
            ->setBold(true);

        $sheet->getStyle("A{$headerRow}:{$lastColumn}{$headerRow}")
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $this->topBorder($sheet, "A{$headerRow}:{$lastColumn}{$headerRow}");
        $this->bottomBorder($sheet, "A{$headerRow}:{$lastColumn}{$headerRow}");

        $currentRow = $dataStartRow;

        foreach (($this->mapData['analysis_groups'] ?? []) as $group) {
            $sheet->mergeCells("A{$currentRow}:{$lastColumn}{$currentRow}");

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
                $sheet->mergeCells("A{$currentRow}:C{$currentRow}");

                $sheet->getStyle("D{$currentRow}:{$lastColumn}{$currentRow}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $this->bottomBorder($sheet, "A{$currentRow}:{$lastColumn}{$currentRow}");

                $currentRow++;
            }
        }

        $nextRow = $dataEndRow + 1;

        if ($isLastChunk && $this->hasLegend()) {
            $legendRow = $nextRow + 1;

            $sheet->mergeCells("A{$legendRow}:{$lastColumn}{$legendRow}");
            $sheet->getStyle("A{$legendRow}:{$lastColumn}{$legendRow}")
                ->getFont()
                ->setSize(8);

            $sheet->getStyle("A{$legendRow}:{$lastColumn}{$legendRow}")
                ->getAlignment()
                ->setWrapText(true);

            $sheet->getRowDimension($legendRow)->setRowHeight(70);

            $nextRow = $legendRow + 1;
        }

        $sheet->getRowDimension($titleRow)->setRowHeight(22);
        $sheet->getRowDimension($subtitleRow)->setRowHeight(22);
        $sheet->getRowDimension($sectionRow)->setRowHeight(24);
        $sheet->getRowDimension($headerRow)->setRowHeight(24);

        return $nextRow;
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

        return max($count, 1);
    }

    private function getMethodsStartExcelRow(): int
    {
        $currentRow = $this->firstResultsExcelRow;
        $sampleChunks = $this->getSampleChunks();

        foreach ($sampleChunks as $chunkIndex => $sampleChunk) {
            $isLastChunk = $chunkIndex === count($sampleChunks) - 1;

            $currentRow = $this->getResultBlockNextExcelRow($currentRow, $isLastChunk);
            $currentRow += 3;
        }

        return $currentRow + 1;
    }

    private function getResultBlockNextExcelRow(int $startExcelRow, bool $isLastChunk): int
    {
        $nextRow = $startExcelRow + 13 + $this->getAnalysisRowsCount();

        if ($isLastChunk && $this->hasLegend()) {
            $nextRow += 2;
        }

        return $nextRow;
    }

    private function getTotalRows(): int
    {
        $methodsStartRow = $this->getMethodsStartExcelRow();

        $methodsHeaderRow = $methodsStartRow + 2;
        $methodsFirstDataRow = $methodsHeaderRow + 1;
        $methodsLastDataRow = $methodsFirstDataRow + $this->getMethodologyRowsCount() - 1;

        $samplingPerformedBy = $this->mapData['sampling_performed_by'] ?? '';
        $showProcedures = $samplingPerformedBy === 'GREENLAB PERÚ S.A.C.';

        if ($showProcedures) {
            $procedureStartRow = $methodsLastDataRow + 3;
            $procedureFirstDataRow = $procedureStartRow + 2;
            $procedureLastDataRow = $procedureFirstDataRow + count($this->getProcedures()) - 1;

            $observationStartRow = $procedureLastDataRow + 3;
        } else {
            $observationStartRow = $methodsLastDataRow + 3;
        }

        return $observationStartRow + 9;
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

    private function getProcedures(): array
    {
        $procedures = collect($this->mapData['procedures'] ?? [])
            ->map(fn($row) => $row['procedure'] ?? null)
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        return !empty($procedures) ? $procedures : ['-'];
    }

    private function getSampleChunks(): array
    {
        $samples = $this->mapData['samples'] ?? [];

        $chunks = array_chunk($samples, $this->maxResultsPerBlock);

        return !empty($chunks) ? $chunks : [[]];
    }

    private function hasLegend(): bool
    {
        return trim((string) ($this->mapData['legend'] ?? '')) !== '';
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
        } elseif (preg_match('/N\s*:\s*([^\r\n,;]+).*?E\s*:\s*([^\r\n,;]+)/is', $coordinate, $matches)) {
            $north = trim($matches[1] ?? '-');
            $east = trim($matches[2] ?? '-');
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

    private function setCell(array &$rows, int $excelRow, int $columnIndex, mixed $value): void
    {
        $rows[$excelRow - 1][$columnIndex] = $value;
    }

    private function getTotalColumns(): int
    {
        return $this->totalColumns;
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
