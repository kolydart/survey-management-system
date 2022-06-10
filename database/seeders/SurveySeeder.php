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
        
        $answerlists_count = 50;

        $answerlists = Answerlist::factory($answerlists_count)->create();
        
        foreach ($answerlists as $answerlist) {

            $answers_count = $this->faker->numberBetween(2,5);

            if($answerlist->type != 'text'){
                $answers = Answer::factory($answers_count)->create();
                $answerlist->answers()->attach($answers);
            }

            $questions_count = $this->faker->numberBetween(1,5);

            Question::factory($questions_count)->create(['answerlist_id'=>$answerlist->id]);

        }

        foreach ($surveys as $survey) {

            $items_count = $this->faker->numberBetween(10,20);

            for ($i=1; $i < $items_count+1; $i++) { 
                Item::factory()->create([
                    'survey_id' => $survey->id,
                    'question_id' => $i == 1 ? 
                        $this->faker->unique(true)->randomElement(Question::pluck('id')) : 
                        $this->faker->unique()->randomElement(Question::pluck('id')),
                    'order' => $i
                ]);
            }
        }
        
        $questionnaires_count = 100;

        for ($i=0; $i < $questionnaires_count; $i++) { 

            $survey = Survey::inRandomOrder()->first();

            $questionnaire = Questionnaire::factory()->create(['survey_id' => $survey->id]);

            foreach ($survey->items()->get() as $item) {

                if($this->faker->boolean($this->faker->numberBetween(95,100)) && $item->label == false){

                    // @todo fix error
                    $answer = $item->question->answerlist->answers()->inRandomOrder()->first();

                    if ($answer) {

                        $response = Response::factory()->create([
                            'questionnaire_id'=>$questionnaire->id, 
                            'question_id'=>$item->question->id, 
                            'answer_id'=>$answer->id, 
                            'content'=> ($answer->open || $item->question->type == 'text') ? $this->faker->words(5,true) : '' ,
                        ]);

                    }

                }

            }
            
        }

    }
}
