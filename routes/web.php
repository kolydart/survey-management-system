<?php

use App\Question;
use App\Questionnaire;
use App\Survey;
use gateweb\common\Database;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     // return view('welcome');
//     return redirect('/surveys');
// });

/**
 * Surveys
 */
Route::resource('/surveys','SurveysController');

/**
 * Questions
 */
Route::resource('/questions','QuestionsController');

/**
 * Questionnaires
 */
Route::resource('questionnaires', 'QuestionnairesController');

/** fix questionnaires => survey id */
/**
Route::get('fix',function (){
	// foreach questionnaire
	$questionnaires = App\Questionnaire::all();
	$surveys = collect(
		Survey::all()->map(function($survey){
			return [
					'id' => $survey->id, 
					'array' => $survey->questions->pluck('id')->sort()->all()
				]; 
			})
	);
	foreach ($questionnaires as $questionnaire) {
		$fingerprint = $questionnaire->questions->pluck('id')->unique()->sort()->toArray(); 
		$result = $surveys->map(function($survey)use($fingerprint){
			if(count(array_intersect($survey['array'], $fingerprint)) == 0) {
				return null;
			}
		});
		echo $result->filter()->count();
	}
		$questionnaire->survey_id = $x;
		$questionnaire->save();
}
);
*/





















