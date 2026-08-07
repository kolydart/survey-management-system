<?php

namespace Tests\Feature\app\Http\Controllers\Frontend;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Frontend\CollectController
 */
class CollectControllerTest extends TestCase
{
 

    #[Test]
    public function index_returns_an_ok_response()
    {


        $response = $this->get(route('frontend.index'));

        $response->assertSuccessful();

    }


}
