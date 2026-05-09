<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;

class TestImport implements ToCollection
{
    /**
     * @param Collection $collection
     */

    public array $errors = [];
    public int $imports = 0;

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $indexExcel = $index + 2;
            Log::info($row);
        }
    }
}
