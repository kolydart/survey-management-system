<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Admin\UsersController
 */
class UsersControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function create_returns_an_ok_response()
    {


        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.users.create'));

        $response->assertOk();
        $response->assertViewIs('admin.users.create');
        $response->assertViewHas('roles');


    }

    /**
     * @test
     */
    public function destroy_returns_an_ok_response()
    {


        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->delete(route('admin.users.destroy', [$user]));

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDeleted($user);


    }

    /**
     * @test
     */
    public function edit_returns_an_ok_response()
    {


        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.users.edit', [$user]));

        $response->assertOk();
        $response->assertViewIs('admin.users.edit');
        $response->assertViewHas('user');
        $response->assertViewHas('roles');


    }

    /**
     * @test
     */
    public function index_returns_an_ok_response()
    {


        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.users.index'));

        $response->assertOk();
        $response->assertViewIs('admin.users.index');
        $response->assertViewHas('users');


    }

    /**
     * @test
     */
    public function mass_destroy_returns_an_ok_response()
    {

        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->post(route('admin.users.mass_destroy'), $user->getAttributes());

        $response->assertSessionHasNoErrors();
        $response->assertOk();


    }

    /**
     * @test
     */
    public function show_returns_an_ok_response()
    {


        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.users.show', [$user]));

        $response->assertOk();
        $response->assertViewIs('admin.users.show');
        $response->assertViewHas('user');
        $response->assertViewHas('loguseragents');


    }

    /**
     * @test
     */
    public function store_returns_an_ok_response()
    {

        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->post(route('admin.users.store'), User::factory()->make()->getAttributes());

        $response->assertRedirect(route('admin.users.index'));


    }

    /**
     * @test
     */
    public function update_returns_an_ok_response()
    {

        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->put(route('admin.users.update', [$user]), $user->getAttributes());

        $response->assertRedirect(route('admin.users.show', $user));


    }


}
