<?php

Route::group(['prefix' => 'public', 'as' => 'public.'], function () {

	Route::get('/'        , 'CollectController@index');
	Route::get('{survey}' , 'CollectController@show');
	Route::get('create'   , 'CollectController@create');
	Route::post('/'       , 'CollectController@store');
});
