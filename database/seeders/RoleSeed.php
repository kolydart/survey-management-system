<?php

namespace Database\Seeders;

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
        $items = [

            ['id' => 1, 'title' => 'admin'],
            ['id' => 2, 'title' => 'moderator'],
            ['id' => 3, 'title' => 'user'],

        ];

        foreach ($items as $item) {
            \App\Role::create($item);
        }
    }
}
