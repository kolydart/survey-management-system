<?php

namespace Tests\Unit\Http\Requests\Admin;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @see \App\Http\Requests\Admin\UpdateContentCategoriesRequest
 */
class UpdateContentCategoriesRequestTest extends TestCase
{
    /** @var \App\Http\Requests\Admin\UpdateContentCategoriesRequest */
    private $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = new \App\Http\Requests\Admin\UpdateContentCategoriesRequest();
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


        $actual = $this->subject->rules();

        $this->assertEquals([], $actual);
    }

    // test cases...
}
