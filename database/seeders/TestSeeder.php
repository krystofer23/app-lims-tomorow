<?php

namespace Database\Seeders;

use App\Models\tenant\Category;
use App\Models\tenant\Conditions;
use App\Models\tenant\ConnectionParameter;
use App\Models\tenant\Matrix;
use App\Models\tenant\Parameters;
use App\Models\tenant\TypeOfAnalysis;
use App\Models\tenant\TypeOfSamples;
use App\Models\tenant\UnitsMeasurement;
use Exception;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            $parameters = [
                "- Hidrocarburos Totales expresados como Hexano.",
                "- Benceno.",
                "- Hidrocarburos No Metano (HCNM)",
            ];

            $matrixs = [
                "Tubo de Carbón Activado"
            ];

            $type = TypeOfSamples::firstOrCreate([
                'description' => 'Tubo de Carbón Activado'
            ]);

            foreach ($matrixs as $matrix) {
                $ma = Matrix::firstOrCreate([
                    'description' => $matrix,
                    'type_of_sample_id' => $type?->id
                ]);

                foreach ($parameters as $parameter) {
                    $par = Parameters::query()->where('description', $parameter)->first();

                    if ($par) {
                        ConnectionParameter::firstOrCreate([
                            'parameter_id' => $par?->id,
                            'matrix_id' => $ma?->id,
                            'type_of_samples_id' => $type?->id
                        ]);
                    } else {
                        Log::error("NO SE ENCONTRO: $parameter");
                    }
                }
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
