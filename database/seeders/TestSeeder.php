<?php

namespace Database\Seeders;

use App\Models\tenant\Category;
use App\Models\tenant\Matrix;
use App\Models\tenant\TypeOfSamples;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            'Agua Residual Doméstica',
            'Agua Residual Industrial',
            'Agua Residual Municipal',
            'Agua Superficial',
            'Agua Subterránea',
            'Agua Potable',
            'Agua de Piscina',
            'Agua de Laguna Artificial',
            'Agua de Circulación y Enfriamiento',
            'Agua de Alimentación de Calderas',
            'Agua de Calderas',
            'Agua de Lixiviación',
            'Agua Purificada',
            'Agua de Inyección y reinyección',
            'Agua de Mar',
            'Agua Salobre',
            'Agua de Reinyeccón',
            'Salmuera',

            'Aire',
            'Filtro',
            'Solución Captadora',
            'Tubo de Carbón Activado',

            'Emisiones',

            'Radiacion No Ionizante',
            'Radiacion Electromagnética',

            'Ruido Ambiental: Planta Industriales',
            'Ruido Ambiental: Tráfico Aéreo',
            'Ruido Ambiental: Tráfico Ferroviario',
            'Ruido Ambiental: Tráfico Rodado',
            'Ruido Ocupacional',

            'Salud Ocupacional',

            'Suelo',
            'Sedimento',
            'Lodo',

            'Vibración Ambiental',
            'Vibración cuerpo entero',
            'Vibracion mano-brazo',
            'Vibracion en edificios',

        ];

        foreach ($types as $value) {
            Matrix::firstOrCreate([
                'description' => $value
            ]);
        }
    }
}
