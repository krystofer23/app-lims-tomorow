<?php

namespace App\Exports\Sheets;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class QuoteLogisticsSheet implements FromView, WithTitle, WithEvents
{
    public function __construct(public array $quote)
    {
    }

    public function title(): string
    {
        return 'Gastos logísticos';
    }

    public function view(): View
    {
        $totalLogistics = collect($this->quote['logistics'])->sum(function ($item) {
            return $item['days'] * $item['quantity'] * $item['unit_cost'];
        });

        return view('exports.quotes.logistics', [
            'quote' => $this->quote,
            'logistics' => $this->quote['logistics'],
            'totalLogistics' => $totalLogistics,
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $sheet->getColumnDimension('A')->setWidth(28);
                $sheet->getColumnDimension('B')->setWidth(25);
                $sheet->getColumnDimension('C')->setWidth(70);
                $sheet->getColumnDimension('D')->setWidth(12);
                $sheet->getColumnDimension('E')->setWidth(12);
                $sheet->getColumnDimension('F')->setWidth(14);
                $sheet->getColumnDimension('G')->setWidth(16);

                $sheet->getStyle('A1:G200')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                $sheet->getStyle('C1:C200')->getAlignment()->setWrapText(true);
                $sheet->getStyle('F1:G200')->getNumberFormat()->setFormatCode('#,##0.00');
            },
        ];
    }
}