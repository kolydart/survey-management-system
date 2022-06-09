<?php

namespace Database\Seeders;

use App\Answer;
use App\Answerlist;
use App\Item;
use App\Question;
use App\Questionnaire;
use App\Response;
use App\Survey;
use Illuminate\Database\Seeder;

class SurveySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $this->faker = \Faker\Factory::create();

        $surveys = Survey::factory(10)->create();
        
        $answerlists = Answerlist::factory(15)->create();
        
        foreach ($answerlists as $answerlist) {

            $answers_count = $this->faker->numberBetween(2,5);

            if($answerlist->type != 'text'){
                $answers = Answer::factory($answers_count)->create();
                $answerlist->answers()->attach($answers);
            }

            $questions_count = $this->faker->numberBetween(1,10);

            Question::factory($questions_count)->create(['answerlist_id'=>$answerlist->id]);

        }
        
        $questionnaires = Questionnaire::factory(150)->create(['survey_id' =>$this->faker->randomElement($surveys)->id]);
        
        // foreach ($surveys as $survey) {

            // $items = Item::factory(15)->create([
            //     'survey_id' => $this->faker->randomElement($surveys)->id,
            //     'question_id' => $this->faker->randomElement($questions)->id,
            // ]);


            // $response = Response::factory()->create([
            //     'questionnaire_id'=>$questionnaire, 
            //     'question_id'=>$item->question->id, 
            //     'answer_id'=>$answer->id, 
            //     'content'=>$this->faker->words(5,true),
            // ]);

        // }

 
    }
}
