<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Question;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Admin\QuestionsController
 */
class QuestionsControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function create_returns_an_ok_response()
    {


        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.questions.create'));

        $response->assertOk();
        $response->assertViewIs('admin.questions.create');
        $response->assertViewHas('answerlists');


    }

    /**
     * @test
     */
    public function destroy_returns_an_ok_response()
    {


        $question = \App\Question::factory()->create();
        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->delete(route('admin.questions.destroy', [$question]));

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.questions.index'));
        $this->assertSoftDeleted($question);


    }

    /**
     * @test
     */
    public function edit_returns_an_ok_response()
    {


        $question = \App\Question::factory()->create();
        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.questions.edit', [$question]));

        $response->assertOk();
        $response->assertViewIs('admin.questions.edit');
        $response->assertViewHas('question');
        $response->assertViewHas('answerlists');


    }

    /**
     * @test
     */
    public function index_returns_an_ok_response()
    {


        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.questions.index'));

        $response->assertOk();
        $response->assertViewIs('admin.questions.index');
        $response->assertViewHas('questions');


    }

    /**
     * @test
     */
    public function mass_destroy_returns_an_ok_response()
    {


        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->post(route('admin.questions.mass_destroy'), [

        ]);

        $response->assertOk();


    }

    /**
     * @test
     */
    public function perma_del_returns_an_ok_response()
    {

        $question = \App\Question::factory()->create();

        $question->delete();

        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->delete(route('admin.questions.perma_del', $question));

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.questions.index'));

    }

    /**
     * @test
     */
    public function restore_returns_an_ok_response()
    {

        $question = \App\Question::factory()->create();

        $question->delete();

        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->post(route('admin.questions.restore', ['id' => $question->id]), [

        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.questions.index'));


    }

    /**
     * @test
     */
    public function show_returns_an_ok_response()
    {


        $question = \App\Question::factory()->create();
        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.questions.show', [$question]));

        $response->assertOk();
        $response->assertViewIs('admin.questions.show');
        $response->assertViewHas('question');
        $response->assertViewHas('responses');
        $response->assertViewHas('items');


    }

    /**
     * @test
     */
    public function store_returns_an_ok_response()
    {

        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->post(route('admin.questions.store'), Question::factory()->make()->getAttributes());

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.questions.index'));


    }

    /**
     * @test
     */
    public function update_returns_an_ok_response()
    {
        $question = \App\Question::factory()->create();
        
        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->put(route('admin.questions.update', [$question]), $question->getAttributes());

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.questions.show', $question));


    }

    // test cases...
}
