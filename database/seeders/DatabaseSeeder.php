<?php

namespace Database\Seeders;

use App\Models\tenant\User;
use App\Models\User as ModelsUser;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void {
        ModelsUser::create([
            'name' => 'asd',
            'email' => 'asd',
            'password' => '12345678',
        ]);
    }
}
