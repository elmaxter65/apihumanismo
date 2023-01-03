<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'code' => 'ADM',
            'name' => 'Administrador'
        ]);

        Role::create([
            'code' => 'WRT',
            'name' => 'Escritor'
        ]);

        Role::create([
            'code' => 'USR',
            'name' => 'Usuario'
        ]);
    }
}
