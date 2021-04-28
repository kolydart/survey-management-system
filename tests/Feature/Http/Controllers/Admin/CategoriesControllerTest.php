<?php

namespace Tests\Feature\Http\Controllers\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Admin\CategoriesController
 */
class CategoriesControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function create_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $user = \App\User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.categories.create'));

        $response->assertOk();
        $response->assertViewIs('admin.categories.create');

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function destroy_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $category = \App\Category::factory()->create();
        $user = \App\User::factory()->create();

        $response = $this->actingAs($user)->delete(route('admin.categories.destroy', [$category]));

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDeleted($admin);

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function edit_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $category = \App\Category::factory()->create();
        $user = \App\User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.categories.edit', [$category]));

        $response->assertOk();
        $response->assertViewIs('admin.categories.edit');
        $response->assertViewHas('category');

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function index_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $user = \App\User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.categories.index'));

        $response->assertOk();
        $response->assertViewIs('admin.categories.index');
        $response->assertViewHas('categories');

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function mass_destroy_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $user = \App\User::factory()->create();

        $response = $this->actingAs($user)->post(route('admin.categories.mass_destroy'), [
            // TODO: send request data
        ]);

        $response->assertOk();

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function perma_del_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $category = \App\Category::factory()->create();
        $user = \App\User::factory()->create();

        $response = $this->actingAs($user)->delete(route('admin.categories.perma_del', ['id' => $category->id]));

        $response->assertRedirect(route('admin.categories.index'));

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function restore_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $category = \App\Category::factory()->create();
        $user = \App\User::factory()->create();

        $response = $this->actingAs($user)->post(route('admin.categories.restore', ['id' => $category->id]), [
            // TODO: send request data
        ]);

        $response->assertRedirect(route('admin.categories.index'));

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function show_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $category = \App\Category::factory()->create();
        $user = \App\User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.categories.show', [$category]));

        $response->assertOk();
        $response->assertViewIs('admin.categories.show');
        $response->assertViewHas('category');
        $response->assertViewHas('surveys');

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function store_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $user = \App\User::factory()->create();

        $response = $this->actingAs($user)->post(route('admin.categories.store'), [
            // TODO: send request data
        ]);

        $response->assertRedirect(route('admin.categories.index'));

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function update_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $category = \App\Category::factory()->create();
        $user = \App\User::factory()->create();

        $response = $this->actingAs($user)->put(route('admin.categories.update', [$category]), [
            // TODO: send request data
        ]);

        $response->assertRedirect(route('admin.categories.show', $id));

        // TODO: perform additional assertions
    }

    // test cases...
}