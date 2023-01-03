<?php

namespace Database\Factories;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;

class TagLanguageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $tags = Tag::all();
        return [
            'tag_id'        => $this->faker->randomElement( $tags ),
            'language_id'   => 1,
            'name'          => $this->faker->name(),
            'slug'          => $this->faker->slug(),
        ];
    }
}
