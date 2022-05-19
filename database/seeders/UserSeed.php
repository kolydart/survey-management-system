<?php

namespace Database\Seeders;

use App\User;
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
        $users = [

            ['id' => 1, 'name' => 'Admin', 'email' => 'admin@admin.com', 'password' => Hash::make('password'), 'role_id' => 1, 'remember_token' => ''],
            ['id' => 2, 'name' => 'Moderator', 'email' => 'moderator@moderator.com', 'password' => Hash::make('password'), 'role_id' => 2, 'remember_token' => ''],
            ['id' => 3, 'name' => 'User', 'email' => 'user@user.com', 'password' => Hash::make('password'), 'role_id' => 3, 'remember_token' => ''],

        ];

        User::upsert($users,['id'],['name', 'email', 'password', 'role_id', 'remember_token',]);
    }
}
