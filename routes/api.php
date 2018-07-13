<?php

Route::group(['prefix' => '/v1', 'middleware' => ['auth:api'], 'namespace' => 'Api\V1', 'as' => 'api.'], function () {
    Route::post('change-password', 'ChangePasswordController@changePassword')->name('auth.change_password');
    Route::apiResource('rules', 'RulesController', ['only' => ['index']]);
    Route::apiResource('surveys', 'SurveysController');
    Route::apiResource('questionnaires', 'QuestionnairesController');
    Route::apiResource('responses', 'ResponsesController');
    Route::apiResource('items', 'ItemsController');
    Route::apiResource('questions', 'QuestionsController');
    Route::apiResource('answerlists', 'AnswerlistsController');
    Route::apiResource('answers', 'AnswersController');
    Route::apiResource('institutions', 'InstitutionsController');
    Route::apiResource('groups', 'GroupsController');
    Route::apiResource('categories', 'CategoriesController');
    Route::apiResource('users', 'UsersController');
    Route::apiResource('roles', 'RolesController');
    Route::apiResource('permissions', 'PermissionsController');
    Route::apiResource('content-categories', 'ContentCategoriesController');
    Route::apiResource('content-tags', 'ContentTagsController');
    Route::apiResource('content-pages', 'ContentPagesController');
});
