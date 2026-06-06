<?php

namespace Database\Seeders;

use App\Models\tenant\Category;
use App\Models\tenant\Conditions;
use App\Models\tenant\ConnectionParameter;
use App\Models\tenant\Matrix;
use App\Models\tenant\Parameters;
use App\Models\tenant\Procedure;
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
            $procedures = [
                // Agua
                'PO-01 Procedimiento General de Muestreo.',
                'PO-02 Transporte, almacenamiento-mantenimiento de equipos y materiales',
                'PO-03 Aseguramiento de Calidad en el Muestreo',
                'PO-04 Análisis de Mediciones de Agua en Campo',
                'PO-07 Muestreo de calidad de aguas',
                'PO-39 Medición de caudales en Ducto Cerrados - Método Ultrasónico',

                // Aire
                'PO-01 Procedimiento General de Muestreo.',
                'PO-02 Transporte, almacenamiento-mantenimiento de equipos y materiales',
                'PO-03 Aseguramiento de Calidad en el Muestreo',
                'PO-11 Medición de Calidad de Aire Material Particulado- (PM10 Y PM2.5)',
                'PO-13 Muestreo de calidad de aire con tren de muestreo',
                'PO-19 Procedimiento de Muestreo de Partículas Respirables e Inhalables',
                'PO-23 Muestreo de calidad de Aire con Equipos Automáticos',
                'PO-37 Hg Gaseoso Total_Calidad de Aire',
                'PO-06 Registro de parámetros meteorológicos',

                // Emisiones
                'PO-01 Procedimiento General de Muestreo.',
                'PO-02 Transporte, almacenamiento-mantenimiento de equipos y materiales',
                'PO-03 Aseguramiento de Calidad en el Muestreo',
                'PO-20 Monitoreo de Gases de Combustión y Determinación de Velocidad en Emisiones de Fuentes Fijas',
                'PO-21 Muestreo de Emisiones de Material Particulado en Fuentes Fijas - Monitoreo Isocinético (Método EPA 5)',
                'PO-27 Muestreo Isocinético_Metales en Fuentes Fijas ( Method EPA 29)',
                'PO-28 Muestreo de Dioxido de Azufre en Fuentes Fijas_Metodo EPA 6 ',
                'PO-29 Determinación de Azufre Total Reducido desde Fuentes Fijas_Método EPA 16A',
                'PO-30 Determinación de la Eficiencia de Recuperación_Método EPA 16A',
                'PO-31 Determinacion de emisiones de óxido de nitrógeno en fuentes estacionarias',
                'PO-32 Determinacion del NOx en fuentes fijas - Metodo 7E',
                'PO-33 Medición de Emisiones Gaseosas, Velocidad y Flujo Volumétrico',
                'PO-34 Determinacion  de Opacidad',
                'PO-35 Determinación del Oxígeno y Dióxido de Carbono en las Emisiones de Fuentes Estacionarias (Procedimiento del Analizador Instrumental) –Método 3A',
                'PO-36 Determinación del Monóxido de Carbono en Fuentes Fijas – Método 10',
                'PO-41 Determinación de VOCs en Fuentes Fijas_Method EPA 18',
                'PO-42 Ácido Sulfúrico (incluyendo niebla H SO  y SO )  y las Emisiones de dióxido de azufre (SO2) de fuentes estacionarias',
                'PO-44 Determinación del Oxígeno y Dióxido de Carbono en las Emisiones de Fuentes Estacionarias–Método 3',
                'PO-45 Manejo de equipo ORSAT para Emisiones de Fuentes Estacionarias–Método EPA 3',
                'PO-46 Determinación de SO2 en fuentes fijas EPA 6C',

                // RNI
                'PO-01 Procedimiento General de Muestreo.',
                'PO-02 Transporte, almacenamiento-mantenimiento de equipos y materiales',
                'PO-03 Aseguramiento de Calidad en el Muestreo',
                'PO-26 Procedimiento para mediciones de campo magnético y eléctrico ',

            ];

            foreach ($procedures as $key => $value) {
                Procedure::updateOrCreate([
                    'description' => $value
                ]);
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
