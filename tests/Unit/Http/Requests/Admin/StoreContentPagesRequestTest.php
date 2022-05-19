<?php

namespace Tests\Unit\Http\Requests\Admin;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @see \App\Http\Requests\Admin\StoreContentPagesRequest
 */
class StoreContentPagesRequestTest extends TestCase
{
    /** @var \App\Http\Requests\Admin\StoreContentPagesRequest */
    private $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = new \App\Http\Requests\Admin\StoreContentPagesRequest();
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
            'title' => 'required',
            'category_id.*' => 'exists:content_categories,id',
            'tag_id.*' => 'exists:content_tags,id',
            'featured_image' => 'nullable|mimes:png,jpg,jpeg,gif',
        ], $actual);
    }

    // test cases...
}
