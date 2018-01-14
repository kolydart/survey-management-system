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
Route::get('/surveys','SurveyController@index')->name('survey.index');

Route::get('/test',function (){
	return Config::get('app.name');
}
);