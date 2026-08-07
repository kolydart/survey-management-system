<?php

namespace Tests\Unit\Http\Requests\Admin;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Requests\Admin\UpdateSurveysRequest
 */
class UpdateSurveysRequestTest extends TestCase
{
    /** @var \App\Http\Requests\Admin\UpdateSurveysRequest */
    private $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = new \App\Http\Requests\Admin\UpdateSurveysRequest();
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
            'category.*' => 'exists:categories,id',
            'group.*' => 'exists:groups,id',
            'access' => 'required',
        ], $actual);
    }

    // test cases...
}
