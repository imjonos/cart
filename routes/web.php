<?php
/**
 * CodersStudio 2019
 *  https://coders.studio
 *  info@coders.studio
 */

Route::pattern('item', '[0-9]+');
Route::namespace('CodersStudio\Cart\Http\Controllers')->middleware('web')->group(function () {
    Route::get('/cart', 'CartController@index');
    Route::post('/cart', 'CartController@store');
    Route::delete('/cart/{item}', 'CartController@destroy');
    Route::delete('/cart', 'CartController@clear');
});
