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

    /**
     * @test
     */
    public function show_with_graph_mode_returns_html_view()
    {
        $survey = Survey::factory()->create();
        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.surveys.show', [$survey]));

        $response->assertOk();
        $response->assertViewIs('admin.surveys.show');
        $response->assertViewHas('viewMode', 'graph');
    }

    /**
     * @test
     */
    public function show_with_text_mode_returns_html_view()
    {
        $survey = Survey::factory()->create();
        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.surveys.show', ['survey' => $survey, 'view' => 'text']));

        $response->assertOk();
        $response->assertViewIs('admin.surveys.show');
        $response->assertViewHas('viewMode', 'text');
    }

    /**
     * @test
     */
    public function show_with_rawdata_parameter_returns_text_mode()
    {
        $survey = Survey::factory()->create();
        $user = $this->create_user('admin');

        // Test backward compatibility with rawdata parameter
        $response = $this->actingAs($user)->get(route('admin.surveys.show', ['survey' => $survey, 'rawdata' => true]));

        $response->assertOk();
        $response->assertViewIs('admin.surveys.show');
        $response->assertViewHas('viewMode', 'text');
    }

    /**
     * @test
     */
    public function show_with_json_mode_returns_json_response()
    {
        $survey = Survey::factory()
            ->has(Questionnaire::factory()->count(2))
            ->create();
        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.surveys.show', ['survey' => $survey, 'view' => 'json']));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/json');
        $response->assertHeader('Content-Disposition', 'attachment; filename="survey_' . $survey->id . '_export.json"');

        $data = $response->json();
        $this->assertArrayHasKey('survey', $data);
        $this->assertArrayHasKey('questions', $data);
        $this->assertArrayHasKey('questionnaires', $data);
        $this->assertArrayHasKey('responses_summary', $data);

        $this->assertEquals($survey->id, $data['survey']['id']);
        $this->assertEquals($survey->title, $data['survey']['title']);
    }

    /**
     * @test
     */
    public function show_with_csv_mode_returns_csv_response()
    {
        $survey = Survey::factory()
            ->has(Questionnaire::factory()->count(2))
            ->create();
        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.surveys.show', ['survey' => $survey, 'view' => 'csv']));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        $response->assertHeader('Content-Disposition', 'attachment; filename="survey_' . $survey->id . '_export.csv"');

        // Check CSV has header row
        $content = $response->getContent();
        $this->assertStringContainsString('questionnaire_id', $content);
        $this->assertStringContainsString('question_id', $content);
        $this->assertStringContainsString('answer_id', $content);
    }

    /**
     * @test
     */
    public function json_export_includes_all_survey_data()
    {
        $category = Category::factory()->create();
        $group = Group::factory()->create();

        $survey = Survey::factory()
            ->create();

        $survey->category()->attach($category);
        $survey->group()->attach($group);

        $questionnaire = Questionnaire::factory()->create(['survey_id' => $survey->id]);
        $item = Item::factory()->create(['survey_id' => $survey->id]);

        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.surveys.show', ['survey' => $survey, 'view' => 'json']));

        $data = $response->json();

        // Verify survey metadata
        $this->assertEquals($survey->title, $data['survey']['title']);
        $this->assertEquals($survey->alias, $data['survey']['alias']);
        $this->assertContains($category->title, $data['survey']['categories']);
        $this->assertContains($group->title, $data['survey']['groups']);

        // Verify responses summary
        $this->assertEquals(1, $data['responses_summary']['total_questionnaires']);

        // Verify questionnaires
        $this->assertCount(1, $data['questionnaires']);
        $this->assertEquals($questionnaire->id, $data['questionnaires'][0]['id']);
    }

    /**
     * @test
     */
    public function export_requires_authentication()
    {
        $survey = Survey::factory()->create();

        // Test JSON export without authentication
        $response = $this->get(route('admin.surveys.show', ['survey' => $survey, 'view' => 'json']));
        $response->assertRedirect(route('login'));

        // Test CSV export without authentication
        $response = $this->get(route('admin.surveys.show', ['survey' => $survey, 'view' => 'csv']));
        $response->assertRedirect(route('login'));
    }

    /**
     * @test
     */
    public function show_with_json_results_mode_returns_aggregated_json()
    {
        $survey = Survey::factory()
            ->has(Questionnaire::factory()->count(5))
            ->create();

        $item = Item::factory()->create(['survey_id' => $survey->id]);

        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.surveys.show', ['survey' => $survey, 'view' => 'json-results']));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/json');
        $response->assertHeader('Content-Disposition', 'attachment; filename="survey_' . $survey->id . '_results.json"');

        $data = $response->json();

        // Should have survey metadata
        $this->assertArrayHasKey('survey', $data);
        $this->assertEquals($survey->id, $data['survey']['id']);
        $this->assertEquals(5, $data['survey']['total_responses']);

        // Should have questions array
        $this->assertArrayHasKey('questions', $data);

        // Should NOT have questionnaires array (this is the key difference)
        $this->assertArrayNotHasKey('questionnaires', $data);
    }

    /**
     * @test
     */
    public function json_results_includes_aggregated_answer_counts()
    {
        $survey = Survey::factory()->create();

        // Create questionnaires
        $q1 = Questionnaire::factory()->create(['survey_id' => $survey->id]);
        $q2 = Questionnaire::factory()->create(['survey_id' => $survey->id]);
        $q3 = Questionnaire::factory()->create(['survey_id' => $survey->id]);

        // Create a question with radio answers
        $question = \App\Question::factory()->create();
        $answerlist = \App\Answerlist::factory()->create(['type' => 'radio']);
        $question->answerlist_id = $answerlist->id;
        $question->save();

        $answer1 = \App\Answer::factory()->create();
        $answer2 = \App\Answer::factory()->create();
        $answerlist->answers()->attach([$answer1->id, $answer2->id]);

        $item = Item::factory()->create([
            'survey_id' => $survey->id,
            'question_id' => $question->id,
        ]);

        // Create responses: 2 for answer1, 1 for answer2
        \App\Response::factory()->create([
            'questionnaire_id' => $q1->id,
            'question_id' => $question->id,
            'answer_id' => $answer1->id,
        ]);
        \App\Response::factory()->create([
            'questionnaire_id' => $q2->id,
            'question_id' => $question->id,
            'answer_id' => $answer1->id,
        ]);
        \App\Response::factory()->create([
            'questionnaire_id' => $q3->id,
            'question_id' => $question->id,
            'answer_id' => $answer2->id,
        ]);

        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.surveys.show', ['survey' => $survey, 'view' => 'json-results']));

        $data = $response->json();

        // Find the question in results
        $questionResult = collect($data['questions'])->firstWhere('question_id', $question->id);

        $this->assertNotNull($questionResult);
        $this->assertEquals(3, $questionResult['total_responses']);

        // Check aggregated results
        $this->assertIsArray($questionResult['results']);

        // Find answer1 results
        $answer1Result = collect($questionResult['results'])->firstWhere('answer_id', $answer1->id);
        $this->assertEquals(2, $answer1Result['count']);
        $this->assertEquals(66.67, $answer1Result['percentage']); // 2/3 * 100

        // Find answer2 results
        $answer2Result = collect($questionResult['results'])->firstWhere('answer_id', $answer2->id);
        $this->assertEquals(1, $answer2Result['count']);
        $this->assertEquals(33.33, $answer2Result['percentage']); // 1/3 * 100
    }

    /**
     * @test
     */
    public function json_results_includes_statistics_for_numeric_questions()
    {
        $survey = Survey::factory()->create();

        $q1 = Questionnaire::factory()->create(['survey_id' => $survey->id]);
        $q2 = Questionnaire::factory()->create(['survey_id' => $survey->id]);
        $q3 = Questionnaire::factory()->create(['survey_id' => $survey->id]);

        // Create a numeric question
        $question = \App\Question::factory()->create();
        $answerlist = \App\Answerlist::factory()->create(['type' => 'number']);
        $question->answerlist_id = $answerlist->id;
        $question->save();

        $item = Item::factory()->create([
            'survey_id' => $survey->id,
            'question_id' => $question->id,
        ]);

        // Create numeric responses: 10, 20, 30
        \App\Response::factory()->create([
            'questionnaire_id' => $q1->id,
            'question_id' => $question->id,
            'content' => '10',
        ]);
        \App\Response::factory()->create([
            'questionnaire_id' => $q2->id,
            'question_id' => $question->id,
            'content' => '20',
        ]);
        \App\Response::factory()->create([
            'questionnaire_id' => $q3->id,
            'question_id' => $question->id,
            'content' => '30',
        ]);

        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.surveys.show', ['survey' => $survey, 'view' => 'json-results']));

        $data = $response->json();

        // Find the question in results
        $questionResult = collect($data['questions'])->firstWhere('question_id', $question->id);

        $this->assertNotNull($questionResult);

        // Check statistics
        $this->assertArrayHasKey('statistics', $questionResult['results']);
        $stats = $questionResult['results']['statistics'];

        $this->assertEquals(10, $stats['min']);
        $this->assertEquals(30, $stats['max']);
        $this->assertEquals(20, $stats['mean']); // (10+20+30)/3
        $this->assertEquals(20, $stats['median']);
        $this->assertEquals(3, $stats['count']);
    }

    /**
     * @test
     */
    public function show_with_csv_results_mode_returns_aggregated_csv()
    {
        $survey = Survey::factory()
            ->has(Questionnaire::factory()->count(5))
            ->create();

        $item = Item::factory()->create(['survey_id' => $survey->id]);

        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.surveys.show', ['survey' => $survey, 'view' => 'csv-results']));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        $response->assertHeader('Content-Disposition', 'attachment; filename="survey_' . $survey->id . '_results.csv"');

        // Check CSV has header row
        $content = $response->getContent();
        $this->assertStringContainsString('question_id', $content);
        $this->assertStringContainsString('question_order', $content);
        $this->assertStringContainsString('question_title', $content);
        $this->assertStringContainsString('count', $content);
        $this->assertStringContainsString('percentage', $content);
    }

    /**
     * @test
     */
    public function csv_results_includes_aggregated_answer_counts()
    {
        $survey = Survey::factory()->create();

        // Create questionnaires
        $q1 = Questionnaire::factory()->create(['survey_id' => $survey->id]);
        $q2 = Questionnaire::factory()->create(['survey_id' => $survey->id]);
        $q3 = Questionnaire::factory()->create(['survey_id' => $survey->id]);

        // Create a question with radio answers
        $question = \App\Question::factory()->create();
        $answerlist = \App\Answerlist::factory()->create(['type' => 'radio']);
        $question->answerlist_id = $answerlist->id;
        $question->save();

        $answer1 = \App\Answer::factory()->create(['title' => 'Answer One']);
        $answer2 = \App\Answer::factory()->create(['title' => 'Answer Two']);
        $answerlist->answers()->attach([$answer1->id, $answer2->id]);

        $item = Item::factory()->create([
            'survey_id' => $survey->id,
            'question_id' => $question->id,
            'order' => '1',
        ]);

        // Create responses: 2 for answer1, 1 for answer2
        \App\Response::factory()->create([
            'questionnaire_id' => $q1->id,
            'question_id' => $question->id,
            'answer_id' => $answer1->id,
        ]);
        \App\Response::factory()->create([
            'questionnaire_id' => $q2->id,
            'question_id' => $question->id,
            'answer_id' => $answer1->id,
        ]);
        \App\Response::factory()->create([
            'questionnaire_id' => $q3->id,
            'question_id' => $question->id,
            'answer_id' => $answer2->id,
        ]);

        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.surveys.show', ['survey' => $survey, 'view' => 'csv-results']));

        $content = $response->getContent();

        // Should contain answer titles
        $this->assertStringContainsString('Answer One', $content);
        $this->assertStringContainsString('Answer Two', $content);

        // Parse CSV to check counts and percentages
        $rows = str_getcsv($content, "\n");
        $this->assertGreaterThanOrEqual(3, count($rows)); // Header + 2 answer rows

        // Check that percentages are present (66.67 for answer1, 33.33 for answer2)
        $this->assertStringContainsString('66.67', $content);
        $this->assertStringContainsString('33.33', $content);
    }

    /**
     * @test
     */
    public function csv_results_includes_statistics_for_numeric_questions()
    {
        $survey = Survey::factory()->create();

        $q1 = Questionnaire::factory()->create(['survey_id' => $survey->id]);
        $q2 = Questionnaire::factory()->create(['survey_id' => $survey->id]);
        $q3 = Questionnaire::factory()->create(['survey_id' => $survey->id]);

        // Create a numeric question
        $question = \App\Question::factory()->create(['title' => 'Rate 1-10']);
        $answerlist = \App\Answerlist::factory()->create(['type' => 'number']);
        $question->answerlist_id = $answerlist->id;
        $question->save();

        $item = Item::factory()->create([
            'survey_id' => $survey->id,
            'question_id' => $question->id,
        ]);

        // Create numeric responses: 10, 20, 30
        \App\Response::factory()->create([
            'questionnaire_id' => $q1->id,
            'question_id' => $question->id,
            'content' => '10',
        ]);
        \App\Response::factory()->create([
            'questionnaire_id' => $q2->id,
            'question_id' => $question->id,
            'content' => '20',
        ]);
        \App\Response::factory()->create([
            'questionnaire_id' => $q3->id,
            'question_id' => $question->id,
            'content' => '30',
        ]);

        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.surveys.show', ['survey' => $survey, 'view' => 'csv-results']));

        $content = $response->getContent();

        // Should contain question title
        $this->assertStringContainsString('Rate 1-10', $content);

        // Should contain statistics columns: min, max, mean, median
        // Parse CSV to verify statistics
        $this->assertStringContainsString('10', $content); // min
        $this->assertStringContainsString('30', $content); // max
        $this->assertStringContainsString('20', $content); // mean and median
    }

    /**
     * @test
     */
    public function csv_results_format_is_suitable_for_pivot_tables()
    {
        $survey = Survey::factory()->create();

        $q1 = Questionnaire::factory()->create(['survey_id' => $survey->id]);

        // Create question with answerlist
        $answerlist1 = \App\Answerlist::factory()->create(['type' => 'radio']);
        $question1 = \App\Question::factory()->create([
            'title' => 'Question 1',
            'answerlist_id' => $answerlist1->id
        ]);

        $answer1 = \App\Answer::factory()->create(['title' => 'Yes']);
        $answerlist1->answers()->attach($answer1->id);

        $item1 = Item::factory()->create([
            'survey_id' => $survey->id,
            'question_id' => $question1->id,
            'order' => '1',
        ]);

        \App\Response::factory()->create([
            'questionnaire_id' => $q1->id,
            'question_id' => $question1->id,
            'answer_id' => $answer1->id,
        ]);

        $user = $this->create_user('admin');

        $response = $this->actingAs($user)->get(route('admin.surveys.show', ['survey' => $survey, 'view' => 'csv-results']));

        $content = $response->getContent();
        $rows = array_map('str_getcsv', explode("\n", trim($content)));

        // Verify structure suitable for pivot tables
        $this->assertGreaterThanOrEqual(2, count($rows)); // Header + data rows

        $headers = $rows[0];

        // Check all expected columns are present
        $this->assertContains('question_id', $headers);
        $this->assertContains('question_order', $headers);
        $this->assertContains('question_title', $headers);
        $this->assertContains('question_type', $headers);
        $this->assertContains('answer_id', $headers);
        $this->assertContains('answer_title', $headers);
        $this->assertContains('count', $headers);
        $this->assertContains('percentage', $headers);
        $this->assertContains('total_responses', $headers);
        $this->assertContains('min', $headers);
        $this->assertContains('max', $headers);
        $this->assertContains('mean', $headers);
        $this->assertContains('median', $headers);
    }


}
