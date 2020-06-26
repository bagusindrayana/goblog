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
        DB::insert("INSERT INTO gb_accesses (id, access_name, access_action, created_at, updated_at, deleted_at) VALUES
        (1, 'Post', 'View', NULL, NULL, NULL),
        (2, 'Post', 'Create', NULL, NULL, NULL),
        (3, 'Post', 'Update', NULL, NULL, NULL),
        (4, 'Post', 'Delete', NULL, NULL, NULL),
        (5, 'Tag', 'View', NULL, NULL, NULL),
        (6, 'Tag', 'Create', NULL, NULL, NULL),
        (7, 'Tag', 'Update', NULL, NULL, NULL),
        (8, 'Tag', 'Delete', NULL, NULL, NULL),
        (9, 'Category', 'View', NULL, NULL, NULL),
        (10, 'Category', 'Create', NULL, NULL, NULL),
        (11, 'Category', 'Update', NULL, NULL, NULL),
        (12, 'Category', 'Delete', NULL, NULL, NULL),
        (13, 'Page', 'View', NULL, NULL, NULL),
        (14, 'Page', 'Create', NULL, NULL, NULL),
        (15, 'Page', 'Update', NULL, NULL, NULL),
        (16, 'Page', 'Delete', NULL, NULL, NULL),
        (17, 'User', 'View', NULL, NULL, NULL),
        (18, 'User', 'Create', NULL, NULL, NULL),
        (19, 'User', 'Update', NULL, NULL, NULL),
        (20, 'User', 'Delete', NULL, NULL, NULL);");

        DB::table('roles')->insert([
            'id'=>1,
            'role_name'=>'Admin'
        ]);

        DB::insert("INSERT INTO gb_role_accesses (id, role_id, access_id, created_at, updated_at, deleted_at) VALUES
        (1, 1, 1, NULL, NULL, NULL),
        (2, 1, 2, NULL, NULL, NULL),
        (3, 1, 3, NULL, NULL, NULL),
        (4, 1, 4, NULL, NULL, NULL),
        (5, 1, 5, NULL, NULL, NULL),
        (6, 1, 6, NULL, NULL, NULL),
        (7, 1, 7, NULL, NULL, NULL),
        (8, 1, 8, NULL, NULL, NULL),
        (9, 1, 9, NULL, NULL, NULL),
        (10, 1, 10, NULL, NULL, NULL),
        (11, 1, 11, NULL, NULL, NULL),
        (12, 1, 12, NULL, NULL, NULL),
        (13, 1, 13, NULL, NULL, NULL),
        (14, 1, 14, NULL, NULL, NULL),
        (15, 1, 15, NULL, NULL, NULL),
        (16, 1, 16, NULL, NULL, NULL),
        (17, 1, 17, NULL, NULL, NULL),
        (18, 1, 18, NULL, NULL, NULL),
        (19, 1, 19, NULL, NULL, NULL),
        (20, 1, 20, NULL, NULL, NULL);");

        DB::table('users')->insert([
            'name'=>'Admin',
            'email'=>'admin@email.com',
            'password'=>Hash::make('admin4321'),
            'api_token'=>Str::random(100),
            'role_id'=>1
        ]);
    }
}
