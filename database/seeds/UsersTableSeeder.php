<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Perfil;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin'),
            'type' => 'admin',
        ]);
        Perfil::create([
            'user_id' => $user->id,
            'nombre' => $user->name,
            'informacion' => 'Informacion.',
            'descripcion' => 'Descripcion.',
            'imagen' => 'image/perfil/avatar.png',
        ]);
        $user = User::create([
            'name' => 'super_admin',
            'email' => 'super_admin@gmail.com',
            'password' => bcrypt('super_admin'),
            'type' => 'super_admin',
        ]);
        Perfil::create([
            'user_id' => $user->id,
            'nombre' => $user->name,
            'informacion' => 'Informacion.',
            'descripcion' => 'Descripcion.',
            'imagen' => 'image/perfil/avatar.png',
        ]);
    }
}
