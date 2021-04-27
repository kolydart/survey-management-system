<?php

namespace Tests\Feature\frontend;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class landing_pageTest extends TestCase
{
    /**
     * @test
     */
    public function page_exists()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function page_contains_survey_word()
    {
        $response = $this->get('/');
        $response->assertSee('survey');
    }
}
