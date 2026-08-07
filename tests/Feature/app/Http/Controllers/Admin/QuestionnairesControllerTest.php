<?php

namespace Tests\Feature\app\Http\Controllers\Admin;

use App\Answer;
use App\Answerlist;
use App\Item;
use App\Question;
use App\Questionnaire;
use App\Response;
use App\Survey;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Admin\QuestionnairesController
 */
class QuestionnairesControllerTest extends TestCase
{
    use DatabaseTransactions;

    #[Test]
    public function create_returns_an_ok_response()
    {


        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.questionnaires.create'));

        $response->assertOk();
        $response->assertViewIs('admin.questionnaires.create');
        $response->assertViewHas('surveys');


    }

    #[Test]
    public function destroy_returns_an_ok_response()
    {


        $questionnaire = \App\Questionnaire::factory()->create();
        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->delete(route('admin.questionnaires.destroy', [$questionnaire]));

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.questionnaires.index'));
        $this->assertSoftDeleted($questionnaire);


    }

    #[Test]
    public function edit_returns_an_ok_response()
    {


        $questionnaire = \App\Questionnaire::factory()->create();
        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.questionnaires.edit', [$questionnaire]));

        $response->assertOk();
        $response->assertViewIs('admin.questionnaires.edit');
        $response->assertViewHas('questionnaire');
        $response->assertViewHas('surveys');


    }

    #[Test]
    public function index_returns_an_ok_response()
    {


        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.questionnaires.index'));

        $response->assertOk();
        $response->assertViewIs('admin.questionnaires.index');
        // $response->assertViewHas('row');
        // $response->assertViewHas('gateKey');
        // $response->assertViewHas('routeKey');


    }

    #[Test]
    public function mass_destroy_returns_an_ok_response()
    {

        $questionnaire = Questionnaire::factory()->create();
        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->post(route('admin.questionnaires.mass_destroy'), $questionnaire->getAttributes());

        $response->assertSessionHasNoErrors();
        $response->assertOk();


    }

    #[Test]
    public function perma_del_returns_an_ok_response()
    {

        $questionnaire = \App\Questionnaire::factory()->create();

        $questionnaire->delete();
        
        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->delete(route('admin.questionnaires.perma_del', ['id' => $questionnaire->id]));

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.questionnaires.index'));


    }

    #[Test]
    public function restore_returns_an_ok_response()
    {

        $questionnaire = \App\Questionnaire::factory()->create();

        $questionnaire->delete();

        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->post(route('admin.questionnaires.restore', ['id' => $questionnaire->id]), [

        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.questionnaires.index'));


    }

    #[Test]
    public function show_returns_an_ok_response()
    {


        $questionnaire = \App\Questionnaire::factory()->create();
        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.questionnaires.show', [$questionnaire]));

        $response->assertOk();
        $response->assertViewIs('admin.questionnaires.show');
        $response->assertViewHas('survey');
        $response->assertViewHas('questionnaire');
        $response->assertViewHas('responses');


    }

    #[Test]
    public function store_returns_an_ok_response()
    {

        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->post(route('admin.questionnaires.store'), Questionnaire::factory()->make()->getAttributes());

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.questionnaires.index'));

    }

    #[Test]
    public function update_returns_an_ok_response()
    {

        $questionnaire = \App\Questionnaire::factory()->create();
        
        $user = $this->login_user('admin');

        $response = $this->put(route('admin.questionnaires.update',$questionnaire), $questionnaire->getAttributes());

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.questionnaires.show', $questionnaire->id));

    }

    /** 
     * this one took a long time to debug
     */
    #[Test]
    public function querionnaireShowRendersCorrectResponseContent(){

        $this->faker = \Faker\Factory::create();

        $survey = Survey::factory()->create();
        $answerlist = Answerlist::factory()->create(['type'=>'text']);
        $answer = Answer::factory()->create(['open'=>true]);
        $question = Question::factory()->create(['answerlist_id'=>$answerlist->id]);
        $item = Item::factory()->create([
            'survey_id' => $survey->id,
            'question_id' => $question->id,
            'label' => false,
        ]);
        $questionnaire1 = Questionnaire::factory()->create(['survey_id' => $survey->id]);
        $questionnaire2 = Questionnaire::factory()->create(['survey_id' => $survey->id]);
        $response1 = Response::factory()->create([
            'questionnaire_id'=>$questionnaire1, 
            'question_id'=>$item->question->id, 
            'answer_id'=>$answer->id, 
            'content'=>$this->faker->words(5,true),
        ]);
        $response2 = Response::factory()->create([
            'questionnaire_id'=>$questionnaire2, 
            'question_id'=>$item->question->id, 
            'answer_id'=>$answer->id, 
            'content'=>$this->faker->words(5,true),
        ]);

        $user = $this->login_user('admin');

        $response = $this->get(route('admin.questionnaires.show',$questionnaire2));
        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();

        $response->assertDontSee("$response1->content");
                
        // in render tab
        $response->assertSee("value=\"$response2->content\"",false);

        // in responses tab
        $response->assertSee("<td field-key='content'>$response2->content</td>",false);
        
    }

     
        #[Test]
        public function querionnaireFormRenders(){

        $this->faker = \Faker\Factory::create();

        $survey = Survey::factory()->create();
        $answerlist = Answerlist::factory()->create();
        $answer = Answer::factory()->create();
        $question = Question::factory()->create(['answerlist_id'=>$answerlist->id]);
        $item = Item::factory()->create([
            'survey_id' => $survey->id,
            'question_id' => $question->id,
            'label' => false,
        ]);
        $questionnaire = Questionnaire::factory()->create(['survey_id' => $survey->id]);

        $response = $this->get(route('frontend.create',$survey->alias));
        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();

        }    

}
