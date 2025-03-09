<?php

namespace Tests\Feature\app\Http\Controllers\Admin;

use App\ContentTag;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Admin\ContentTagsController
 */
class ContentTagsControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function create_returns_an_ok_response()
    {


        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.content_tags.create'));

        $response->assertOk();
        $response->assertViewIs('admin.content_tags.create');


    }

    /**
     * @test
     */
    public function destroy_returns_an_ok_response()
    {


        $contentTag = \App\ContentTag::factory()->create();
        $user = $this->create_user('admin');


        $response = $this->actingAs($user)->delete(route('admin.content_tags.destroy', [$contentTag]));

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.content_tags.index'));
        $this->assertDatabaseMissing((new ContentTag())->getTable(),['id'=>$contentTag->id]);        


    }

    /**
     * @test
     */
    public function edit_returns_an_ok_response()
    {


        $contentTag = \App\ContentTag::factory()->create();
        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.content_tags.edit', [$contentTag]));

        $response->assertOk();
        $response->assertViewIs('admin.content_tags.edit');
        // $response->assertViewHas('content_tag');


    }

    /**
     * @test
     */
    public function index_returns_an_ok_response()
    {


        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.content_tags.index'));

        $response->assertOk();
        $response->assertViewIs('admin.content_tags.index');
        $response->assertViewHas('content_tags');


    }

    /**
     * @test
     */
    public function mass_destroy_returns_an_ok_response()
    {

        $contentTag = ContentTag::factory()->create();
        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->post(route('admin.content_tags.mass_destroy'), $contentTag->getAttributes());

        $response->assertSessionHasNoErrors();
        $response->assertOk();


    }

    /**
     * @test
     */
    public function show_returns_an_ok_response()
    {


        $contentTag = \App\ContentTag::factory()->create();
        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.content_tags.show', [$contentTag]));

        $response->assertOk();
        $response->assertViewIs('admin.content_tags.show');
        // $response->assertViewHas('content_tag');
        // $response->assertViewHas('content_pages');


    }

    /**
     * @test
     */
    public function store_returns_an_ok_response()
    {

        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->post(route('admin.content_tags.store'), ContentTag::factory()->make()->getAttributes());

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.content_tags.index'));


    }

    /**
     * @test
     */
    public function update_returns_an_ok_response()
    {

        $contentTag = \App\ContentTag::factory()->create();

        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->put(route('admin.content_tags.update', [$contentTag]), $contentTag->getAttributes());

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.content_tags.index'));


    }


}
