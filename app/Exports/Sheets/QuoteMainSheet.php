<?php

namespace App\Exports\Sheets;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class QuoteMainSheet implements FromView, WithTitle, WithEvents
{
    public function __construct(public $quote) {}

    public function title(): string
    {
        return 'cotización';
    }

    public function view(): View
    {
        $quote = $this->quote;
        $company = $quote->company;

        $matrices = $quote->itemsQuotes->where('type', 'matriz');
        $services = $quote->itemsQuotes->where('type', 'service');
        $other_expense = $quote->itemsQuotes->where('type', 'other_expense');

        $ruc = strval($company->ruc);

        $groupedMatrices = $matrices
            ->groupBy(function ($matriz) {
                $description = data_get($matriz, 'item.description', 'Sin matriz');
                $frequencyLabel = data_get($matriz, 'item.frequency_label');

                return $description . '||' . ($frequencyLabel ?: 'SIN_FRECUENCIA');
            })
            ->map(function ($items) {
                $first = $items->first();

                return [
                    'description' => data_get($first, 'item.description', 'Sin matriz'),
                    'frequency_label' => data_get($first, 'item.frequency_label'),
                    'items' => $items,
                    'total' => $items->sum(fn($item) => (float) ($item->total ?? 0)),
                ];
            })
            ->values();

        $servicesTotal = $services->sum(function ($service) {
            return (float) data_get($service, 'item.price', $service->total ?? 0);
        });

        $otherExpenseTotal = $other_expense->sum(function ($otherexpense) {
            return (float) data_get($otherexpense, 'item.price', $otherexpense->total ?? 0);
        });

        $matricesTotal = $groupedMatrices->sum('total');
        $grandTotal = $matricesTotal + $servicesTotal;

        return view('exports.quotes.main', [
            'quote' => $quote,
            'company' => $company,
            'ruc' => $ruc,
            'groupedMatrices' => $groupedMatrices,
            'services' => $services,
            'servicesTotal' => $servicesTotal,
            'matricesTotal' => $matricesTotal,
            'grandTotal' => $grandTotal,
            'other_expense' => $other_expense,
            'otherExpenseTotal' => $otherExpenseTotal,
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $sheet->getColumnDimension('A')->setWidth(22);
                $sheet->getColumnDimension('B')->setWidth(35);
                $sheet->getColumnDimension('C')->setWidth(30);
                $sheet->getColumnDimension('D')->setWidth(30);
                $sheet->getColumnDimension('E')->setWidth(12);
                $sheet->getColumnDimension('F')->setWidth(14);
                $sheet->getColumnDimension('G')->setWidth(14);
                $sheet->getColumnDimension('H')->setWidth(20);
                $sheet->getColumnDimension('I')->setWidth(16);
                $sheet->getColumnDimension('J')->setWidth(16);

                $highestRow = $sheet->getHighestRow();

                $sheet->getStyle("A1:J{$highestRow}")
                    ->getAlignment()
                    ->setVertical(Alignment::VERTICAL_CENTER);

                $sheet->getStyle("A1:J{$highestRow}")
                    ->getAlignment()
                    ->setWrapText(true);

                $sheet->getStyle("A1:J{$highestRow}")
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);

                $sheet->getStyle("A1:J{$highestRow}")
                    ->getBorders()
                    ->getOutline()
                    ->setBorderStyle(Border::BORDER_THIN);
            },
        ];
    }
}
