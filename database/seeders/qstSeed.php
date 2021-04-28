<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class qstSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $paths = [
            'database/dumps/institutions.sql',
            'database/dumps/groups.sql',
            'database/dumps/categories.sql',
            'database/dumps/answers.sql',
            'database/dumps/surveys.sql',
            'database/dumps/questionnaires.sql',
            'database/dumps/group_survey.sql',
            'database/dumps/answerlists.sql',
            'database/dumps/answer_answerlist.sql',
            'database/dumps/questions.sql',
            'database/dumps/items (question_survey).sql',
            'database/dumps/question_questionnaire (response).sql',
            'database/dumps/category_survey.sql',
        ];

        \Eloquent::unguard();
        foreach ($paths as $path) {
            $result = DB::unprepared(file_get_contents($path));
            abort_if($result !== true, 500);
        }

        /** re-delete models in order to softcascade */
        foreach (\App\Questionnaire::onlyTrashed()->get() as $model) {
            $model->restore();
            $model->delete();
        }
        foreach (\App\Item::onlyTrashed()->get() as $model) {
            $model->restore();
            $model->delete();
        }
        foreach (\App\Question::onlyTrashed()->get() as $model) {
            $model->restore();
            $model->delete();
        }
        foreach (\App\Answerlist::onlyTrashed()->get() as $model) {
            $model->restore();
            $model->delete();
        }
        foreach (\App\Answer::onlyTrashed()->get() as $model) {
            $model->restore();
            $model->delete();
        }
        //delete orphan responses
        \App\Response::whereNull('answer_id')->orWhereNull('question_id')->orWhereNull('questionnaire_id')->delete();

        /** removeDuplicates */
        function removeDuplicates($Model, $tables, $field)
        {
            $duplicates = $Model::selectRaw('title, COUNT(*) as `count` ')->groupBy('title')->having('count', '>', 1)->get();

            foreach ($duplicates as $duplicate) {
                $found = $Model::where('title', '=', $duplicate->title)->get();

                // get first
                $first = $found->shift();

                // iterate remaining
                foreach ($found as $current) {
                    // update related IDs
                    foreach ($tables as $table) {
                        DB::table($table)->where($field, $current->id)->update([$field => $first->id]);
                    }
                    // delete it
                    $current->delete();
                }
            }
        }

        $Model = \App\Answer::class;
        $tables = ['responses', 'answer_answerlist'];
        $field = 'answer_id';
        removeDuplicates($Model, $tables, $field);

        $Model = \App\Question::class;
        $tables = ['responses', 'items'];
        $field = 'question_id';
        removeDuplicates($Model, $tables, $field);

        $Model = \App\Group::class;
        $tables = ['group_survey'];
        $field = 'group_id';
        removeDuplicates($Model, $tables, $field);

        /** answerlist is last */
        $Model = \App\Answerlist::class;
        $table = 'questions';
        $field = 'answerlist_id';
        $array = [
            [1013, 2013],
            [1002, 2002],
            [1010, 2010],
            [1012, 2012],
            [1001, 2001],
            [1006, 2006],
            [1007, 2007],
            [1011, 2011],
            [1005, 2005],
            [1004, 2004],
        ];

        foreach ($array as $pair) {
            $found = $Model::find($pair);

            // get first
            $first = $found->shift();

            // update related IDs
            DB::table($table)->where($field, $found->first()->id)->update([$field => $first->id]);

            // delete it
            $found->first()->delete();
        }
    }
}
