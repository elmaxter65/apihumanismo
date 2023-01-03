<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Language::create([
            'code' => 'ESP',
            'name' => 'Español'
        ]);

        Language::create([
            'code' => 'ENG',
            'name' => 'Inglés'
        ]);
    }
}
