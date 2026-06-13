<?php

Route::group(['middleware' => 'auth'], function () {
    Route::resource('employees', 'EmployeeController');
    Route::get('employees/{employee}/ledger', 'EmployeeController@ledger')->name('employees.ledger');
    Route::resource('overtimes', 'OvertimeController');
    Route::resource('bonuses', 'BonusController');
    Route::resource('payrolls', 'PayrollController');
    Route::get('attendances/bulk', 'AttendanceController@bulk')->name('attendances.bulk');
    Route::post('attendances/bulk-store', 'AttendanceController@bulkStore')->name('attendances.bulk-store');
    Route::resource('attendances', 'AttendanceController');

    Route::get('payrolls/get-overtime/{employee}/{start}/{end}', 'PayrollController@getOvertime')
        ->name('payrolls.get-overtime');
});
