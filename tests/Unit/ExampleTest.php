<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $user = $this->login_user('Admin');
        die(\gateweb\common\Presenter::dd($user->getAttributes()));
        $this->assertTrue($user->can('survey_access'));
    }
}
