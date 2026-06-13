<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => 'auth'], function () {
    //Print Barcode
    Route::get('/products/print-barcode', 'BarcodeController@printBarcode')->name('barcode.print');
    //Raw Material Product
    Route::get('/raw-materials', 'ProductController@rawMaterials')->name('raw-materials.index');
    Route::get('/raw-materials/create', 'ProductController@createRawMaterial')->name('raw-materials.create');
    Route::post('/raw-materials', 'ProductController@store')->name('raw-materials.store');
    //Product
    Route::resource('products', 'ProductController');
    //Product Category
    Route::resource('product-categories', 'CategoriesController')->except('create', 'show');
});

