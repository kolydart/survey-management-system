<?php

namespace Tests\Unit\Http\Requests;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @see \App\Http\Requests\StoreQuestionnaire
 */
class StoreQuestionnaireTest extends TestCase
{
    /** @var \App\Http\Requests\StoreQuestionnaire */
    private $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = new \App\Http\Requests\StoreQuestionnaire();
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
            'survey_id' => 'numeric|required',
            '*_id*' => 'numeric|filled',
        ], $actual);
    }

    /**
     * @test
     */
    public function messages()
    {


        $actual = $this->subject->messages();

        $this->assertEquals([], $actual);
    }

    // test cases...
}
