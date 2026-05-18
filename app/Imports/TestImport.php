<?php

namespace App\Imports;

use App\Models\tenant\Category;
use App\Models\tenant\Conditions;
use App\Models\tenant\Item;
use App\Models\tenant\Matrix;
use App\Models\tenant\Parameters;
use App\Models\tenant\ReferencesStandard;
use App\Models\tenant\SubCategory;
use App\Models\tenant\UnitsMeasurement;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;

class TestImport implements ToCollection
{
    public array $errors = [];
    public int $imports = 0;

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            try {
                DB::beginTransaction();

                $conditionDescription = trim((string) ($row[0] ?? ''));
                $matrixDescription = trim((string) ($row[1] ?? ''));
                $parameterDescription = trim((string) ($row[2] ?? ''));

                $normReferenceCode = trim((string) ($row[3] ?? ''));
                $normReferenceTitle = trim((string) ($row[4] ?? ''));

                $unitDescriptionFirst = trim((string) ($row[5] ?? ''));
                $lcmFirst = trim((string) ($row[9] ?? ''));

                $condition = $conditionDescription !== ''
                    ? Conditions::firstOrCreate([
                        'description' => $conditionDescription,
                    ])
                    : null;

                $matrix = $matrixDescription !== ''
                    ? Matrix::firstOrCreate([
                        'description' => $matrixDescription,
                    ])
                    : null;

                $parameter = $parameterDescription !== ''
                    ? Parameters::firstOrCreate([
                        'description' => $parameterDescription,
                    ])
                    : null;

                $referenceStandard = ReferencesStandard::firstOrCreate([
                    'code' => $normReferenceCode,
                    'title' => $normReferenceTitle,
                ]);

                $findUnit = UnitsMeasurement::firstOrCreate([
                    'description' => $unitDescriptionFirst
                ]);

                $unitsMensurets = [
                    trim((string) ($row[5] ?? '')),
                    trim((string) ($row[6] ?? '')),
                    trim((string) ($row[7] ?? '')),
                    trim((string) ($row[8] ?? '')),
                ];

                $lcms = [
                    trim((string) ($row[9] ?? '')),
                    trim((string) ($row[10] ?? '')),
                    trim((string) ($row[11] ?? '')),
                    trim((string) ($row[12] ?? '')),
                ];

                Item::create([
                    'type' => 'EMISIONES',
                    'condition_id' => $condition?->id,
                    'matrix_id' => $matrix?->id,
                    'parameter_id' => $parameter?->id,
                    'reference_id' => $referenceStandard?->id,
                    'unit_measurement_id' => $findUnit?->id,
                    'lcm' => $lcmFirst,
                    'unit_price' => 0.00,
                    'content' => [
                        'units_measurements' => $unitsMensurets,
                        'lcms' => $lcms,
                    ]
                ]);

                DB::commit();

                $this->imports++;
            } catch (Exception $e) {
                DB::rollBack();

                $this->errors[] = [
                    'row' => $index + 1,
                    'error' => $e->getMessage(),
                ];

                Log::error('Error importando fila', [
                    'row' => $index + 1,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }
}
