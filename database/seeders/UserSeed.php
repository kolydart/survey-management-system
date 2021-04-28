<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [

            ['id' => 1, 'name' => 'Admin', 'email' => 'admin@admin.com', 'password' => Hash::make('password'), 'role_id' => 1, 'remember_token' => ''],

        ];

        foreach ($items as $item) {
            \App\User::create($item);
        }
    }
}
