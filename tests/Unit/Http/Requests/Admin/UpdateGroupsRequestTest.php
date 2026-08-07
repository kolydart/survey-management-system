<?php

namespace Tests\Unit\Http\Requests\Admin;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Requests\Admin\UpdateGroupsRequest
 */
class UpdateGroupsRequestTest extends TestCase
{
    /** @var \App\Http\Requests\Admin\UpdateGroupsRequest */
    private $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = new \App\Http\Requests\Admin\UpdateGroupsRequest();
    }

    #[Test]
    public function authorize()
    {


        $actual = $this->subject->authorize();

        $this->assertTrue($actual);
    }

    #[Test]
    public function rules()
    {


        $actual = $this->subject->rules();

        $this->assertValidationRules([
            'title' => 'required',
            'surveys.*.title' => 'required',
        ], $actual);
    }

    // test cases...
}
