<?php

namespace Tests\Feature\app\Http\Controllers\Frontend;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\HomeController
 */
class HomeControllerTest extends TestCase
{


    use DatabaseTransactions;

    #[Test]
    public function landing_page_is_working(){
        $response = $this->get('/');
        $response->assertSuccessful();
        $response->assertSessionHasNoErrors();
    }

    #[Test]
    public function frontend_home_is_working(){
        $response = $this->get(route('frontend.index'));
        $response->assertSuccessful();
        $response->assertSessionHasNoErrors();
    }

    #[Test]
    public function frontend_home_redirects_admin(){
        $user = $this->login_user('admin');
        $response = $this->get(route('frontend.index'));

        $response->assertRedirect(route('admin.home'));
        $response->assertSessionHasNoErrors();
    }


}
