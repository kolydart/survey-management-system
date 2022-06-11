<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Item;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Admin\ItemsController
 */
class ItemsControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function create_returns_an_ok_response()
    {


        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.items.create'));

        $response->assertOk();
        $response->assertViewIs('admin.items.create');
        $response->assertViewHas('surveys');
        $response->assertViewHas('questions');


    }

    /**
     * @test
     */
    public function destroy_returns_an_ok_response()
    {


        $item = \App\Item::factory()->create();
        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->delete(route('admin.items.destroy', [$item]));

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.items.index'));
        $this->assertSoftDeleted($item);


    }

    /**
     * @test
     */
    public function edit_returns_an_ok_response()
    {


        $item = \App\Item::factory()->create();
        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.items.edit', [$item]));

        $response->assertOk();
        $response->assertViewIs('admin.items.edit');
        $response->assertViewHas('item');
        $response->assertViewHas('surveys');
        $response->assertViewHas('questions');


    }

    /**
     * @test
     */
    public function index_returns_an_ok_response()
    {


        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.items.index'));

        $response->assertOk();
        $response->assertViewIs('admin.items.index');
        // $response->assertViewHas('row');
        // $response->assertViewHas('gateKey');
        // $response->assertViewHas('routeKey');


    }

    /**
     * @test
     */
    public function mass_destroy_returns_an_ok_response()
    {


        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->post(route('admin.items.mass_destroy'), [

        ]);

        $response->assertOk();


    }

    /**
     * @test
     */
    public function perma_del_returns_an_ok_response()
    {

        $item = \App\Item::factory()->create();

        $item->delete();
        
        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->delete(route('admin.items.perma_del', ['id' => $item->id]));

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.items.index'));


    }

    /**
     * @test
     */
    public function restore_returns_an_ok_response()
    {

        $item = \App\Item::factory()->create();

        $item->delete();

        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->post(route('admin.items.restore', ['id' => $item->id]), [

        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.items.index'));


    }

    /**
     * @test
     */
    public function show_returns_an_ok_response()
    {


        $item = \App\Item::factory()->create();
        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.items.show', [$item]));

        $response->assertOk();
        $response->assertViewIs('admin.items.show');
        $response->assertViewHas('item');


    }

    /**
     * @test
     */
    public function store_returns_an_ok_response()
    {

        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->post(route('admin.items.store'), Item::factory()->make()->getAttributes());

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.items.index'));


    }

    /**
     * @test
     */
    public function update_returns_an_ok_response()
    {
        $item = \App\Item::factory()->create();

        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->put(route('admin.items.update', [$item]), $item->getAttributes());

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.items.show', $item));


    }

    // test cases...
}
