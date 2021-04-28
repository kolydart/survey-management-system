<?php

namespace Database\Seeders;

use App\Answer;
use App\Answerlist;
use App\Item;
use App\Question;
use App\Survey;
use Illuminate\Database\Seeder;

class answerlistTypesSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $survey = Survey::factory()->create(['title'=>'test-survey']);

        $types = ['radio', 'checkbox', 'text', 'number', 'range', 'color', 'date', 'time', 'datetime-local', 'email', 'url', 'week', 'month', 'password', 'tel'];

        foreach ($types as $type) {
            $answerlist = Answerlist::create(['type'=>$type, 'title'=> $type.'-answerlist']);
            if ($type == 'radio' || $type == 'checkbox') {
                foreach (Answer::factory()->count(4)->create() as $answer) {
                    $answerlist->answers()->attach($answer);
                }
                $answerlist->answers()->attach(Answer::factory()->create(['open'=>true]));
            } else {
                $answerlist->answers()->attach(Answer::hidden());
            }

            $question = Question::create(['title'=>$type.'-question', 'answerlist_id'=>$answerlist->id]);
            $item = Item::create(['survey_id'=>$survey->id, 'question_id'=>$question->id]);
        }
    }
}
