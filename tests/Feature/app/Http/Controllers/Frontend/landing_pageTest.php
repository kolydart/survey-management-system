<?php

namespace Tests\Feature\frontend;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class landing_pageTest extends TestCase
{
    #[Test]
    public function page_exists()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    #[Test]
    public function page_contains_survey_word()
    {
        $response = $this->get('/');
        $response->assertSee('survey');
    }
}
