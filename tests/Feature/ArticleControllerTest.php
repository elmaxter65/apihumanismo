<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Article;
use App\Models\Level;
use App\Models\Role;
use App\Models\Status;

class ArticleControllerTest extends TestCase
{
    use WithFaker, RefreshDatabase;
    /**
    * @author Rafael Duarte <elyouus94@gmail.com>
    * @date 09/12/2021
    * @description test if exists data in the index of articles
    */
    public function test_index()
    {
        $this->withoutExceptionHandling();
        $role       = Role::create(
            [
                'code' => 'WRT',
                'name' => 'Escritor'
            ]
        );
        $user       = User::factory()->create(['role_id' => $role->id]);
        $status     = Status::create(
            [
            'code' => 'PUB',
            'name' => 'Publicada'
            ]
        );

        $level      = Level::create(
            [
            'code' => 'PRI',
            'name' => 'Principiante'
            ]
        );
        $article    = Article::factory()->create(
            [
                'author_id' => $user->id,
                'status_id' => $status->id,
                'level_id' => $level->id
            ]
        );

        $this
            ->actingAs($user)
            ->get('news')
            ->assertStatus(200)
            ->assertSee($article->id);
    }
    /**#@-*/

    /**
    * @author Rafael Duarte <elyouus94@gmail.com>
    * @date 09/12/2021
    * @description test
    */

    /**#@-*/
}
