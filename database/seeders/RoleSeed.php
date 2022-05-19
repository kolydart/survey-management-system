<?php

namespace Database\Seeders;

use App\Role;
use Illuminate\Database\Seeder;

class RoleSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arr = [

            ['id' => 1, 'title' => 'Admin'],
            ['id' => 2, 'title' => 'Moderator'],
            ['id' => 3, 'title' => 'User'],

        ];
        Role::upsert($arr,'id',['title']);
    }
}
