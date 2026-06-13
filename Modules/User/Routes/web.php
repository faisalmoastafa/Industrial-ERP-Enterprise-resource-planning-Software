<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => 'auth'], function () {

    //User Profile
    Route::get('/user/profile', 'ProfileController@edit')->name('profile.edit');
    Route::patch('/user/profile', 'ProfileController@update')->name('profile.update');
    Route::patch('/user/password', 'ProfileController@updatePassword')->name('profile.update.password');
    Route::get('/user/activity-log', 'ActivityLogController@index')->name('activity-log.index');
    Route::get('/user/activity-log/{entry}', 'ActivityLogController@show')->name('activity-log.show');

    //Users
    Route::resource('users', 'UsersController')->except('show');

    //Roles
    Route::resource('roles', 'RolesController')->except('show');

});
