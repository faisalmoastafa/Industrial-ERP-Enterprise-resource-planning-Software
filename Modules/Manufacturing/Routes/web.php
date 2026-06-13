<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth'], function () {
    Route::get('production-report', 'ProductionBatchController@report')->name('production-report.index');
    Route::get('conversion-expenses/create', 'ProductionBatchExpenseController@create')->name('conversion-expenses.create');
    Route::post('conversion-expenses', 'ProductionBatchExpenseController@store')->name('conversion-expenses.store');

    Route::resource('production-batches', 'ProductionBatchController')
        ->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);
});
