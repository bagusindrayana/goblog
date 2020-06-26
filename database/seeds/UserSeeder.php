<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        DB::table('roles')->insert([
            'role_name'=>'Admin'
        ]);

        DB::table('users')->insert([
            'name'=>'Admin',
            'email'=>'admin@email.com',
            'password'=>Hash::make('admin4321'),
            'api_token'=>Str::random(100),
            'role_id'=>1
        ]);
    }
}
