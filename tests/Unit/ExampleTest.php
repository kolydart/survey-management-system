<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
        $user = $this->signin_as('admin',1);
        $this->assertTrue($user->can('survey_access'));

    }
}
