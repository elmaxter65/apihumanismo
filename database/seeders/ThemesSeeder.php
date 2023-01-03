<?php

namespace Database\Seeders;

use App\Models\Theme;
use App\Models\Section;
use App\Models\ThemeSection;
use Illuminate\Database\Seeder;

class ThemesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $section = Section::where('code', '=', 'INI')->first();
        $section_id = $section->id;

        $section1 = Section::where('code', '=', 'CON')->first();
        $section_id1 = $section1->id;

        $theme1 = Theme::create([
            'name' => 'Estudios y análisis de proyectos',
            'slug' => 'estudios-y-análisis-de-proyectos',
        ]);

        $theme2 = Theme::create([
            'name' => 'Análisis de mercado',
            'slug' => 'analisis-de-mercado',
        ]);

        $theme3 = Theme::create([
            'name' => 'Glosario',
            'slug' => 'glosario',
        ]);

        /**********************/

        ThemeSection::create([
            'theme_id' => $theme1->id,
            'section_id' => $section_id
        ]);

        ThemeSection::create([
            'theme_id' => $theme2->id,
            'section_id' => $section_id
        ]);

        ThemeSection::create([
            'theme_id' => $theme3->id,
            'section_id' => $section_id
        ]);

        /**********************/

        $theme4 = Theme::create([
            'name' => 'Bitcoin',
            'slug' => 'bitcoin',
        ]);

        $theme5 = Theme::create([
            'name' => 'DEFI',
            'slug' => 'defi',
        ]);

        $theme6 = Theme::create([
            'name' => 'NFTs',
            'slug' => 'nfts',
        ]);

        $theme7 = Theme::create([
            'name' => 'Play to Earn',
            'slug' => 'play-to-earn',
        ]);

        $theme8 = Theme::create([
            'name' => 'Metaverso',
            'slug' => 'metaverso',
        ]);

        $theme9 = Theme::create([
            'name' => 'Trading',
            'slug' => 'trading',
        ]);

        $theme10 = Theme::create([
            'name' => 'Analíticas',
            'slug' => 'analiticas',
        ]);

        $theme11 = Theme::create([
            'name' => 'Noticias',
            'slug' => 'noticias',
        ]);

        $theme12 = Theme::create([
            'name' => 'Trending',
            'slug' => 'trending',
        ]);

        $theme13 = Theme::create([
            'name' => 'Economía',
            'slug' => 'economia',
        ]);

        $theme14 = Theme::create([
            'name' => 'Shitcoins',
            'slug' => 'shitcoins',
        ]);

        /**********************/

        ThemeSection::create([
            'theme_id' => $theme4->id,
            'section_id' => $section_id
        ]);

        ThemeSection::create([
            'theme_id' => $theme5->id,
            'section_id' => $section_id
        ]);

        ThemeSection::create([
            'theme_id' => $theme6->id,
            'section_id' => $section_id
        ]);

        ThemeSection::create([
            'theme_id' => $theme7->id,
            'section_id' => $section_id
        ]);

        ThemeSection::create([
            'theme_id' => $theme8->id,
            'section_id' => $section_id
        ]);

        ThemeSection::create([
            'theme_id' => $theme9->id,
            'section_id' => $section_id
        ]);

        ThemeSection::create([
            'theme_id' => $theme10->id,
            'section_id' => $section_id
        ]);

        ThemeSection::create([
            'theme_id' => $theme11->id,
            'section_id' => $section_id
        ]);

        ThemeSection::create([
            'theme_id' => $theme12->id,
            'section_id' => $section_id
        ]);

        ThemeSection::create([
            'theme_id' => $theme13->id,
            'section_id' => $section_id
        ]);

        ThemeSection::create([
            'theme_id' => $theme14->id,
            'section_id' => $section_id
        ]);

        /**********************/

        ThemeSection::create([
            'theme_id' => $theme4->id,
            'section_id' => $section_id1
        ]);

        ThemeSection::create([
            'theme_id' => $theme5->id,
            'section_id' => $section_id1
        ]);

        ThemeSection::create([
            'theme_id' => $theme6->id,
            'section_id' => $section_id1
        ]);

        ThemeSection::create([
            'theme_id' => $theme7->id,
            'section_id' => $section_id1
        ]);

        ThemeSection::create([
            'theme_id' => $theme8->id,
            'section_id' => $section_id1
        ]);

        ThemeSection::create([
            'theme_id' => $theme9->id,
            'section_id' => $section_id1
        ]);

        ThemeSection::create([
            'theme_id' => $theme10->id,
            'section_id' => $section_id1
        ]);

        ThemeSection::create([
            'theme_id' => $theme11->id,
            'section_id' => $section_id1
        ]);

        ThemeSection::create([
            'theme_id' => $theme12->id,
            'section_id' => $section_id1
        ]);

        ThemeSection::create([
            'theme_id' => $theme13->id,
            'section_id' => $section_id1
        ]);

        ThemeSection::create([
            'theme_id' => $theme14->id,
            'section_id' => $section_id1
        ]);
    }
}
