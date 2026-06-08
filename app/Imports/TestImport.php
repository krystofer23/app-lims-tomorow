<?php

namespace App\Imports;

use App\Models\tenant\Parameters;
use App\Models\tenant\Procedure;
use App\Models\tenant\ProceduresToParameter;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
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

            try {
                DB::beginTransaction();

                $parameterDescription = trim((string) ($row[7] ?? ''));

                if ($parameterDescription === '') {
                    throw new Exception('El parámetro está vacío.');
                }

                $param = Parameters::where('description', 'like', "%$parameterDescription%")->first();

                if (!$param) {
                    throw new Exception("No se encontró el parámetro: {$parameterDescription}");
                }

                $procedures = [
                    trim((string) ($row[2] ?? '')),
                    trim((string) ($row[3] ?? '')),
                    trim((string) ($row[4] ?? '')),
                    trim((string) ($row[5] ?? '')),
                ];

                foreach ($procedures as $procedureDescription) {
                    if ($procedureDescription === '') {
                        continue;
                    }

                    $procedure = Procedure::where('description', 'like', "%$procedureDescription%")->first();

                    if (!$procedure) {
                        $procedure = Procedure::updateOrCreate(['description' => $procedureDescription]);

                        // $this->errors[] = [
                        //     'row' => $index + 1,
                        //     'error' => "No se encontró el procedimiento: {$procedureDescription}",
                        // ];

                        // continue;
                    }

                    ProceduresToParameter::updateOrCreate([
                        'parameter_id' => $param->id,
                        'procedure_id' => $procedure->id,
                    ]);
                }

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
