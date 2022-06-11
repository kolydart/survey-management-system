<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Item;
use App\Questionnaire;
use App\Survey;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Admin\SurveysController
 */
class SurveysControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function clone_returns_creates_an_exact_copy()
    {

        $survey = Survey::factory()->create(['completed' => true]);

        $this->assertDatabaseCount('surveys',1);

        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.surveys.clone', [$survey]));

        $new_survey = Survey::find($survey->id+1);
        
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseCount('surveys',2);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.surveys.show', $new_survey));

        $this->assertEquals($survey->title,$new_survey->title);
        $this->assertEquals($survey->alias,$new_survey->alias);
        $this->assertEquals($survey->institution_id,$new_survey->institution_id);
        $this->assertEquals($survey->introduction,$new_survey->introduction);
        $this->assertEquals($survey->javascript,$new_survey->javascript);
        $this->assertEquals($survey->notes,$new_survey->notes);
        $this->assertEquals($survey->inform,$new_survey->inform);
        $this->assertEquals($survey->access,$new_survey->access);
        $this->assertEquals(false,$new_survey->completed);

    }

    /**
     * @test
     */
    public function create_returns_an_ok_response()
    {


        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.surveys.create'));

        $response->assertOk();
        $response->assertViewIs('admin.surveys.create');
        $response->assertViewHas('institutions');
        $response->assertViewHas('categories');
        $response->assertViewHas('groups');


    }

    /**
     * @test
     */
    public function destroy_returns_an_ok_response()
    {


        $survey = \App\Survey::factory()->create();
        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->delete(route('admin.surveys.destroy', [$survey]));

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.surveys.index'));
        $this->assertSoftDeleted($survey);


    }

    /**
     * @test
     */
    public function edit_returns_an_ok_response()
    {


        $survey = \App\Survey::factory()->create();
        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.surveys.edit', [$survey]));

        $response->assertOk();
        $response->assertViewIs('admin.surveys.edit');
        $response->assertViewHas('survey');
        $response->assertViewHas('institutions');
        $response->assertViewHas('categories');
        $response->assertViewHas('groups');


    }

    /**
     * @test
     */
    public function index_returns_an_ok_response()
    {


        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.surveys.index'));

        $response->assertOk();
        $response->assertViewIs('admin.surveys.index');
        $response->assertViewHas('surveys');


    }

    /**
     * @test
     */
    public function mass_destroy_returns_an_ok_response()
    {

        $survey = Survey::factory()->create();
        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->post(route('admin.surveys.mass_destroy'), $survey->getAttributes());

        $response->assertSessionHasNoErrors();
        $response->assertOk();


    }

    /**
     * @test
     */
    public function perma_del_returns_an_ok_response()
    {

        $survey = \App\Survey::factory()->create();

        $survey->delete();
        
        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->delete(route('admin.surveys.perma_del', ['id' => $survey->id]));

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.surveys.index'));


    }

    /**
     * @test
     */
    public function restore_returns_an_ok_response()
    {

        $survey = \App\Survey::factory()->create();
        
        $survey->delete();

        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->post(route('admin.surveys.restore', ['id' => $survey->id]), [

        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.surveys.index'));


    }

    /**
     * @test
     */
    public function show_returns_an_ok_response()
    {


        $survey = \App\Survey::factory()->create();
        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.surveys.show', [$survey]));

        $response->assertOk();
        $response->assertViewIs('admin.surveys.show');
        $response->assertViewHas('survey');
        $response->assertViewHas('questionnaires');
        $response->assertViewHas('items');
        $response->assertViewHas('duplicates');


    }

    /**
     * @test
     */
    public function store_returns_an_ok_response()
    {

        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->post(route('admin.surveys.store'), Survey::factory()->make()->getAttributes());

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.surveys.index'));


    }

    /**
     * @test
     */
    public function update_returns_an_ok_response()
    {

        $survey = \App\Survey::factory()->create();

        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->put(route('admin.surveys.update', [$survey]), $survey->getAttributes());

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.surveys.show', $survey));


    }


}
