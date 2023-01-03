<?php

namespace Database\Seeders;

use App\Models\Newsletter;
use Illuminate\Database\Seeder;

class NewsletterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Newsletter::create([
            'code' => 'DAY',
            'name' => 'Diaria'
        ]);

        Newsletter::create([
            'code' => 'WEK',
            'name' => 'Semanal'
        ]);
    }
}
