<?php

namespace Database\Seeders;

use App\Models\Language;
use App\Models\Newsletter;
use App\Models\Role;
use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
  	    $tok = substr(str_shuffle($permitted_chars), 0, 64);
	    $role = Role::select('id')->where('code','=','ADM')->first();
        $newsletter = Newsletter::select('id')->where('code','=','WEK')->first();

        User::create([
            'name'                  => 'Daniel Peña',
            'email'                 => 'daniel@mundocryptotv.com',
            'email_verified_at'     => now(),
            'password'              => bcrypt('*@Dmin1#'),
            'date_birth'            => '1980-12-04',
            'gender'                => 'M',
            'adult'                 => 1,
            'accept_private_policy' => 1,
            'active'                => 1,
            'token'                 => $tok,
            'role_id'               => $role->id,
            'newsletter_id'         => $newsletter->id
        ]);

        User::create([
            'name'                  => 'Rafael Duarte',
            'email'                 => 'rduarte@mundocryptotv.com',
            'email_verified_at'     => now(),
            'password'              => bcrypt('Admin21%.2021'),
            'date_birth'            => '1990-01-01',
            'gender'                => 'M',
            'adult'                 => 1,
            'accept_private_policy' => 1,
            'active'                => 1,
            'token'                 => $tok,
            'role_id'               => $role->id,
            'newsletter_id'         => $newsletter->id
        ]);

        User::create([
            'name'                  => 'Manuel Ramos',
            'email'                 => 'mramos@mundocryptotv.com',
            'email_verified_at'     => now(),
            'password'              => bcrypt('Leon2021'),
            'date_birth'            => '1990-01-01',
            'gender'                => 'M',
            'adult'                 => 1,
            'accept_private_policy' => 1,
            'active'                => 1,
            'token'                 => $tok,
            'role_id'               => $role->id,
            'newsletter_id'         => $newsletter->id
        ]);

        User::create([
            'name'                  => 'Administrador',
            'email'                 => 'admin@mundocryptotv.com',
            'email_verified_at'     => now(),
            'password'              => bcrypt('Leon2021'),
            'date_birth'            => '1990-01-01',
            'gender'                => 'M',
            'adult'                 => 1,
            'accept_private_policy' => 1,
            'active'                => 1,
            'token'                 => $tok,
            'role_id'               => $role->id,
            'newsletter_id'         => $newsletter->id
        ]);

        User::factory()->count(30)->create();
    }
}
