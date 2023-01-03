<?php

namespace Database\Seeders;

use App\Models\Entry;
use App\Models\EntryLanguage;
use Database\Factories\EntryFactory;
use Database\Factories\EntryLanguageFactory;
use Illuminate\Database\Seeder;

class EntrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $entries = Entry::factory()->count(30)->create();
        if ($entries) {
            EntryLanguage::factory()->count(60)->create();
        }
    }
}
