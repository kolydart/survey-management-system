<?php

Route::group(['as' => 'frontend.'], function () {
    Route::get('/', 'Frontend\CollectController@index')->name('index');
    Route::get('/{alias}', 'Frontend\CollectController@create')->name('create');
    Route::post('/store', 'Frontend\CollectController@store')->name('store');
});

Route::group(['middleware' => ['auth'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('/survey/clone/{survey}', 'Admin\SurveysController@clone')->name('surveys.clone');
});
