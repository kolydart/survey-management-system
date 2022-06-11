<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\ContentCategory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Admin\ContentCategoriesController
 */
class ContentCategoriesControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function create_returns_an_ok_response()
    {


        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.content_categories.create'));

        $response->assertOk();
        $response->assertViewIs('admin.content_categories.create');


    }

    /**
     * @test
     */
    public function destroy_returns_an_ok_response()
    {


        $contentCategory = \App\ContentCategory::factory()->create();
        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->delete(route('admin.content_categories.destroy', [$contentCategory]));

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.content_categories.index'));
        $this->assertDeleted($contentCategory);


    }

    /**
     * @test
     */
    public function edit_returns_an_ok_response()
    {


        $contentCategory = \App\ContentCategory::factory()->create();
        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.content_categories.edit', [$contentCategory]));

        $response->assertOk();
        $response->assertViewIs('admin.content_categories.edit');
        $response->assertViewHas('content_category');


    }

    /**
     * @test
     */
    public function index_returns_an_ok_response()
    {


        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.content_categories.index'));

        $response->assertOk();
        $response->assertViewIs('admin.content_categories.index');
        $response->assertViewHas('content_categories');


    }

    /**
     * @test
     */
    public function mass_destroy_returns_an_ok_response()
    {


        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->post(route('admin.content_categories.mass_destroy'), [

        ]);

        $response->assertOk();


    }

    /**
     * @test
     */
    public function show_returns_an_ok_response()
    {


        $contentCategory = \App\ContentCategory::factory()->create();
        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.content_categories.show', [$contentCategory]));

        $response->assertOk();
        $response->assertViewIs('admin.content_categories.show');
        $response->assertViewHas('content_category');
        $response->assertViewHas('content_pages');


    }

    /**
     * @test
     */
    public function store_returns_an_ok_response()
    {

        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->post(route('admin.content_categories.store'), ContentCategory::factory()->make()->getAttributes());

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.content_categories.index'));


    }

    /**
     * @test
     */
    public function update_returns_an_ok_response()
    {
        $contentCategory = \App\ContentCategory::factory()->create();

        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->put(route('admin.content_categories.update', [$contentCategory]), $contentCategory->getAttributes());

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.content_categories.index'));


    }

}
