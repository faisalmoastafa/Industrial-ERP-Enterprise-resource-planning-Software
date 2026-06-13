<?php

Route::group(['middleware' => 'auth'], function () {
    Route::resource('income-categories', 'IncomeCategoriesController')->except('show', 'create');
    Route::resource('incomes', 'IncomeController');
});
