<?php

namespace Tests\Unit\Http\Requests\Admin;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @see \App\Http\Requests\Admin\StoreActivitylogsRequest
 */
class StoreActivitylogsRequestTest extends TestCase
{
    /** @var \App\Http\Requests\Admin\StoreActivitylogsRequest */
    private $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = new \App\Http\Requests\Admin\StoreActivitylogsRequest();
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

        $this->assertValidationRules([
            'causer_id' => 'max:2147483647|nullable|numeric',
            'subject_id' => 'max:2147483647|nullable|numeric',
        ], $actual);
    }

    // test cases...
}
