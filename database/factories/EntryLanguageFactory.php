<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Entry;
use App\Models\Language;

class EntryLanguageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $entries = Entry::select('id')->get();
        $languages = Language::select('id')->where('code','=','ESP')->get();

        return [
            'title'         => $this->faker->text(35),
            'subtitle'      => $this->faker->text(35),
            'video_transcription' => $this->faker->text(45),
            'content'       => $this->faker->text(200),
            'meta_description' => $this->faker->text(50),
            'seo_title'     => $this->faker->text(50),
            'slug'          => $this->faker->slug,
            'url_video_youtube' => 'https://www.youtube.com/watch?v=UPJsvlkYh9w',
            'url_video_vimeo' => $this->faker->url,
            'url_audio'     => $this->faker->url,
            'entry_id'      => $this->faker->randomElement( $entries ),
            'language_id'   => $this->faker->randomElement( $languages )
        ];
    }
}
