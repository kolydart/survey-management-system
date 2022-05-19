<?php

namespace Tests\Unit\Http\Requests\Admin;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @see \App\Http\Requests\Admin\UpdateContentPagesRequest
 */
class UpdateContentPagesRequestTest extends TestCase
{
    /** @var \App\Http\Requests\Admin\UpdateContentPagesRequest */
    private $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = new \App\Http\Requests\Admin\UpdateContentPagesRequest();
    }

    /**
     * @test
     */
    public function authorize()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $actual = $this->subject->authorize();

        $this->assertTrue($actual);
    }

    /**
     * @test
     */
    public function rules()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $actual = $this->subject->rules();

        $this->assertValidationRules([
            'title' => 'required',
            'category_id.*' => 'exists:content_categories,id',
            'tag_id.*' => 'exists:content_tags,id',
            'featured_image' => 'nullable|mimes:png,jpg,jpeg,gif',
        ], $actual);
    }

    // test cases...
}
