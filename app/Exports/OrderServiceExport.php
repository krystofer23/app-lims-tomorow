<?php

namespace App\Exports;

use App\Exports\Sheets\OrderServiceMainSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class OrderServiceExport implements WithMultipleSheets
{
    use Exportable;

    public function __construct(public $orderService) {}

    public function sheets(): array
    {
        return [
            new OrderServiceMainSheet($this->orderService),
        ];
    }
}
