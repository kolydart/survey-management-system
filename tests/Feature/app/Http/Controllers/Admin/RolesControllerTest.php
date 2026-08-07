<?php

namespace Tests\Feature\app\Http\Controllers\Admin;

use App\Role;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Admin\RolesController
 */
class RolesControllerTest extends TestCase
{
    use DatabaseTransactions;

    #[Test]
    public function create_returns_an_ok_response()
    {


        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.roles.create'));

        $response->assertOk();
        $response->assertViewIs('admin.roles.create');


    }

    #[Test]
    public function destroy_returns_an_ok_response()
    {


        $role = \App\Role::factory()->create();
        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->delete(route('admin.roles.destroy', [$role]));

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.roles.index'));
        $this->assertDatabaseMissing((new Role())->getTable(),['id'=>$role->id]);        

    }

    #[Test]
    public function edit_returns_an_ok_response()
    {


        $role = \App\Role::factory()->create();
        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.roles.edit', [$role]));

        $response->assertOk();
        $response->assertViewIs('admin.roles.edit');
        $response->assertViewHas('role');


    }

    #[Test]
    public function index_returns_an_ok_response()
    {


        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.roles.index'));

        $response->assertOk();
        $response->assertViewIs('admin.roles.index');
        $response->assertViewHas('roles');


    }

    #[Test]
    public function mass_destroy_returns_an_ok_response()
    {
        $role = Role::factory()->create();

        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->post(route('admin.roles.mass_destroy'), $role->getAttributes());

        $response->assertSessionHasNoErrors();
        $response->assertOk();


    }

    #[Test]
    public function show_returns_an_ok_response()
    {


        $role = \App\Role::factory()->create();
        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.roles.show', [$role]));

        $response->assertOk();
        $response->assertViewIs('admin.roles.show');
        $response->assertViewHas('role');
        $response->assertViewHas('users');


    }

    #[Test]
    public function store_returns_an_ok_response()
    {

        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->post(route('admin.roles.store'), Role::factory()->make()->getAttributes());

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.roles.index'));


    }

    #[Test]
    public function update_returns_an_ok_response()
    {

        $role = \App\Role::factory()->create();

        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->put(route('admin.roles.update', [$role]), $role->getAttributes());

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.roles.show', $role));


    }


}
