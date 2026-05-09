<?php

namespace Database\Seeders;

use App\Models\tenant\Conditions;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConditionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $conditions = [
            'INACAL',
            'IAS',
            'NO ACREDITADO',
        ];

        foreach ($conditions as $val) {
            Conditions::create([
                'description' => $val
            ]);
        }
    }
}
