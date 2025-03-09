<?php

namespace Tests\Feature\app\Http\Controllers\Admin;

use App\Group;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Admin\GroupsController
 */
class GroupsControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function create_returns_an_ok_response()
    {


        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.groups.create'));

        $response->assertOk();
        $response->assertViewIs('admin.groups.create');


    }

    /**
     * @test
     */
    public function destroy_returns_an_ok_response()
    {


        $group = \App\Group::factory()->create();
        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->delete(route('admin.groups.destroy', [$group]));

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.groups.index'));
        $this->assertSoftDeleted($group);


    }

    /**
     * @test
     */
    public function edit_returns_an_ok_response()
    {


        $group = \App\Group::factory()->create();
        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.groups.edit', [$group]));

        $response->assertOk();
        $response->assertViewIs('admin.groups.edit');
        $response->assertViewHas('group');


    }

    /**
     * @test
     */
    public function index_returns_an_ok_response()
    {


        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.groups.index'));

        $response->assertOk();
        $response->assertViewIs('admin.groups.index');
        $response->assertViewHas('groups');


    }

    /**
     * @test
     */
    public function mass_destroy_returns_an_ok_response()
    {

        $group = Group::factory()->create();
        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->post(route('admin.groups.mass_destroy'), $group->getAttributes());

        $response->assertSessionHasNoErrors();
        $response->assertOk();


    }

    /**
     * @test
     */
    public function perma_del_returns_an_ok_response()
    {

        $group = \App\Group::factory()->create();

        $group->delete();
        
        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->delete(route('admin.groups.perma_del', ['id' => $group->id]));

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.groups.index'));


    }

    /**
     * @test
     */
    public function restore_returns_an_ok_response()
    {

        $group = \App\Group::factory()->create();

        $group->delete();

        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->post(route('admin.groups.restore', ['id' => $group->id]), [

        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.groups.index'));


    }

    /**
     * @test
     */
    public function show_returns_an_ok_response()
    {


        $group = \App\Group::factory()->create();
        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.groups.show', [$group]));

        $response->assertOk();
        $response->assertViewIs('admin.groups.show');
        $response->assertViewHas('group');
        $response->assertViewHas('surveys');


    }

    /**
     * @test
     */
    public function store_returns_an_ok_response()
    {

        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->post(route('admin.groups.store'), Group::factory()->make()->getAttributes());

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.groups.index'));


    }

    /**
     * @test
     */
    public function update_returns_an_ok_response()
    {

        $group = \App\Group::factory()->create();
        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->put(route('admin.groups.update', [$group]), $group->getAttributes());

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.groups.show', $group));


    }


}
