<?php

namespace Tests\Unit\Http\Requests\Admin;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @see \App\Http\Requests\Admin\UpdateCategoriesRequest
 */
class UpdateCategoriesRequestTest extends TestCase
{
    /** @var \App\Http\Requests\Admin\UpdateCategoriesRequest */
    private $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = new \App\Http\Requests\Admin\UpdateCategoriesRequest();
    }

    /**
     * @test
     */
    public function authorize()
    {


        $actual = $this->subject->authorize();

        $this->assertTrue($actual);
    }

    /**
     * @test
     */
    public function rules()
    {

        $this->markTestIncomplete();
        
        $actual = $this->subject->rules();

        $this->assertEquals([], $actual);
    }

    // test cases...
}
