<?php

namespace Tests\Unit\Http\Requests\Admin;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Requests\Admin\StoreItemsRequest
 */
class StoreItemsRequestTest extends TestCase
{
    /** @var \App\Http\Requests\Admin\StoreItemsRequest */
    private $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = new \App\Http\Requests\Admin\StoreItemsRequest();
    }

    #[Test]
    public function authorize()
    {


        $actual = $this->subject->authorize();

        $this->assertTrue($actual);
    }

    // /**
    //  * @test
    //  */
    // public function rules()
    // {

    //     $actual = $this->subject->rules();

    //     $this->assertEquals([], $actual);
    // }

}
