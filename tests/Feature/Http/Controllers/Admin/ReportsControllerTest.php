<?php

namespace Tests\Feature\Http\Controllers\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Admin\ReportsController
 */
class ReportsControllerTest extends TestCase
{
    /**
     * @test
     */
    public function questionnaires_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $user = \App\User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.'));

        $response->assertOk();
        $response->assertViewIs('admin.reports');
        $response->assertViewHas('reportTitle');
        $response->assertViewHas('results');
        $response->assertViewHas('chartType');
        $response->assertViewHas('reportLabel');

        // TODO: perform additional assertions
    }

    // test cases...
}
