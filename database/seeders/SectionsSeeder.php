<?php

namespace Database\Seeders;

use App\Models\Section;
use Illuminate\Database\Seeder;

class SectionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Section::create([
            'code' => 'INI',
            'name' => 'Inicio'
        ]);

        Section::create([
            'code' => 'CON',
            'name' => 'Contenido'
        ]);

    }
}
