<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Codenixsv\CoinGeckoApi\CoinGeckoClient;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call( EntryTypeSeeder::class );
        $this->command->info('entry_types table seeded!');
        $this->call( SectionsSeeder::class );
        $this->command->info('sections table seeded!');
        $this->call( ThemesSeeder::class );
        $this->command->info('themes table seeded!');
        $this->call( RolesSeeder::class );
        $this->command->info('roles table seeded!');
        $this->call( LanguageSeeder::class );
        $this->command->info('languages table seeded!');
        $this->call( StatusSeeder::class );
        $this->command->info('statuses table seeded!');
        $this->call( NewsletterSeeder::class );
        $this->command->info('newsletter table seeded!');
        $this->call( UsersSeeder::class );
        $this->command->info('users table seeded!');
        $this->call( EntrySeeder::class );
        $this->command->info('entries table seeded!');
        $this->command->info('entry_languages table seeded!');
        $this->call( TemplateSeeder::class );
        $this->command->info('templates table seeded!');
        $this->call( TagSeeder::class );
        $this->command->info('tags table seeded!');
        $this->call( TagLanguageSeeder::class );
        $this->command->info('tags languages table seeded!');
    }
}
