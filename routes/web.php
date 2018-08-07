<?php
Route::get('/', function () { return redirect('/admin/home'); });

// Authentication Routes...
$this->get('login', 'Auth\LoginController@showLoginForm')->name('login');
$this->post('login', 'Auth\LoginController@login')->name('auth.login');
$this->post('logout', 'Auth\LoginController@logout')->name('auth.logout');

// Change Password Routes...
$this->get('change_password', 'Auth\ChangePasswordController@showChangePasswordForm')->name('auth.change_password');
$this->patch('change_password', 'Auth\ChangePasswordController@changePassword')->name('auth.change_password');

// Password Reset Routes...
$this->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('auth.password.reset');
$this->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('auth.password.reset');
$this->get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
$this->post('password/reset', 'Auth\ResetPasswordController@reset')->name('auth.password.reset');

Route::group(['middleware' => ['auth'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('/home', 'HomeController@index');
    
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
    Route::resource('users', 'Admin\UsersController');
    Route::post('users_mass_destroy', ['uses' => 'Admin\UsersController@massDestroy', 'as' => 'users.mass_destroy']);
    Route::resource('institutions', 'Admin\InstitutionsController');
    Route::post('institutions_mass_destroy', ['uses' => 'Admin\InstitutionsController@massDestroy', 'as' => 'institutions.mass_destroy']);
    Route::post('institutions_restore/{id}', ['uses' => 'Admin\InstitutionsController@restore', 'as' => 'institutions.restore']);
    Route::delete('institutions_perma_del/{id}', ['uses' => 'Admin\InstitutionsController@perma_del', 'as' => 'institutions.perma_del']);



 
});
