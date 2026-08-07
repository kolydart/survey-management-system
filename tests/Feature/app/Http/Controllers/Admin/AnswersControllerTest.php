<?php

namespace Tests\Feature\app\Http\Controllers\Admin;

use App\Answer;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Admin\AnswersController
 */
class AnswersControllerTest extends TestCase
{
    use DatabaseTransactions;

    #[Test]
    public function create_returns_an_ok_response()
    {


        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.answers.create'));

        $response->assertOk();
        $response->assertViewIs('admin.answers.create');


    }

    #[Test]
    public function destroy_returns_an_ok_response()
    {


        $answer = \App\Answer::factory()->create();
        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->delete(route('admin.answers.destroy', [$answer]));

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.answers.index'));
        $this->assertSoftDeleted($answer);


    }

    #[Test]
    public function edit_returns_an_ok_response()
    {


        $answer = \App\Answer::factory()->create();
        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.answers.edit', [$answer]));

        $response->assertOk();
        $response->assertViewIs('admin.answers.edit');
        $response->assertViewHas('answer');


    }

    #[Test]
    public function index_returns_an_ok_response()
    {


        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.answers.index'));

        $response->assertOk();
        $response->assertViewIs('admin.answers.index');
        $response->assertViewHas('answers');


    }

    #[Test]
    public function mass_destroy_returns_an_ok_response()
    {

        $answer = \App\Answer::factory()->create();

        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->post(route('admin.answers.mass_destroy'), $answer->getAttributes());

        $response->assertSessionHasNoErrors();
        $response->assertOk();


    }

    #[Test]
    public function perma_del_returns_an_ok_response()
    {

        $answer = \App\Answer::factory()->create();

        $answer->delete();
        
        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->delete(route('admin.answers.perma_del', ['id' => $answer->id]));

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.answers.index'));


    }

    #[Test]
    public function restore_returns_an_ok_response()
    {

        $answer = \App\Answer::factory()->create();

        $answer->delete();

        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->post(route('admin.answers.restore', ['id' => $answer->id]), [

        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.answers.index'));


    }

    #[Test]
    public function show_returns_an_ok_response()
    {


        $answer = \App\Answer::factory()->create();
        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.answers.show', [$answer]));

        $response->assertOk();
        $response->assertViewIs('admin.answers.show');
        $response->assertViewHas('answer');
        $response->assertViewHas('responses');
        $response->assertViewHas('answerlists');


    }

    #[Test]
    public function store_returns_an_ok_response()
    {

        $user = $this->create_user('admin');

        $answer = Answer::factory()->make();
        $response = $this->actingAs($user)->post(route('admin.answers.store'), $answer->getAttributes());

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.answers.index'));


    }

    #[Test]
    public function update_returns_an_ok_response()
    {

        $answer = \App\Answer::factory()->create();
        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->put(route('admin.answers.update', [$answer]), $answer->getAttributes());

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.answers.show', $answer));


    }


}
