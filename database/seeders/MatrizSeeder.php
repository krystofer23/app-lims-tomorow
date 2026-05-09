<?php

namespace Database\Seeders;

use App\Models\tenant\ReferencesStandard;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MatrizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $matriz = [
            'AIRE' => [
                'INACAL' => [
                    'Solución Captadora' => [
                        [
                            'parameter' => '- Dióxido de Nitrógeno (NO2).',
                            'codigo' => 'ASTM D1607-91 (validado modificado). No incluye muestreo. (2018)',
                            'title' => 'Método de prueba estándar para el contenido de dióxido de nitrógeno de la atmósfera (reacción de Griess-Saltzman).',
                            'unit' => 'ug/muestra',
                            'lcm' => '0,10',
                        ],
                        [
                            'parameter' => '- Monóxido de Carbono (CO).',
                            'codigo' => 'GREENLAB - 009. (Basado en EPA Method 10A) (validado). No incluye muestreo. (2018)',
                            'title' => 'Determinación de Monóxido de Carbono en la atmósfera.',
                            'unit' => 'ug/muestra',
                            'lcm' => '135',
                        ],
                        [
                            'parameter' => '- Sulfuro de Hidrógeno (H2S).',
                            'codigo' => 'Method of Air Sampling and Analysis. James P. Lodge, Jr., 3rd Ed: 1989. Part 701 - (Validado Modificado). No incluye muestreo. (2018)',
                            'title' => 'Determinación de la concentración de sulfuro de hidrógeno (H2S) en la atmósfera',
                            'unit' => 'ug/muestra',
                            'lcm' => '0,8',
                        ],
                        [
                            'parameter' => '- Ozono (O3).',
                            'codigo' => 'GREENLAB-010 (Basado en Lodge James, Methods of air sampling and analysis, 401) (validado). No incluye muestreo. (2018)',
                            'title' => 'Método de Determinación de Ozono en la Atmósfera.',
                            'unit' => 'ug/muestra',
                            'lcm' => '0,30',
                        ],
                        [
                            'parameter' => '- Dióxido de Azufre (SO2).',
                            'codigo' => 'EPA CFR 40. Appendix A-2 to part 50. (validado modificado). No incluye muestreo. (2018)',
                            'title' => 'Método de referencia para la determinación de dióxido de azufre en la atmósfera. (Método Pararosanilina).',
                            'unit' => 'ug/muestra',
                            'lcm' => '3,7',
                        ],
                    ],
                    'Aire' => [
                        [
                            'parameter' => '- Monóxido de Carbono (CO) ⁽ᵇ⁾',
                            'codigo' => 'GREENLAB-006 (Basado en EPA Method 10A) (validado) (2018)',
                            'title' => 'Determinación de Monóxido de Carbono en la atmósfera.',
                            'unit' => 'ug/m3',
                            'lcm' => '561',
                        ],
                        [
                            'parameter' => '- Sulfuro de Hidrógeno (H2S) ⁽ᶜ⁾',
                            'codigo' => 'James P. Lodge Jr. - Methods of Air Sampling and Analysis, Third Edition, Part 701. 1980 (Validado-Modificado). (2018)',
                            'title' => 'Determinación de la concentración de sulfuro de hidrógeno (H2S) en la atmósfera',
                            'unit' => 'ug/m3',
                            'lcm' => '2,6',
                        ],
                        [
                            'parameter' => '- Ozono (O3) ⁽ᵇ⁾',
                            'codigo' => 'GREENLAB-001. (Basado en EPA CFR 40. Appendix J to part 50)(validado). (2018)',
                            'title' => 'Método de Determinación de Ozono en la Atmosfera.',
                            'unit' => 'ug/m3',
                            'lcm' => '1,00',
                        ],
                        [
                            'parameter' => '- Dióxido de Nitrógeno (NO2) ⁽ᵃ⁾',
                            'codigo' => 'ASTM D1607-91 (2018)',
                            'title' => 'Standard Test Method for Nitrogen Dioxide Content of the Atmosphere (Griess-Saltzman Reaction)',
                            'unit' => 'ug/m3',
                            'lcm' => '4',
                        ],
                        [
                            'parameter' => '- Dióxido de Azufre (SO2) ⁽ᶜ⁾',
                            'codigo' => 'EPA CFR 40. Appendix A-2 to part 50. (2018)',
                            'title' => 'Reference Method for the Determination of Sulfur Dioxide in the Atmosphere (Pararosaniline Method)',
                            'unit' => 'ug/m3',
                            'lcm' => '13',
                        ],
                        [
                            'parameter' => '- Material particulado  PM2.5 (Alto volumen)',
                            'codigo' => 'EPA CFR 40. Appendix J to part 50 (Validado aplicado fuera del alcance). (2018)',
                            'title' => 'Reference method for the determination of particulate matter as PM10 in the atmosphere.',
                            'unit' => 'ug/m3',
                            'lcm' => '1,5',
                        ],
                    ],
                    'Filtro' => [
                        [
                            'parameter' => '- Determinación de peso en Filtros PM2.5 Alto volumen.',
                            'codigo' => 'GREENLAB-003. (Basado en EPA CFR 40, Appendix J to part 50) (validado aplicado fuera del alcance).No incluye muestreo. (2018)',
                            'title' => 'Reference method for the determination of particulate matter as PM10 in the atmosphere',
                            'unit' => 'mg/filtro',
                            'lcm' => '3',
                        ],
                    ],
                    ''
                ]
            ]
        ];
    }
}
