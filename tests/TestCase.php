<?php

namespace Tests;

use App\Role;
use App\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * acting as admin
     * @return signed $user model
     */
    public function signin_as($role_title, $role_id)
    {
        $role = factory(Role::class)->create(['title'=>$role_title, 'id'=>$role_id]);

        $this->user = factory(User::class)->create(['role_id'=>$role_id]);
        $this->actingAs($this->user);

        return $this->user;
    }
}
