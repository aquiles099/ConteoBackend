<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::table('users')->insert([
            'name'           => 'master',
            'last_name'      => 'master',
            'rut'            => 'rut',
            'id_role'        => 1,
            'password'       => bcrypt(123456789),
            'email'          => 'master@conteo.com',
        ]);
        DB::table('users')->insert([
            'name'           => 'administrador',
            'last_name'      => 'administrador',
            'rut'            => 'rut',
            'id_role'        => 3,
            'password'       => bcrypt(123456789),
            'email'          => 'admin@conteo.com',
        ]);
        DB::table('users')->insert([
            'name'           => 'consultor',
            'last_name'      => 'consultor',
            'rut'            => 'rut',
            'id_role'        => 3,
            'password'       => bcrypt(123456789),
            'email'          => 'consultor@conteo.com',
        ]);
    }
}
