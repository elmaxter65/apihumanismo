<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Theme;
use App\Models\Status;
use App\Models\EntryType;

class EntryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $authors = User::select('id')->get();
		$statuses = Status::select('id')->where('code','=','PUB')->get();
		$themes = Theme::select('id')->get();
        $entryTypes = EntryType::select('id')->where('code','=','VID')->get();

        return [
            'index_content' => 1,
            'views_number'  => 3,
            'reading_time'  => 2,
            'appears_home'  => 1,
			'author_id'     => $this->faker->randomElement( $authors ),
			'status_id'     => $this->faker->randomElement( $statuses ),
			'theme_id'      => $this->faker->randomElement( $themes ),
            'entry_type_id' => $this->faker->randomElement( $entryTypes )
        ];

    }
}
