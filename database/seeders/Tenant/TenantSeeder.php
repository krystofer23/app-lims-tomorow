<?php

namespace Database\Seeders\Tenant;

use App\Models\tenant\User;
use Database\Seeders\RolesSeeder;
use Illuminate\Database\Seeder;

class TenantSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolesSeeder::class
        ]);

        $user = User::create([
            'name' => 'SUPER',
            'last_name_first' => 'ADMIN',
            'last_name_second' => 'ADMIN',
            'full_name' => 'SUPER ADMIN',
            'type_document' => 'DNI',
            'document_number' => '00000000',
            'sex' => 'MASCULINO',
            'email' => 'super@greenlab.com',
            'password' => 'sofdemo#123',
            'active' => true
        ]);

        $user->assignRole('Super Admin');
    }
}
