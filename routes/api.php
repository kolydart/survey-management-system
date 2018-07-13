<?php

Route::group(['prefix' => '/v1', 'middleware' => ['auth:api'], 'namespace' => 'Api\V1', 'as' => 'api.'], function () {
    Route::post('change-password', 'ChangePasswordController@changePassword')->name('auth.change_password');
    Route::apiResource('rules', 'RulesController', ['only' => ['index']]);
    Route::apiResource('surveys', 'SurveysController');
    Route::apiResource('items', 'ItemsController');
    Route::apiResource('questionnaires', 'QuestionnairesController');
    Route::apiResource('institutions', 'InstitutionsController');
    Route::apiResource('groups', 'GroupsController');
    Route::apiResource('categories', 'CategoriesController');
    Route::apiResource('questions', 'QuestionsController');
    Route::apiResource('users', 'UsersController');
    Route::apiResource('roles', 'RolesController');
    Route::apiResource('permissions', 'PermissionsController');
});
