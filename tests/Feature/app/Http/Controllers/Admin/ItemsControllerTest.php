<?php

namespace Tests\Feature\app\Http\Controllers\Admin;

use App\Item;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Admin\ItemsController
 */
class ItemsControllerTest extends TestCase
{
    use DatabaseTransactions;

    #[Test]
    public function create_returns_an_ok_response()
    {


        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.items.create'));

        $response->assertOk();
        $response->assertViewIs('admin.items.create');
        $response->assertViewHas('surveys');
        $response->assertViewHas('questions');


    }

    #[Test]
    public function destroy_returns_an_ok_response()
    {


        $item = \App\Item::factory()->create();
        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->delete(route('admin.items.destroy', [$item]));

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.items.index'));
        $this->assertSoftDeleted($item);


    }

    #[Test]
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

    #[Test]
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

    #[Test]
    public function index_ajax_returns_datatables_payload()
    {
        $item = Item::factory()->create();

        $user = $this->create_user('admin');

        // The controller serves the DataTables payload only for XHR requests.
        $response = $this->actingAs($user)->withHeaders([
            'X-Requested-With' => 'XMLHttpRequest',
        ])->getJson(route('admin.items.index', [
            'draw' => 1,
            'start' => 0,
            'length' => 10,
            'columns' => [
                ['data' => 'id', 'name' => 'id', 'searchable' => 'true', 'orderable' => 'true', 'search' => ['value' => '', 'regex' => 'false']],
                ['data' => 'order', 'name' => 'order', 'searchable' => 'true', 'orderable' => 'true', 'search' => ['value' => '', 'regex' => 'false']],
            ],
            'search' => ['value' => '', 'regex' => 'false'],
        ]));

        $response->assertOk();
        $response->assertJsonStructure(['draw', 'recordsTotal', 'recordsFiltered', 'data']);

        $payload = $response->json();

        $this->assertGreaterThan(0, $payload['recordsTotal']);
        $this->assertSame($payload['recordsTotal'], $payload['recordsFiltered']);
        $this->assertNotEmpty($payload['data']);
        $this->assertArrayHasKey('actions', $payload['data'][0]);
    }

    #[Test]
    public function mass_destroy_returns_an_ok_response()
    {
        $item = Item::factory()->create();

        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->post(route('admin.items.mass_destroy'), $item->getAttributes());

        $response->assertSessionHasNoErrors();
        $response->assertOk();


    }

    #[Test]
    public function perma_del_returns_an_ok_response()
    {

        $item = \App\Item::factory()->create();

        $item->delete();
        
        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->delete(route('admin.items.perma_del', ['id' => $item->id]));

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.items.index'));


    }

    #[Test]
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

    #[Test]
    public function show_returns_an_ok_response()
    {


        $item = \App\Item::factory()->create();
        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.items.show', [$item]));

        $response->assertOk();
        $response->assertViewIs('admin.items.show');
        $response->assertViewHas('item');


    }

    #[Test]
    public function store_returns_an_ok_response()
    {

        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->post(route('admin.items.store'), Item::factory()->make()->getAttributes());

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.items.index'));


    }

    #[Test]
    public function update_returns_an_ok_response()
    {
        $item = \App\Item::factory()->create();

        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->put(route('admin.items.update', [$item]), $item->getAttributes());

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.items.show', $item));


    }


}
