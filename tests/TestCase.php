<?php

namespace Tests;

use App\Role;
use App\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use JMac\Testing\Traits\AdditionalAssertions;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, AdditionalAssertions;

    /**
     * acting as admin
     * @return signed $user model
     */
    public function signin_as($role_title, $role_id)
    {
        $role = Role::factory()->create(['title'=>$role_title, 'id'=>$role_id]);

        $this->user = User::factory()->create(['role_id'=>$role_id]);
        $this->actingAs($this->user);

        return $this->user;
    }
}
