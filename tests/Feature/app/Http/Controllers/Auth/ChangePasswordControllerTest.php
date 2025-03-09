<?php

namespace Tests\Feature\app\Http\Controllers\Auth;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Auth\ChangePasswordController
 */
class ChangePasswordControllerTest extends TestCase
{
    /**
     * @test
     */
    public function change_password_returns_an_ok_response()
    {

        $user = $this->login_user('user',['password'=>'testpassword']);

        $response = $this->patch(route('auth.change_password'), [
            'current_password' => 'testpassword',
            'new_password' => 'newpassword',
            'new_password_confirmation' => 'newpassword',

        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();


    }

    /**
     * @test
     */
    public function show_change_password_form_returns_an_ok_response()
    {

        $user = $this->login_user('user');

        $response = $this->get(route('auth.change_password'));

        $response->assertOk();
        $response->assertViewIs('auth.change_password');
        $response->assertViewHas('user');

    }

}
