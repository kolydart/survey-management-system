<?php

Route::group(['prefix' => 'public', 'as' => 'public.'], function () {

	Route::get('/'                , 'CollectController@index')->name('index');
	Route::get('/{survey}' , 'CollectController@create')->name('create');
	Route::post('/'               , 'CollectController@store')->name('store');
});


Route::group(['middleware' => ['auth'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('/survey/clone/{survey}', 'Admin\SurveysController@clone')->name('surveys.clone');

});