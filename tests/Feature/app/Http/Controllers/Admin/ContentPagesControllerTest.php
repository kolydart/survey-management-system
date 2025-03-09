<?php

namespace Tests\Feature\app\Http\Controllers\Admin;

use App\ContentPage;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Admin\ContentPagesController
 */
class ContentPagesControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function create_returns_an_ok_response()
    {


        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.content_pages.create'));

        $response->assertOk();
        $response->assertViewIs('admin.content_pages.create');
        $response->assertViewHas('category_ids');
        $response->assertViewHas('tag_ids');


    }

    /**
     * @test
     */
    public function destroy_returns_an_ok_response()
    {


        $contentPage = \App\ContentPage::factory()->create();
        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->delete(route('admin.content_pages.destroy', [$contentPage]));

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.content_pages.index'));
        $this->assertDatabaseMissing((new ContentPage)->getTable(),['id'=>$contentPage->id]);        


    }

    /**
     * @test
     */
    public function edit_returns_an_ok_response()
    {


        $contentPage = \App\ContentPage::factory()->create();
        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.content_pages.edit', [$contentPage]));

        $response->assertOk();
        $response->assertViewIs('admin.content_pages.edit');
        $response->assertViewHas('content_page');
        $response->assertViewHas('category_ids');
        $response->assertViewHas('tag_ids');


    }

    /**
     * @test
     */
    public function index_returns_an_ok_response()
    {


        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.content_pages.index'));

        $response->assertOk();
        $response->assertViewIs('admin.content_pages.index');
        $response->assertViewHas('content_pages');


    }

    /**
     * @test
     */
    public function mass_destroy_returns_an_ok_response()
    {

        $contentPage = ContentPage::factory()->create();
        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->post(route('admin.content_pages.mass_destroy'), $contentPage->getAttributes());

        $response->assertSessionHasNoErrors();
        $response->assertOk();


    }

    /**
     * @test
     */
    public function show_returns_an_ok_response()
    {


        $contentPage = \App\ContentPage::factory()->create();
        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.content_pages.show', [$contentPage]));

        $response->assertOk();
        $response->assertViewIs('admin.content_pages.show');
        $response->assertViewHas('content_page');


    }

    /**
     * @test
     */
    public function store_returns_an_ok_response()
    {

        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->post(route('admin.content_pages.store'), ContentPage::factory()->make()->getAttributes());

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.content_pages.index'));


    }

    /**
     * @test
     */
    public function update_returns_an_ok_response()
    {

        $contentPage = \App\ContentPage::factory()->create();

        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->put(route('admin.content_pages.update', [$contentPage]), $contentPage->getAttributes());

        $response->assertRedirect(route('admin.content_pages.index'));


    }

}
