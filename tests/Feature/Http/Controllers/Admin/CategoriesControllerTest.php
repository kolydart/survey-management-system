<?php

namespace Tests\Feature\Http\Controllers\Admin;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Admin\CategoriesController
 */
class CategoriesControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function create_returns_an_ok_response()
    {


        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.categories.create'));

        $response->assertOk();
        $response->assertViewIs('admin.categories.create');


    }

    /**
     * @test
     */
    public function destroy_returns_an_ok_response()
    {


        $category = \App\Category::factory()->create();
        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->delete(route('admin.categories.destroy', [$category]));

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertSoftDeleted($category);


    }

    /**
     * @test
     */
    public function edit_returns_an_ok_response()
    {


        $category = \App\Category::factory()->create();
        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.categories.edit', [$category]));

        $response->assertOk();
        $response->assertViewIs('admin.categories.edit');
        $response->assertViewHas('category');


    }

    /**
     * @test
     */
    public function index_returns_an_ok_response()
    {


        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.categories.index'));

        $response->assertOk();
        $response->assertViewIs('admin.categories.index');
        $response->assertViewHas('categories');


    }

    /**
     * @test
     */
    public function mass_destroy_returns_an_ok_response()
    {


        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->post(route('admin.categories.mass_destroy'), [
            // TODO: send request data
        ]);

        $response->assertOk();


    }

    /**
     * @test
     */
    public function perma_del_returns_an_ok_response()
    {

        $category = \App\Category::factory()->create();

        $category->delete();
        
        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->delete(route('admin.categories.perma_del', ['id' => $category->id]));

        $response->assertRedirect(route('admin.categories.index'));


    }

    /**
     * @test
     */
    public function restore_returns_an_ok_response()
    {


        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');
        $category = \App\Category::factory()->create();
        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->post(route('admin.categories.restore', ['id' => $category->id]), [
            // TODO: send request data
        ]);

        $response->assertRedirect(route('admin.categories.index'));


    }

    /**
     * @test
     */
    public function show_returns_an_ok_response()
    {


        $category = \App\Category::factory()->create();
        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.categories.show', [$category]));

        $response->assertOk();
        $response->assertViewIs('admin.categories.show');
        $response->assertViewHas('category');
        $response->assertViewHas('surveys');


    }

    /**
     * @test
     */
    public function store_returns_an_ok_response()
    {


        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');
        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->post(route('admin.categories.store'), [
            // TODO: send request data
        ]);

        $response->assertRedirect(route('admin.categories.index'));


    }

    /**
     * @test
     */
    public function update_returns_an_ok_response()
    {

        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $category = \App\Category::factory()->create();
        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->put(route('admin.categories.update', [$category]), [
            // TODO: send request data
        ]);

        $response->assertRedirect(route('admin.categories.show', $id));


    }

    // test cases...
}
