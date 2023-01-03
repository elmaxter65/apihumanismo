<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Status::create([
            'code' => 'PUB',
            'name' => 'Publicada'
        ]);
    
        Status::create([
            'code' => 'ARC',
            'name' => 'Archivada'
        ]);
    
        Status::create([
            'code' => 'BOR',
            'name' => 'Borrador'
        ]);
    }
}
