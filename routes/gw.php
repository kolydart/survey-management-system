<?php

Route::group(['prefix' => 'public', 'as' => 'public.'], function () {

	Route::get('/'                , 'CollectController@index')->name('index');
	// Route::get('{survey}'      , 'CollectController@show')->name('show');
	Route::get('/{survey}/create' , 'CollectController@create')->name('create');
	Route::post('/'               , 'CollectController@store')->name('store');
});
