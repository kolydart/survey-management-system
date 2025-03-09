<?php

namespace Tests\Feature\app\Http\Controllers\Admin;

use App\Loguseragent;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Admin\LoguseragentsController
 */
class LoguseragentsControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function destroy_returns_an_ok_response()
    {


        $loguseragent = \App\Loguseragent::factory()->create();
        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->delete(route('admin.loguseragents.destroy', [$loguseragent]));

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.loguseragents.index'));
        $this->assertDatabaseMissing((new Loguseragent())->getTable(),['id'=>$loguseragent->id]);        

    }

    /**
     * @test
     */
    public function edit_returns_an_ok_response()
    {


        $loguseragent = \App\Loguseragent::factory()->create();
        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.loguseragents.edit', [$loguseragent]));

        $response->assertOk();
        $response->assertViewIs('admin.loguseragents.edit');
        $response->assertViewHas('loguseragent');
        $response->assertViewHas('users');


    }

    /**
     * @test
     */
    public function index_returns_an_ok_response()
    {


        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.loguseragents.index'));

        $response->assertOk();
        $response->assertViewIs('admin.loguseragents.index');


    }

    /**
     * @test
     */
    public function mass_destroy_returns_an_ok_response()
    {

        $user = $this->create_user('admin');
        $loguseragent = Loguseragent::factory()->create();
        $response = $this->actingAs($user)->post(route('admin.loguseragents.mass_destroy'), $loguseragent->getAttributes());
        $response->assertSessionHasNoErrors();
        $response->assertOk();


    }

    /**
     * @test
     */
    public function show_returns_an_ok_response()
    {


        $loguseragent = \App\Loguseragent::factory()->create();
        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.loguseragents.show', [$loguseragent]));

        $response->assertOk();
        $response->assertViewIs('admin.loguseragents.show');
        $response->assertViewHas('loguseragent');


    }

    /**
     * @test
     */
    public function update_returns_an_ok_response()
    {

        $loguseragent = \App\Loguseragent::factory()->create();
        
        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->put(route('admin.loguseragents.update', [$loguseragent]), $loguseragent->getAttributes());


        $response->assertRedirect(route('admin.loguseragents.index'));


    }


}
