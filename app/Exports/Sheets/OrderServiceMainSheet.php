<?php

namespace App\Exports\Sheets;

use App\Models\tenant\ConnectionParameter as TenantConnectionParameter;
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
        $contact = $orderService->contactCompany;

        $matrices = $orderService->items->where('type', 'matrix');
        $services = $orderService->items->where('type', 'service');

        $groupedMatrices = $matrices
            ->groupBy(function ($matrix) {
                $matrixDescription = data_get($matrix, 'item.matrix.description');

                $matrixKey = $matrixDescription
                    ?: 'matrix_filter_' . data_get($matrix, 'item.matrix_filter', 'sin_matriz');

                $frequencyLabel = data_get($matrix, 'item.frequency_label')
                    ?? data_get($matrix, 'item.item.frequency_label')
                    ?? 'SIN_FRECUENCIA';

                return $matrixKey . '||' . $frequencyLabel;
            })
            ->map(function ($items) {
                $first = $items->first();

                $matrixDescription = data_get($first, 'item.matrix.description');

                if (!$matrixDescription) {
                    $parameterId = data_get($first, 'item.parameter_id');
                    $typeOfSampleId = data_get($first, 'item.type_of_sample_filter');
                    $matrixId = data_get($first, 'item.matrix_filter');

                    $connectionParameter = TenantConnectionParameter::query()
                        ->with('matrix')
                        ->where('parameter_id', $parameterId)
                        ->when($typeOfSampleId, fn($q) => $q->where('type_of_samples_id', $typeOfSampleId))
                        ->when($matrixId, fn($q) => $q->where('matrix_id', $matrixId))
                        ->first();

                    $matrixDescription = $connectionParameter?->matrix?->description;
                }

                $preparedItems = $items
                    ->map(function ($item) use ($matrixDescription) {
                        $methodologyCode = data_get($item, 'item.reference.code', '');
                        $methodologyTitle = data_get($item, 'item.reference.title', '');

                        $methodology = trim($methodologyCode . ' - ' . $methodologyTitle, ' -');

                        $item->matrix_description_resolved = data_get($item, 'item.matrix.description')
                            ?? $matrixDescription
                            ?? 'Sin matriz';

                        $item->essay_description_resolved = data_get($item, 'item.parameter.description')
                            ?? data_get($item, 'item.description')
                            ?? '-';

                        $item->methodology_resolved = $methodology !== ''
                            ? $methodology
                            : data_get($item, 'item.methodology')
                            ?? data_get($item, 'item.methodologie.description')
                            ?? '-';

                        $item->lcm_resolved = data_get($item, 'item.lcm', '-');

                        $item->unit_resolved = data_get($item, 'item.unit_measurement.description')
                            ?? data_get($item, 'item.units_measurement.description')
                            ?? '-';

                        $item->samples_resolved = data_get($item, 'item.number_samples')
                            ?? $item->amount
                            ?? '-';

                        $item->subcontract_resolved = data_get($item, 'item.subcontract')
                            ?? data_get($item, 'item.subcontract_name')
                            ?? data_get($item, 'item.condition.description')
                            ?? 'NO APLICA';

                        return $item;
                    })
                    ->values();

                return [
                    'description' => $matrixDescription ?? 'Sin matriz',
                    'frequency_label' => data_get($first, 'item.frequency_label')
                        ?? data_get($first, 'item.item.frequency_label'),
                    'items' => $preparedItems,
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

                foreach (range('A', 'J') as $column) {
                    $sheet->getColumnDimension($column)->setWidth(match ($column) {
                        'A' => 12,
                        'B' => 26,
                        'C', 'D' => 28,
                        'E' => 14,
                        'F' => 16,
                        'G' => 14,
                        'H' => 22,
                        'I' => 18,
                        'J' => 28,
                        default => 18,
                    });
                }

                $highestRow = $sheet->getHighestRow();

                $sheet->getStyle("A1:J{$highestRow}")
                    ->getAlignment()
                    ->setVertical(Alignment::VERTICAL_CENTER)
                    ->setWrapText(true);

                $sheet->getStyle("A1:J{$highestRow}")
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);

                $sheet->getStyle("A1:J{$highestRow}")
                    ->getFont()
                    ->setName('Arial')
                    ->setSize(10);

                $sheet->getStyle('A1:J1')
                    ->getFont()
                    ->setBold(true)
                    ->setSize(14);

                $sheet->getStyle("A1:J{$highestRow}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_LEFT);

                $sheet->getStyle("A1:J{$highestRow}")
                    ->getAlignment()
                    ->setVertical(Alignment::VERTICAL_CENTER);

                for ($i = 1; $i <= $highestRow; $i++) {
                    $sheet->getRowDimension($i)->setRowHeight(24);
                }
            },
        ];
    }
}
