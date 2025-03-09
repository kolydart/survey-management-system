<?php

namespace Tests\Feature\app\Http\Controllers\Admin;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\HomeController
 */
class HomeControllerTest extends TestCase
{


    use DatabaseTransactions;

    /** 
     * @test
     */
    public function admin_has_access_privileges(){
        $user = $this->login_user('Admin');
        $this->assertTrue($user->can('survey_access'));
        
    }

    /**
     * @test
     */
    public function index_allowed_to_admin()
    {
        $user = $this->login_user('admin');
        $response = $this->get(route('admin.home'));

        $response->assertOk();
        $response->assertViewIs('home');
        $response->assertViewHas('responses');
        $response->assertViewHas('questionnaires');
    }

    /**
     * @test
     */
    public function index_not_allowed_to_guest()
    {
        $response = $this->get(route('admin.home'));

        $response->assertRedirect('/login');
        $response->assertSessionHasNoErrors();
    }







}
