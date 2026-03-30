<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'Super Admin',
            'Comercial',
            'Recepción',
            'Profesional',
        ];

        foreach ($roles as $key => $value) {
            Role::create([
                'name' => $value,
                'guard_name' => 'api'
            ]);
        }
    }
}
