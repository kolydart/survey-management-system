<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Category;
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

        $response->assertSessionHasNoErrors();
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

        $category = Category::factory()->create();
        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->post(route('admin.categories.mass_destroy'), $category->getAttributes());

        $response->assertSessionHasNoErrors();
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

        $category = \App\Category::factory()->create();

        $category->delete();

        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->post(route('admin.categories.restore', ['id' => $category->id]), [

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

        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->post(route('admin.categories.store'), Category::factory()->make()->getAttributes());

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.categories.index'));


    }

    /**
     * @test
     */
    public function update_returns_an_ok_response()
    {
        $category = \App\Category::factory()->create();

        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->put(route('admin.categories.update', [$category]), $category->getAttributes());

        $response->assertRedirect(route('admin.categories.show', $category));


    }


}
