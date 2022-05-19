<?php

namespace Tests\Feature\Http\Controllers\Admin;

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
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $user = \App\User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.content_tags.create'));

        $response->assertOk();
        $response->assertViewIs('admin.content_tags.create');

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function destroy_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $contentTag = \App\ContentTag::factory()->create();
        $user = \App\User::factory()->create();

        $response = $this->actingAs($user)->delete(route('admin.content_tags.destroy', [$content_tag]));

        $response->assertRedirect(route('admin.content_tags.index'));
        $this->assertDeleted($admin);

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function edit_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $contentTag = \App\ContentTag::factory()->create();
        $user = \App\User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.content_tags.edit', [$content_tag]));

        $response->assertOk();
        $response->assertViewIs('admin.content_tags.edit');
        $response->assertViewHas('content_tag');

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function index_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $user = \App\User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.content_tags.index'));

        $response->assertOk();
        $response->assertViewIs('admin.content_tags.index');
        $response->assertViewHas('content_tags');

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function mass_destroy_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $user = \App\User::factory()->create();

        $response = $this->actingAs($user)->post(route('admin.content_tags.mass_destroy'), [
            // TODO: send request data
        ]);

        $response->assertOk();

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function show_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $contentTag = \App\ContentTag::factory()->create();
        $user = \App\User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.content_tags.show', [$content_tag]));

        $response->assertOk();
        $response->assertViewIs('admin.content_tags.show');
        $response->assertViewHas('content_tag');
        $response->assertViewHas('content_pages');

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function store_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $user = \App\User::factory()->create();

        $response = $this->actingAs($user)->post(route('admin.content_tags.store'), [
            // TODO: send request data
        ]);

        $response->assertRedirect(route('admin.content_tags.index'));

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function update_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $contentTag = \App\ContentTag::factory()->create();
        $user = \App\User::factory()->create();

        $response = $this->actingAs($user)->put(route('admin.content_tags.update', [$content_tag]), [
            // TODO: send request data
        ]);

        $response->assertRedirect(route('admin.content_tags.index'));

        // TODO: perform additional assertions
    }

    // test cases...
}
