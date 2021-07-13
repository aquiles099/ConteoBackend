<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'name'           => 'master',
            'description'      => 'master',
        ]);
        DB::table('roles')->insert([
            'name'           => 'administrador',
            'description'      => 'administrador',
        ]);
        DB::table('roles')->insert([
            'name'           => 'consultor',
            'description'      => 'consultor',
        ]);
    }
}
