<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('auth.login');
})->middleware('guest');

// ── Splash data endpoint — read by the Electron splash screen before login ──
// Returns logo URL, app title and tagline as JSON so splash.html can inject them.
Route::get('/splash-logo', function () {
    $s = settings();
    return response()->json([
        'logo'    => $s->getLogoSolidUrl(),
        'title'   => $s->app_title   ?: 'NECI ERP',
        'tagline' => $s->app_tagline ?: 'NEC Super and Cables Industries',
    ])->header('Cache-Control', 'no-store');
})->name('splash.logo');

Auth::routes(['register' => false]);

Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', 'HomeController@index')
        ->name('home');

    Route::get('/sales-purchases/chart-data', 'HomeController@salesPurchasesChart')
        ->name('sales-purchases.chart');

    Route::get('/current-month/chart-data', 'HomeController@currentMonthChart')
        ->name('current-month.chart');

    Route::get('/payment-flow/chart-data', 'HomeController@paymentChart')
        ->name('payment-flow.chart');

    Route::get('/payable-receivable/chart-data', 'HomeController@payableReceivableChart')
        ->name('payable-receivable.chart');

    Route::get('/prepay-pay-later/chart-data', 'HomeController@prepayPayLaterChart')
        ->name('prepay-pay-later.chart');

    Route::get('/stock-movement/chart-data', 'HomeController@stockMovementChart')
        ->name('stock-movement.chart');
    Route::get('/stock-movement/weekly-chart-data', 'HomeController@stockMovementWeeklyChart')
        ->name('stock-movement.weekly.chart');

    Route::get('/party-payments/ledger', 'PartyPaymentController@ledger')
        ->name('party-payments.ledger');
    Route::resource('party-payments', 'PartyPaymentController');
        
    // --- System Utilities (Backup & Restore) ---
    Route::get('/backup', [App\Http\Controllers\BackupController::class, 'index'])
        ->middleware('permission:access_backup')
        ->name('backup.index');

    Route::post('/backup', [App\Http\Controllers\BackupController::class, 'store'])
        ->middleware('permission:access_backup')
        ->name('backup.store');

    Route::get('/restore', [App\Http\Controllers\RestoreController::class, 'index'])
        ->middleware('permission:access_restore')
        ->name('restore.index');

    Route::post('/restore/process', [App\Http\Controllers\RestoreController::class, 'systemRestore'])
        ->middleware('permission:access_restore')
        ->name('restore.process');

    Route::post('/restore/open-folder', [App\Http\Controllers\RestoreController::class, 'openBackupFolder'])
        ->middleware('permission:access_restore')
        ->name('restore.open_folder');

});

Route::get('/storage/{path}', function ($path) {
    $path = str_replace('..', '', $path);
    $filePath = storage_path('app/public/' . $path);
    if (!file_exists($filePath)) {
        abort(404);
    }
    return response()->file($filePath);
})->where('path', '.*');

