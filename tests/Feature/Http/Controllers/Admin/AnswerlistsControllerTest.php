<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Answer;
use App\Answerlist;
use App\Question;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Admin\AnswerlistsController
 */
class AnswerlistsControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function create_returns_an_ok_response()
    {


        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.answerlists.create'));

        $response->assertOk();
        $response->assertViewIs('admin.answerlists.create');
        $response->assertViewHas('answers');
        $response->assertViewHas('hidden_answer');


    }

    /**
     * @test
     */
    public function destroy_returns_an_ok_response()
    {

        $answerlist = \App\Answerlist::factory()->create();

        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->delete(route('admin.answerlists.destroy', [$answerlist]));

        $response->assertSessionHasNoErrors();
        
        $response->assertRedirect(route('admin.answerlists.index'));

        $this->assertSoftDeleted($answerlist);


    }

    /**
     * @test
     */
    public function edit_returns_an_ok_response()
    {


        $answerlist = \App\Answerlist::factory()->create();
        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.answerlists.edit', [$answerlist]));

        $response->assertOk();
        $response->assertViewIs('admin.answerlists.edit');
        $response->assertViewHas('answerlist');
        $response->assertViewHas('answers');
        $response->assertViewHas('hidden_answer');


    }

    /**
     * @test
     */
    public function index_returns_an_ok_response()
    {


        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.answerlists.index'));

        $response->assertOk();
        $response->assertViewIs('admin.answerlists.index');
        $response->assertViewHas('answerlists');


    }

    /**
     * @test
     */
    public function mass_destroy_returns_an_ok_response()
    {


        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->post(route('admin.answerlists.mass_destroy'), [
            // TODO: send request data
        ]);

        $response->assertOk();


    }


    /**
     * @test
     */
    public function show_returns_an_ok_response()
    {

        $answerlist = \App\Answerlist::factory()->create();
        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.answerlists.show', [$answerlist]));

        $response->assertOk();
        $response->assertViewIs('admin.answerlists.show');
        $response->assertViewHas('answerlist');
        $response->assertViewHas('questions');


    }

    /**
     * @test
     */
    public function store_returns_an_ok_response()
    {

        $answers = Answer::factory()->count(3)->create();
        $answerlist = \App\Answerlist::factory()->make();

        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->post(route('admin.answerlists.store'), 
            $answerlist->getAttributes()+['answers' => $answers->pluck('id')->toArray()]
        );

        $response->assertSessionHasNoErrors();
        
        $response->assertRedirect(route('admin.answerlists.index'));


    }

    /**
     * @test
     */
    public function update_returns_an_ok_response()
    {

        $answerlist = \App\Answerlist::factory()
            ->has(Answer::factory()->count(3))
            ->create();

        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->put(route('admin.answerlists.update', $answerlist), 
            $answerlist->getAttributes() + ['answers' => $answerlist->answers->pluck('id')->toArray()]
        );

        $response->assertSessionHasNoErrors();
        
        $response->assertRedirect(route('admin.answerlists.index'));

    }

}
