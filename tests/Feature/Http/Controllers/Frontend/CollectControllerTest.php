<?php

namespace Tests\Feature\Http\Controllers\Frontend;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Frontend\CollectController
 */
class CollectControllerTest extends TestCase
{
 

    /**
     * @test
     */
    public function index_returns_an_ok_response()
    {


        $response = $this->get(route('frontend.index'));

        $response->assertSuccessful();

    }


}
