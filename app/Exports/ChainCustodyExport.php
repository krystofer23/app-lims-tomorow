<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;

class ChainCustodyExport implements FromArray, WithStyles, WithEvents, WithColumnWidths
{
    protected array $rows;

    public function __construct(array $rows)
    {
        $this->rows = $rows;
    }

    public function array(): array
    {
        return [
            ['GreenLab Perú S.A.C.', '', '', '', 'Identificación: F-PR-01-2'],
            ['', '', '', '', 'Revisión: 01'],
            ['', '', '', '', 'Inicio de Vigencia: 2025-09-12'],
            [],
            ['', '', 'Orden de Trabajo'],
            [],
            ['Orden de servicio', ':', $this->rows['os'] ?? ''],
            ['Informe de Ensayo', ':', $this->rows['number_report'] ?? ''],
            ['Cadena de Custodia', ':', $this->rows['number_chain'] ?? ''],
            ['Matriz', ':', $this->rows['matriz'] ?? ''],
            ['Fecha de entrega', ':', $this->rows['date_agreed'] ?? ''],
            ['Hora', ':', $this->rows['hour'] ?? ''],
            [],
            ['', '* Este documento debe ser entregado junto con los siguientes análisis requeridos *'],
            [],
            ['Código de Laboratorio (muestras)', 'Análisis Requeridos'],
            ...($this->rows['details'] ?? []),
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 30,
            'B' => 8,
            'C' => 45,
            'D' => 25,
            'E' => 35,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 18, 'color' => ['rgb' => '16A34A']]],
            5 => ['font' => ['bold' => true, 'size' => 18]],
            16 => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
                'fill' => [
                    'fillType' => 'solid',
                    'startColor' => ['rgb' => '92D050'],
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $sheet->mergeCells('A1:C3');
                $sheet->mergeCells('C5:D5');
                $sheet->mergeCells('B14:E14');
                $sheet->mergeCells('B16:E16');

                $lastRow = 16 + count($this->rows['details'] ?? []);

                $sheet->getStyle("A16:E{$lastRow}")
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle('thin');

                $sheet->getStyle("A16:E{$lastRow}")
                    ->getAlignment()
                    ->setHorizontal('center')
                    ->setVertical('center')
                    ->setWrapText(true);

                $sheet->getStyle('A1:E3')->getFill()
                    ->setFillType('solid')
                    ->getStartColor()
                    ->setRGB('92D050');

                $sheet->getStyle('C5:D5')
                    ->getAlignment()
                    ->setHorizontal('center');

                $sheet->getRowDimension(16)->setRowHeight(35);

                for ($i = 17; $i <= $lastRow; $i++) {
                    $sheet->getRowDimension($i)->setRowHeight(28);
                }
            },
        ];
    }
}
