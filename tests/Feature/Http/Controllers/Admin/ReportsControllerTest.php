<?php

namespace Tests\Feature\Http\Controllers\Admin;

use Illuminate\Foundation\Testing\DatabaseTransactions;
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


        $user = $this->create_user('admin');

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
