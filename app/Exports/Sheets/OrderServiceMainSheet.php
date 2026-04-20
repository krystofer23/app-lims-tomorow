<?php

namespace App\Exports\Sheets;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class OrderServiceMainSheet implements FromView, WithTitle, WithEvents
{
    public function __construct(public $orderService) {}

    public function title(): string
    {
        return 'orden servicio';
    }

    public function view(): View
    {
        $orderService = $this->orderService;
        $company = $orderService->company;
        $contact = $orderService->contact;

        $matrices = $orderService->items->where('type', 'matriz');
        $services = $orderService->items->where('type', 'service');

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
                    'items' => $items->values(),
                ];
            })
            ->values();

        return view('exports.order-services.main', [
            'orderService' => $orderService,
            'company' => $company,
            'contact' => $contact,
            'groupedMatrices' => $groupedMatrices,
            'services' => $services->values(),
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $sheet->getColumnDimension('A')->setWidth(22);
                $sheet->getColumnDimension('B')->setWidth(35);
                $sheet->getColumnDimension('C')->setWidth(25);
                $sheet->getColumnDimension('D')->setWidth(25);
                $sheet->getColumnDimension('E')->setWidth(12);
                $sheet->getColumnDimension('F')->setWidth(14);
                $sheet->getColumnDimension('G')->setWidth(14);
                $sheet->getColumnDimension('H')->setWidth(20);
                $sheet->getColumnDimension('I')->setWidth(18);
                $sheet->getColumnDimension('J')->setWidth(22);

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
