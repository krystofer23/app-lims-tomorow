<?php

namespace App\Exports;

use App\Exports\Sheets\QuoteMainSheet;
use App\Exports\Sheets\QuoteLogisticsSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithPreCalculateFormulas;

class QuoteExport implements WithMultipleSheets, WithPreCalculateFormulas
{
    use Exportable;

    public function __construct(public $quote) {}

    public function sheets(): array
    {
        return [
            new QuoteMainSheet($this->quote),
            // new QuoteLogisticsSheet($this->quote),
        ];
    }
}
