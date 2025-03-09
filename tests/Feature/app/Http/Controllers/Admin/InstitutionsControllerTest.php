<?php

namespace Tests\Feature\app\Http\Controllers\Admin;

use App\Institution;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Admin\InstitutionsController
 */
class InstitutionsControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function create_returns_an_ok_response()
    {


        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.institutions.create'));

        $response->assertOk();
        $response->assertViewIs('admin.institutions.create');


    }

    /**
     * @test
     */
    public function destroy_returns_an_ok_response()
    {


        $institution = \App\Institution::factory()->create();
        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->delete(route('admin.institutions.destroy', [$institution]));

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.institutions.index'));
        $this->assertSoftDeleted($institution);


    }

    /**
     * @test
     */
    public function edit_returns_an_ok_response()
    {


        $institution = \App\Institution::factory()->create();
        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.institutions.edit', [$institution]));

        $response->assertOk();
        $response->assertViewIs('admin.institutions.edit');
        $response->assertViewHas('institution');


    }

    /**
     * @test
     */
    public function index_returns_an_ok_response()
    {


        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.institutions.index'));

        $response->assertOk();
        $response->assertViewIs('admin.institutions.index');
        $response->assertViewHas('institutions');


    }

    /**
     * @test
     */
    public function mass_destroy_returns_an_ok_response()
    {
        $institution = Institution::factory()->create();

        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->post(route('admin.institutions.mass_destroy'), $institution->getAttributes());

        $response->assertSessionHasNoErrors();
        $response->assertOk();


    }

    /**
     * @test
     */
    public function perma_del_returns_an_ok_response()
    {

        $institution = \App\Institution::factory()->create();

        $institution->delete();
        
        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->delete(route('admin.institutions.perma_del', ['id' => $institution->id]));

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.institutions.index'));


    }

    /**
     * @test
     */
    public function restore_returns_an_ok_response()
    {

        $institution = \App\Institution::factory()->create();

        $institution->delete();

        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->post(route('admin.institutions.restore', ['id' => $institution->id]), [

        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.institutions.index'));


    }

    /**
     * @test
     */
    public function show_returns_an_ok_response()
    {


        $institution = \App\Institution::factory()->create();
        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.institutions.show', [$institution]));

        $response->assertOk();
        $response->assertViewIs('admin.institutions.show');
        $response->assertViewHas('institution');
        $response->assertViewHas('surveys');


    }

    /**
     * @test
     */
    public function store_returns_an_ok_response()
    {

        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->post(route('admin.institutions.store'), Institution::factory()->make()->getAttributes());

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.institutions.index'));


    }

    /**
     * @test
     */
    public function update_returns_an_ok_response()
    {

        $institution = \App\Institution::factory()->create();

        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->put(route('admin.institutions.update', [$institution]), $institution->getAttributes());

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.institutions.show', $institution));


    }


}
