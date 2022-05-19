<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\HomeController
 */
class HomeControllerTest extends TestCase
{


    /** 
     * @test
     */
    public function landing_page_is_working(){
        $response = $this->get('/');
        $response->assertSuccessful();
        $response->assertSessionHasNoErrors();
    }

    /** 
     * @test
     */
    public function frontend_home_is_working(){
        $response = $this->get(route('frontend.home'));
        $response->assertSuccessful();
        $response->assertSessionHasNoErrors();
    }

    /** 
     * @test
     */
    public function frontend_home_redirects_admin(){
        $user = $this->login_user('admin');
        $response = $this->get(route('frontend.home'));

        $response->assertRedirect(route('admin.home'));
        $response->assertSessionHasNoErrors();
    }


}
