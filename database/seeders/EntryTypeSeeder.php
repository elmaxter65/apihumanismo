<?php

namespace Database\Seeders;

use App\Models\EntryType;
use Illuminate\Database\Seeder;

class EntryTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EntryType::create([
            'code' => 'VID',
            'name' => 'Vídeos'
        ]);

        EntryType::create([
            'code' => 'OTR',
            'name' => 'Otros'
        ]);

    }
}
