<?php


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

Route::get('/', function () {
    // return view('welcome');
    return redirect('/surveys');
});

/**
 * Surveys
 */
Route::get('/surveys','SurveysController@index')->name('surveys.index');

/**
 * Questions
 */
Route::get('/questions','QuestionsController@index')->name('questions.index');

/**
 * Questionnaires
 */
Route::get('/questionnaires','QuestionnairesController@index')->name('questionnaires.index');