<?php

namespace Tests\Feature\app\Http\Controllers\Admin;

use App\Category;
use App\Group;
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
    use WithFaker;

    /**
     * @test
     */
    public function clone_creates_a_new_survey()
    {
        $survey = Survey::factory()->create();

        $this->assertDatabaseCount('surveys',1);

        $this->login_user('admin');

        $response = $this->get(route('admin.surveys.clone', $survey));

        $response->assertRedirect(route('admin.surveys.show',$survey->id+1));
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseCount('surveys',2);
        Survey::find($survey->id+1)->exists();

    }


    /**
     * @test
     */
    public function clone_has_completed_set_to_false()
    {
        $survey = Survey::factory()->create(['completed' => true]);

        $this->login_user('admin');

        $this->get(route('admin.surveys.clone', $survey));

        $new_survey = Survey::find($survey->id+1);
    
        $this->assertEquals(false,$new_survey->completed);

    }

    /**
     * @test
     */
    public function clone_creates_an_exact_copy()
    {

        $survey = Survey::factory()->create([
            'inform' => $this->faker->numberBetween(0,1),
            'completed' => true,
        ]);

        $this->login_user('admin');

        $this->get(route('admin.surveys.clone', $survey));

        $new_survey = Survey::find($survey->id+1);
        
        $ignoring = ['created_at','updated_at','completed','id'];

        $diff = collect($survey->getAttributes())
            ->forget($ignoring)
            ->diffAssoc(
                collect($new_survey->getAttributes())
                    ->forget($ignoring)
                )
            ;

        $this->assertTrue($diff->isEmpty(),$diff);

    }

    /**
     * @test
     */
    public function clone_has_the_same_relationships()
    {

        $survey = Survey::factory()
            ->has(Category::factory($this->faker()->numberBetween(1,3)))
            ->has(Group::factory($this->faker()->numberBetween(1,3)))
            ->has(Questionnaire::factory($this->faker()->numberBetween(1,3)))
            ->has(Item::factory($this->faker()->numberBetween(2,4)))
            ->create();

        $this->login_user('admin');

        $response = $this->get(route('admin.surveys.clone', $survey));

        $response->assertRedirect(route('admin.surveys.show',$survey->id+1));
        $response->assertSessionHasNoErrors();

        $new_survey = Survey::find($survey->id+1);

        $diff = $survey->category->pluck('title')
            ->diff($new_survey->category->pluck('title')
            );
        $this->assertTrue($diff->isEmpty(),$diff);

        $diff = $survey->group->pluck('title')
            ->diff($new_survey->group->pluck('title')
            );
        $this->assertTrue($diff->isEmpty(),$diff);

        $ignore=['id','created_at','updated_at','survey_id'];
        $survey->items
            ->each(function($item) use($new_survey,$ignore){

                $diff = 
                    collect($item->getAttributes())
                        ->forget($ignore)
                        ->diffAssoc(
                            collect(Item::where('survey_id', $new_survey->id)->where('question_id', $item->question_id)
                                ->first()
                                ->getAttributes())
                                    ->forget($ignore)
                            );

                $this->assertTrue($diff->isEmpty(),$diff);

            });

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
