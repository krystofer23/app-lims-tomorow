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

                // $categoryDescription = trim((string) ($row[5] ?? ''));
                // $subCategoryDescription = trim((string) ($row[6] ?? ''));

                $unitDescription = trim((string) ($row[5] ?? ''));
                $lcm = trim((string) ($row[6] ?? ''));

                Log::info([
                    'row' => $index + 1,
                    'conditionDescription' => $conditionDescription,
                    'matrixDescription' => $matrixDescription,
                    'parameterDescription' => $parameterDescription,
                    'normReferenceCode' => $normReferenceCode,
                    'normReferenceTitle' => $normReferenceTitle,
                    // 'categoryDescription' => $categoryDescription,
                    // 'subCategoryDescription' => $subCategoryDescription,
                    'unitDescription' => $unitDescription,
                    'lcm' => $lcm,
                ]);

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

                // $category = $categoryDescription !== ''
                //     ? Category::firstOrCreate([
                //         'description' => $categoryDescription,
                //     ])
                //     : null;

                // $subcategory = $subCategoryDescription !== ''
                //     ? SubCategory::firstOrCreate([
                //         'description' => $subCategoryDescription,
                //     ])
                //     : null;

                $unit = $unitDescription !== ''
                    ? UnitsMeasurement::firstOrCreate([
                        'description' => $unitDescription,
                    ])
                    : null;

                Item::create([
                    'type' => 'METEOROLOGIA',
                    'condition_id' => $condition?->id,
                    'matrix_id' => $matrix?->id,
                    'parameter_id' => $parameter?->id,
                    'reference_id' => $referenceStandard?->id,
                    // 'category_id' => $category?->id,
                    // 'sub_category_id' => $subcategory?->id,
                    'unit_measurement_id' => $unit?->id,
                    'lcm' => $lcm,
                    'unit_price' => 0.00,
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
