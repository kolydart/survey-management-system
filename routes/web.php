<?php

// Route::get('/', function () { return redirect('/admin/home'); });

// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login')->name('auth.login');
Route::post('logout', 'Auth\LoginController@logout')->name('auth.logout');

// Change Password Routes...
Route::get('change_password', 'Auth\ChangePasswordController@showChangePasswordForm')->name('auth.change_password');
Route::patch('change_password', 'Auth\ChangePasswordController@changePassword')->name('auth.change_password');

// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('auth.password.reset');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('auth.password.reset');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('auth.password.reset');

Route::group(['middleware' => ['auth'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/reports/questionnaires', 'Admin\ReportsController@questionnaires');

    Route::resource('surveys', 'Admin\SurveysController');
    Route::post('surveys_mass_destroy', ['uses' => 'Admin\SurveysController@massDestroy', 'as' => 'surveys.mass_destroy']);
    Route::post('surveys_restore/{id}', ['uses' => 'Admin\SurveysController@restore', 'as' => 'surveys.restore']);
    Route::delete('surveys_perma_del/{id}', ['uses' => 'Admin\SurveysController@perma_del', 'as' => 'surveys.perma_del']);
    Route::resource('questionnaires', 'Admin\QuestionnairesController');
    Route::post('questionnaires_mass_destroy', ['uses' => 'Admin\QuestionnairesController@massDestroy', 'as' => 'questionnaires.mass_destroy']);
    Route::post('questionnaires_restore/{id}', ['uses' => 'Admin\QuestionnairesController@restore', 'as' => 'questionnaires.restore']);
    Route::delete('questionnaires_perma_del/{id}', ['uses' => 'Admin\QuestionnairesController@perma_del', 'as' => 'questionnaires.perma_del']);
    Route::resource('responses', 'Admin\ResponsesController');
    Route::get('responses/index/content', 'Admin\ResponsesController@index_content')->name('responses.index.content');
    Route::post('responses_mass_destroy', ['uses' => 'Admin\ResponsesController@massDestroy', 'as' => 'responses.mass_destroy']);
    Route::post('responses_restore/{id}', ['uses' => 'Admin\ResponsesController@restore', 'as' => 'responses.restore']);
    Route::delete('responses_perma_del/{id}', ['uses' => 'Admin\ResponsesController@perma_del', 'as' => 'responses.perma_del']);
    Route::resource('items', 'Admin\ItemsController');
    Route::post('items_mass_destroy', ['uses' => 'Admin\ItemsController@massDestroy', 'as' => 'items.mass_destroy']);
    Route::post('items_restore/{id}', ['uses' => 'Admin\ItemsController@restore', 'as' => 'items.restore']);
    Route::delete('items_perma_del/{id}', ['uses' => 'Admin\ItemsController@perma_del', 'as' => 'items.perma_del']);
    Route::resource('questions', 'Admin\QuestionsController');
    Route::post('questions_mass_destroy', ['uses' => 'Admin\QuestionsController@massDestroy', 'as' => 'questions.mass_destroy']);
    Route::post('questions_restore/{id}', ['uses' => 'Admin\QuestionsController@restore', 'as' => 'questions.restore']);
    Route::delete('questions_perma_del/{id}', ['uses' => 'Admin\QuestionsController@perma_del', 'as' => 'questions.perma_del']);
    Route::resource('answerlists', 'Admin\AnswerlistsController');
    Route::post('answerlists_mass_destroy', ['uses' => 'Admin\AnswerlistsController@massDestroy', 'as' => 'answerlists.mass_destroy']);
    Route::post('answerlists_restore/{id}', ['uses' => 'Admin\AnswerlistsController@restore', 'as' => 'answerlists.restore']);
    Route::delete('answerlists_perma_del/{id}', ['uses' => 'Admin\AnswerlistsController@perma_del', 'as' => 'answerlists.perma_del']);
    Route::resource('answers', 'Admin\AnswersController');
    Route::post('answers_mass_destroy', ['uses' => 'Admin\AnswersController@massDestroy', 'as' => 'answers.mass_destroy']);
    Route::post('answers_restore/{id}', ['uses' => 'Admin\AnswersController@restore', 'as' => 'answers.restore']);
    Route::delete('answers_perma_del/{id}', ['uses' => 'Admin\AnswersController@perma_del', 'as' => 'answers.perma_del']);
    Route::resource('content_pages', 'Admin\ContentPagesController');
    Route::post('content_pages_mass_destroy', ['uses' => 'Admin\ContentPagesController@massDestroy', 'as' => 'content_pages.mass_destroy']);
    Route::resource('content_categories', 'Admin\ContentCategoriesController');
    Route::post('content_categories_mass_destroy', ['uses' => 'Admin\ContentCategoriesController@massDestroy', 'as' => 'content_categories.mass_destroy']);
    Route::resource('content_tags', 'Admin\ContentTagsController');
    Route::post('content_tags_mass_destroy', ['uses' => 'Admin\ContentTagsController@massDestroy', 'as' => 'content_tags.mass_destroy']);
    Route::resource('institutions', 'Admin\InstitutionsController');
    Route::post('institutions_mass_destroy', ['uses' => 'Admin\InstitutionsController@massDestroy', 'as' => 'institutions.mass_destroy']);
    Route::post('institutions_restore/{id}', ['uses' => 'Admin\InstitutionsController@restore', 'as' => 'institutions.restore']);
    Route::delete('institutions_perma_del/{id}', ['uses' => 'Admin\InstitutionsController@perma_del', 'as' => 'institutions.perma_del']);
    Route::resource('groups', 'Admin\GroupsController');
    Route::post('groups_mass_destroy', ['uses' => 'Admin\GroupsController@massDestroy', 'as' => 'groups.mass_destroy']);
    Route::post('groups_restore/{id}', ['uses' => 'Admin\GroupsController@restore', 'as' => 'groups.restore']);
    Route::delete('groups_perma_del/{id}', ['uses' => 'Admin\GroupsController@perma_del', 'as' => 'groups.perma_del']);
    Route::resource('categories', 'Admin\CategoriesController');
    Route::post('categories_mass_destroy', ['uses' => 'Admin\CategoriesController@massDestroy', 'as' => 'categories.mass_destroy']);
    Route::post('categories_restore/{id}', ['uses' => 'Admin\CategoriesController@restore', 'as' => 'categories.restore']);
    Route::delete('categories_perma_del/{id}', ['uses' => 'Admin\CategoriesController@perma_del', 'as' => 'categories.perma_del']);
    Route::resource('roles', 'Admin\RolesController');
    Route::post('roles_mass_destroy', ['uses' => 'Admin\RolesController@massDestroy', 'as' => 'roles.mass_destroy']);
    Route::resource('activitylogs', 'Admin\ActivitylogsController');
    Route::post('activitylogs_mass_destroy', ['uses' => 'Admin\ActivitylogsController@massDestroy', 'as' => 'activitylogs.mass_destroy']);
    Route::resource('loguseragents', 'Admin\LoguseragentsController');
    Route::post('loguseragents_mass_destroy', ['uses' => 'Admin\LoguseragentsController@massDestroy', 'as' => 'loguseragents.mass_destroy']);
    Route::resource('users', 'Admin\UsersController');
    Route::post('users_mass_destroy', ['uses' => 'Admin\UsersController@massDestroy', 'as' => 'users.mass_destroy']);
});

include __DIR__.'/gw.php';
