<?php

namespace App\Imports;

use App\Models\tenant\Conditions;
use App\Models\tenant\Essays;
use App\Models\tenant\Methodologies;
use App\Models\tenant\UnitsMeasurement;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ImportMatriz implements ToCollection
{
    /**
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $key => $row) {
            if ($key === 0) {
                continue;
            }

            $unitsMeasurement = trim((string) ($row[4] ?? ''));
            $methodology = trim((string) ($row[2] ?? ''));
            $condition = trim((string) ($row[6] ?? ''));

            if ($unitsMeasurement !== '') {
                UnitsMeasurement::firstOrCreate([
                    'description' => $unitsMeasurement
                ]);
            }

            if ($methodology !== '') {
                Methodologies::firstOrCreate([
                    'description' => $methodology
                ]);
            }

            if ($condition !== '') {
                Conditions::firstOrCreate([
                    'description' => $condition
                ]);
            }
        }

        foreach ($rows as $key => $row) {
            if ($key === 0) {
                continue;
            }

            $essay = trim((string) ($row[1] ?? ''));
            $lcm = trim((string) ($row[3] ?? ''));
            $unitsMeasurement = trim((string) ($row[4] ?? ''));
            $condition = trim((string) ($row[6] ?? ''));

            if ($essay === '') {
                continue;
            }

            $findUnitsMeasurement = UnitsMeasurement::where('description', $unitsMeasurement)->first();
            $findCondition = Conditions::where('description', $condition)->first();

            Essays::updateOrCreate(
                [
                    'description' => $essay
                ],
                [
                    'lcm' => $lcm,
                    'units_measurement_id' => $findUnitsMeasurement?->id,
                    'condition_id' => $findCondition?->id,
                ]
            );
        }
    }
}
