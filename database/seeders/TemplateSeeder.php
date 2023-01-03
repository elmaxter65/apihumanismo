<?php

namespace Database\Seeders;

use App\Models\Template;
use Illuminate\Database\Seeder;

class TemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Template::create([
            'name' => 'X en 3 minutos',
            'index_content' => 1,
            'active' => 1,
            'init' => 0
        ]);

        Template::create([
            'name' => 'Estudios y análisis de proyectos',
            'index_content' => 1,
            'active' => 1,
            'init' => 0
        ]);

        Template::create([
            'name' => 'Tutoriales',
            'index_content' => 1,
            'active' => 1,
            'init' => 0
        ]);

        Template::create([
            'name' => 'Academia',
            'index_content' => 1,
            'active' => 1,
            'init' => 0
        ]);

        Template::create([
            'name' => 'Glosario',
            'index_content' => 1,
            'active' => 1,
            'init' => 0
        ]);

        Template::create([
            'name' => 'Informes',
            'index_content' => 1,
            'active' => 1,
            'init' => 0
        ]);

        Template::create([
            'name' => 'Webinars',
            'index_content' => 1,
            'active' => 1,
            'init' => 0
        ]);

        Template::create([
            'name' => 'Resúmen libros',
            'index_content' => 1,
            'active' => 1,
            'init' => 0
        ]);

        Template::create([
            'name' => 'Guías',
            'index_content' => 1,
            'active' => 1,
            'init' => 0
        ]);

        Template::create([
            'name' => 'Estudios de proyectos',
            'index_content' => 1,
            'active' => 1,
            'init' => 0
        ]);

        Template::create([
            'name' => 'Estudios y análisis de proyectos',
            'index_content' => 1,
            'active' => 1,
            'init' => 1
        ]);

        Template::create([
            'name' => 'Análisis de mercado',
            'index_content' => 1,
            'active' => 1,
            'init' => 1
        ]);

        Template::create([
            'name' => 'Glosario',
            'index_content' => 1,
            'active' => 1,
            'init' => 1
        ]);

    }
}
