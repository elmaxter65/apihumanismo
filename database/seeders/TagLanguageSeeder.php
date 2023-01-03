<?php

namespace Database\Seeders;

use App\Models\TagLanguage;
use Illuminate\Database\Seeder;

class TagLanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TagLanguage::factory()->count(100)->create();
    }
}
