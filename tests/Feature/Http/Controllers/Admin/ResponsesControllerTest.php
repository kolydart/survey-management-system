<?php

namespace Tests\Feature\Http\Controllers\Admin;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use App\Response;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Admin\ResponsesController
 */
class ResponsesControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function create_returns_an_ok_response()
    {


        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.responses.create'));

        $response->assertOk();
        $response->assertViewIs('admin.responses.create');
        $response->assertViewHas('questionnaires');
        $response->assertViewHas('questions');
        $response->assertViewHas('answers');


    }

    /**
     * @test
     */
    public function destroy_returns_an_ok_response()
    {


        $instance = \App\Response::factory()->create();
        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->delete(route('admin.responses.destroy', [$instance]));

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.responses.index'));
        $this->assertSoftDeleted($instance);


    }

    /**
     * @test
     */
    public function edit_returns_an_ok_response()
    {


        $instance = \App\Response::factory()->create();
        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.responses.edit', [$instance]));

        $response->assertOk();
        $response->assertViewIs('admin.responses.edit');
        $response->assertViewHas('response');
        $response->assertViewHas('questionnaires');
        $response->assertViewHas('questions');
        $response->assertViewHas('answers');


    }

    /**
     * @test
     */
    public function index_returns_an_ok_response()
    {


        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.responses.index'));

        $response->assertOk();
        $response->assertViewIs('admin.responses.index');
        // $response->assertViewHas('row');
        // $response->assertViewHas('gateKey');
        // $response->assertViewHas('routeKey');


    }

    /**
     * @test
     */
    public function index_content_returns_an_ok_response()
    {


        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.responses.index.content'));

        $response->assertOk();
        $response->assertViewIs('admin.responses.index.content');
        $response->assertViewHas('responses');


    }

    /**
     * @test
     */
    public function mass_destroy_returns_an_ok_response()
    {
        $resp = Response::factory()->create();

        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->post(route('admin.responses.mass_destroy'), $resp->getAttributes());

        $response->assertSessionHasNoErrors();
        $response->assertOk();


    }

    /**
     * @test
     */
    public function perma_del_returns_an_ok_response()
    {

        $instance = \App\Response::factory()->create();

        $instance->delete();
        
        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->delete(route('admin.responses.perma_del', ['id' => $instance->id]));

        $response->assertRedirect(route('admin.responses.index'));


    }

    /**
     * @test
     */
    public function restore_returns_an_ok_response()
    {

        $instance = \App\Response::factory()->create();

        $instance->delete();

        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->post(route('admin.responses.restore', ['id' => $instance->id]), [

        ]);

        $response->assertRedirect(route('admin.responses.index'));


    }

    /**
     * @test
     */
    public function show_returns_an_ok_response()
    {

        $instance = \App\Response::factory()->create();

        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.responses.show', $instance));

        $response->assertOk();
        $response->assertViewIs('admin.responses.show');
        // $response->assertViewHas('response');


    }

    /**
     * @test
     */
    public function store_returns_an_ok_response()
    {

        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->post(route('admin.responses.store'), Response::factory()->make()->getAttributes());

        $response->assertRedirect(route('admin.responses.index'));


    }

    /**
     * @test
     */
    public function update_returns_an_ok_response()
    {
        $resp = \App\Response::factory()->create();

        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->put(route('admin.responses.update', [$resp]), $resp->getAttributes());

        $response->assertRedirect(route('admin.responses.show', $resp));


    }


}
