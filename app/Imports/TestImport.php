<?php

namespace App\Imports;

use App\Http\Controllers\tenant\ConsultingRucApiController;
use App\Models\tenant\Category;
use App\Models\tenant\Company;
use App\Models\tenant\Conditions;
use App\Models\tenant\ConnectionParameter;
use App\Models\tenant\ContactCompanies;
use App\Models\tenant\Item;
use App\Models\tenant\Matrix;
use App\Models\tenant\Parameters;
use App\Models\tenant\ReferencesStandard;
use App\Models\tenant\SubCategory;
use App\Models\tenant\TypeOfAnalysis;
use App\Models\tenant\TypeOfSamples;
use App\Models\tenant\UnitsMeasurement;
use App\Models\Tenant\User;
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
            if ($index === 0) {
                continue;
            }

            // try {
            //     DB::beginTransaction();

            //     $typeMatrixDescription = trim((string) ($row[0] ?? ''));
            //     $typeAnalysis = trim((string) ($row[1] ?? ''));

            //     $parameterDescription = trim((string) ($row[2] ?? ''));
            //     $codeDescription = trim((string) ($row[3] ?? ''));
            //     $titleDescription = trim((string) ($row[4] ?? ''));
            //     $conditionDescription = trim((string) ($row[5] ?? ''));

            //     $unitDescription1 = trim((string) ($row[6] ?? ''));
            //     $unitDescription2 = trim((string) ($row[7] ?? ''));
            //     $unitDescription3 = trim((string) ($row[8] ?? ''));
            //     $unitDescription4 = trim((string) ($row[9] ?? ''));
            //     $unitDescription5 = trim((string) ($row[10] ?? ''));

            //     $lcmDescription1 = trim((string) ($row[11] ?? ''));
            //     $lcmDescription2 = trim((string) ($row[12] ?? ''));
            //     $lcmDescription3 = trim((string) ($row[13] ?? ''));
            //     $lcmDescription4 = trim((string) ($row[14] ?? ''));
            //     $lcmDescription5 = trim((string) ($row[15] ?? ''));

            //     if (
            //         blank($typeMatrixDescription) ||
            //         blank($typeAnalysis) ||
            //         blank($parameterDescription) ||
            //         blank($codeDescription) ||
            //         blank($titleDescription) ||
            //         blank($conditionDescription)
            //     ) {
            //         throw new Exception("Faltan datos obligatorios en la fila");
            //     }

            //     $typeOfAnalysis = TypeOfAnalysis::firstOrCreate([
            //         'description' => $typeAnalysis,
            //     ]);

            //     $typeOfSample = TypeOfSamples::query()->where('description', $typeMatrixDescription)->first();
            //     $matrix = Matrix::query()->where('description', $typeMatrixDescription)->first();

            //     $condition = Conditions::query()->where('description', $conditionDescription)->first();

            //     if (!$condition) {
            //         throw new Exception("No existe la condición: {$conditionDescription}");
            //     }

            //     $parameter = Parameters::firstOrCreate([
            //         'description' => $parameterDescription,
            //         'type_of_analysis_id' => $typeOfAnalysis->id,
            //     ]);

            //     $reference = ReferencesStandard::firstOrCreate([
            //         'code' => $codeDescription,
            //         'title' => $titleDescription,
            //     ]);

            //     Item::firstOrCreate([
            //         'type' => 'EMISIONES',
            //         'condition_id' => $condition->id,
            //         'matrix_id' => $matrix?->id,
            //         'reference_id' => $reference->id,
            //         'parameter_id' => $parameter->id,
            //         'type_of_sample_id' => $typeOfSample?->id,
            //         'unit_price' => 0.00,
            //         'is_operation' => true,
            //         'operations' => [
            //             'units_measurements' => [
            //                 $unitDescription1,
            //                 $unitDescription2,
            //                 $unitDescription3,
            //                 $unitDescription4,
            //                 $unitDescription5,
            //             ],
            //             'lcms' => [
            //                 $lcmDescription1,
            //                 $lcmDescription2,
            //                 $lcmDescription3,
            //                 $lcmDescription4,
            //                 $lcmDescription5,
            //             ],
            //         ]
            //     ]);

            //     DB::commit();

            //     $this->imports++;
            // } catch (Exception $e) {
            //     DB::rollBack();

            //     $this->errors[] = [
            //         'row' => $index + 1,
            //         'error' => $e->getMessage(),
            //     ];

            //     Log::error('Error importando fila', [
            //         'row' => $index + 1,
            //         'error' => $e->getMessage(),
            //     ]);
            // }

            try {
                DB::beginTransaction();

                $parameter = trim((string) ($row[0] ?? ''));
                $code = trim((string) ($row[1] ?? ''));
                $title = trim((string) ($row[2] ?? ''));
                $accreditation = trim((string) ($row[3] ?? ''));
                $price = trim((string) ($row[4] ?? ''));

                $parameter = preg_replace('/\s+/', ' ', $parameter);
                $code = preg_replace('/\s+/', ' ', $code);
                $title = preg_replace('/\s+/', ' ', $title);
                $accreditation = preg_replace('/\s+/', ' ', $accreditation);

                $parameter = trim(ltrim($parameter, '-'));

                $price = str_replace(['S/', 's/', 'S/.', 's/.', ' '], '', $price);
                $price = str_replace(',', '.', $price);

                if (!$parameter || !$code || !$title || !$accreditation || $price === '') {
                    throw new Exception('Faltan datos obligatorios en la fila.');
                }

                if (!is_numeric($price)) {
                    throw new Exception("El precio no es válido: {$price}");
                }

                $p = Parameters::query()
                    ->where('description', 'like', "%$parameter%")
                    ->first();

                if (!$p) {
                    throw new Exception("No se encontró el parámetro: {$parameter}");
                }

                $rs = ReferencesStandard::query()
                    ->where('code', 'like', "%$code%")
                    ->where('title', 'like', "%$title%")
                    ->first();

                if (!$rs) {
                    throw new Exception("No se encontró la norma de referencia: {$code} - {$title}");
                }

                $c = Conditions::query()
                    ->where('description', 'like', "%$accreditation%")
                    ->first();

                if (!$c) {
                    throw new Exception("No se encontró la acreditación/condición: {$accreditation}");
                }

                $item = Item::query()
                    ->where('type', 'SUELO')
                    ->where('condition_id', $c->id)
                    ->where('reference_id', $rs->id)
                    ->where('parameter_id', $p->id)
                    ->first();

                if (!$item) {
                    throw new Exception("No se encontró el item para: {$parameter} | {$code} | {$title} | {$accreditation}");
                }

                $item->update([
                    'unit_price' => (float) $price,
                ]);

                DB::commit();

                $this->imports++;
            } catch (Exception $e) {
                DB::rollBack();

                $this->errors[] = [
                    'row' => $index + 1,
                    'error' => $e->getMessage(),
                ];
            }
        }
    }
}
