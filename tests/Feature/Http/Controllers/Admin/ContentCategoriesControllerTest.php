<?php

namespace Tests\Feature\Http\Controllers\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Admin\ContentCategoriesController
 */
class ContentCategoriesControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function create_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $user = \App\User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.content_categories.create'));

        $response->assertOk();
        $response->assertViewIs('admin.content_categories.create');

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function destroy_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $contentCategory = \App\ContentCategory::factory()->create();
        $user = \App\User::factory()->create();

        $response = $this->actingAs($user)->delete(route('admin.content_categories.destroy', [$content_category]));

        $response->assertRedirect(route('admin.content_categories.index'));
        $this->assertDeleted($admin);

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function edit_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $contentCategory = \App\ContentCategory::factory()->create();
        $user = \App\User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.content_categories.edit', [$content_category]));

        $response->assertOk();
        $response->assertViewIs('admin.content_categories.edit');
        $response->assertViewHas('content_category');

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function index_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $user = \App\User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.content_categories.index'));

        $response->assertOk();
        $response->assertViewIs('admin.content_categories.index');
        $response->assertViewHas('content_categories');

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function mass_destroy_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $user = \App\User::factory()->create();

        $response = $this->actingAs($user)->post(route('admin.content_categories.mass_destroy'), [
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

        $contentCategory = \App\ContentCategory::factory()->create();
        $user = \App\User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.content_categories.show', [$content_category]));

        $response->assertOk();
        $response->assertViewIs('admin.content_categories.show');
        $response->assertViewHas('content_category');
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

        $response = $this->actingAs($user)->post(route('admin.content_categories.store'), [
            // TODO: send request data
        ]);

        $response->assertRedirect(route('admin.content_categories.index'));

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function update_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $contentCategory = \App\ContentCategory::factory()->create();
        $user = \App\User::factory()->create();

        $response = $this->actingAs($user)->put(route('admin.content_categories.update', [$content_category]), [
            // TODO: send request data
        ]);

        $response->assertRedirect(route('admin.content_categories.index'));

        // TODO: perform additional assertions
    }

    // test cases...
}
