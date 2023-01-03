<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Notice;
use App\Models\Role;
use App\Models\Status;
use App\Models\Category;
use App\Models\Tag;

class NewsControllerTest extends TestCase
{
    use WithFaker, RefreshDatabase;
    /**
    * @author Rafael Duarte <elyouus94@gmail.com>
    * @date 09/12/2021
    * @description test if exists data in the index of articles
    */

    public function test_guest()
    {
        $this->get( 'news' )->assertRedirect( '/' );
        $this->get( 'news/1' )->assertRedirect( '/' );
        $this->get( 'news/1/edit' )->assertRedirect( '/' );
        $this->put( 'news/1/' )->assertRedirect( '/' );
        $this->delete( 'news/1' )->assertRedirect( '/' );
        $this->get( 'news/create' )->assertRedirect( '/' );
        $this->post( 'news', [] )->assertRedirect( '/' );
    }

    public function test_index_with_data()
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

        $category   = Category::factory()->create();

        $new        = Notice::factory()->create(
            [
                'author_id'     => $user->id,
                'status_id'     => $status->id,
                'category_id'   => $category->id
            ]
        );

        $this
            ->actingAs($user)
            ->get('news')
            ->assertStatus(200)
            ->assertSee($new->id);
    }
    /**#@-*/

    /**
    * @author Rafael Duarte <elyouus94@gmail.com>
    * @date 09/12/2021
    * @description test show
    */
    public function test_show()
    {
        //$this->withoutExceptionHandling();
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

        $category   = Category::factory()->create();

        $new        = Notice::factory()->create(
            [
                'author_id'     => $user->id,
                'status_id'     => $status->id,
                'category_id'   => $category->id
            ]
        );

        //dd($new);
        $this
            ->actingAs($user)
            ->get("news/$new->id")
            ->assertStatus(200);
    }
    /**#@-*/

    public function test_show_policy()
    {
        //$this->withoutExceptionHandling();
        $user       = User::factory()->create();
        $new        = Notice::factory()->create(
            [
                'author_id'     => User::factory(),
                'status_id'     => Status::factory(),
                'category_id'   => Category::factory()
            ]
        );
        $this
            ->actingAs($user)
            ->get("news/$new->id")
            ->assertStatus(403);
    }
    /**#@-*/

    /**
    * @author Rafael Duarte <elyouus94@gmail.com>
    * @date 09/12/2021
    * @description test create new
    */
    function test_create()
    {
        $role       = Role::create(
            [
                'code' => 'WRT',
                'name' => 'Escritor'
            ]
        );
        $user       = User::factory()->create(['role_id' => $role->id]);

        $this
            ->actingAs($user)
            ->get('news/create')
            ->assertStatus(200);
    }
    /**#@-*/

    /**
    * @author Rafael Duarte <elyouus94@gmail.com>
    * @date 09/12/2021
    * @description test store new
    */
    public function test_store()
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

        $noticeTag  = Tag::factory()->count(10)->create();

        $tags = array();

        foreach( $noticeTag as $key => $tag ) {
            array_push( $tags, $tag->id );
        }

        $category   = Category::factory()->create();

        $data       =  [
            'title'         => $this->faker->text(100),
            'subtitle'      => $this->faker->text(100),
            'content'       => $this->faker->text(900),
            'slug'          => $this->faker->slug,
            'views_number'  => 0,
            'url_video'     => $this->faker->url,
            'appears_home'  => null,
            'author_id'     => $user->id,
            'status'        => $status->id,
            'category'      => $category->id,
            'tags'          => $tags,
        ];

        $this
            ->actingAs( $user )
            ->post( 'news', $data )
            ->assertRedirect( 'news' );

        unset( $data['tags'] );
        $data['status_id'] = $status->id;
        $data['category_id'] = $status->id;
        unset($data['status'] );
        unset($data['category'] );

        $this->assertDatabaseHas( 'notices', $data );
    }

    public function test_validate_store()
    {
        $user       = User::factory()->create();
        $this
            ->actingAs( $user )
            ->post( 'news', [] )
            ->assertStatus(302)
            ->assertSessionHasErrors(['title', 'content']);
    }
    /**#@-*/

    /**
    * @author Rafael Duarte <elyouus94@gmail.com>
    * @date 09/12/2021
    * @description test edit
    */
    public function test_edit()
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

        $category   = Category::factory()->create();

        $new = Notice::factory()->create( [
            'author_id'     => $user->id,
            'status_id'     => $status->id,
            'category_id'   => $category->id
        ] );

        //dd( $new );

        $this
            ->actingAs( $user )
            ->get("news/$new->id/edit")
            ->assertStatus(200)
            ->assertSee($new->title)
            ->assertSee($new->subtitle);
    }


    public function test_edit_policy()
    {
        //$this->withoutExceptionHandling();
        $user       = User::factory()->create();
        $new        = Notice::factory()->create([
            'author_id'     => User::factory(),
            'status_id'     => Status::factory(),
            'category_id'   => Category::factory()
        ]);
        $this
            ->actingAs($user)
            ->get("news/$new->id/edit")
            ->assertStatus(403);
    }
    /**#@-*/

    public function test_update()
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

        $noticeTag  = Tag::factory()->count(10)->create();

        $tags = array();

        foreach( $noticeTag as $key => $tag ) {
            array_push( $tags, $tag->id );
        }

        $category   = Category::factory()->create();

        $new = Notice::factory()->create( [
            'author_id'     => $user->id,
            'status_id'     => $status->id,
            'category_id'   => $category->id,
        ] );

        $data = array(
            'title'         => $this->faker->text(100),
            'subtitle'      => $this->faker->text(100),
            'content'       => $this->faker->text(900),
            'slug'          => $this->faker->slug,
            'views_number'  => 3,
            //'image'         => $this->faker->image,
            'url_video'     => $this->faker->url,
            'appears_home'  => 1,
            'author_id'     => $user->id,
            'status'        => $status->id,
            'category'      => $category->id,
            'tags'          => $tags,
        );

        //dd( $data );

        $this
            ->actingAs( $user )
            ->put("news/$new->id", $data)
            ->assertRedirect("news/$new->id/edit");

        unset( $data['tags'] );
        $data['status_id'] = $status->id;
        $data['category_id'] = $status->id;
        unset($data['status'] );
        unset($data['category'] );

        $this->assertDatabaseHas( 'notices', $data );
    }

    public function test_update_policy()
    {
        //$this->withoutExceptionHandling();
        $user       = User::factory()->create();
        $new        = Notice::factory()->create([
            'author_id'     => User::factory(),
            'status_id'     => Status::factory(),
            'category_id'   => Category::factory()
        ]);
        $data       = array(
            'title'     => $this->faker->text(100),
            'subtitle'  => $this->faker->text(100),
        );
        $this
            ->actingAs($user)
            ->get("news/$new->id", $data)
            ->assertStatus(403);
    }

    public function test_validate_update()
    {
        $user       = User::factory()->create();
        $new        = Notice::factory()->create(
            [
                'author_id'     => User::factory(),
                'status_id'     => Status::factory(),
                'category_id'   => Category::factory()
            ]
        );

        $this
            ->actingAs($user)
            ->put("news/$new->id", [])
            ->assertStatus(302)
            ->assertSessionHasErrors([ 'title', 'content' ]);
    }

    /**
    * @author Rafael Duarte <elyouus94@gmail.com>
    * @date 09/12/2021
    * @description test show
    */
    public function test_delete()
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

        $category   = Category::factory()->create();

        $new        = Notice::factory()->create(
            [
                'author_id'     => $user->id,
                'status_id'     => $status->id,
                'category_id'   => $category->id
            ]
        );

        $this
            ->actingAs($user)
            ->delete("news/$new->id")
            ->assertRedirect( 'news' );

        $this->assertSoftDeleted( 'notices', [
            'id'        => $new->id,
            'title'     => $new->title,
            'subtitle'  => $new->subtitle
        ]);
    }

    public function test_destroy_policy()
    {
        //$this->withoutExceptionHandling();
        $user       = User::factory()->create();
        $new        = Notice::factory()->create([
            'author_id'     => User::factory(),
            'status_id'     => Status::factory(),
            'category_id'   => Category::factory()
        ]);
        $this
            ->actingAs($user)
            ->delete("news/$new->id")
            ->assertStatus(403);
    }
    /**#@-*/
}
