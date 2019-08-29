<?php
/**
 * CodersStudio 2019
 *  https://coders.studio
 *  info@coders.studio
 */

Route::pattern('item', '[0-9]+');
Route::namespace('CodersStudio\Cart\Http\Controllers')->middleware('web')->group(function () {
    Route::get('/cart', 'CartController@index');
    Route::put('/cart', 'CartController@update');
    Route::post('/cart', 'CartController@store');
    Route::delete('/cart/{item}', 'CartController@destroy');
    Route::delete('/cart', 'CartController@clear');

    Route::get('/checkout', 'CheckoutController@checkout');
    Route::post('/checkout', 'CheckoutController@checkout');

    // callback handlers
    Route::get('/checkout/success/{payment_method_id}', 'CheckoutController@success')->name('checkout.success');
    Route::get('/checkout/fail/{payment_method_id}', 'CheckoutController@fail')->name('checkout.fail');

    // static pages
    Route::get('/payment/success', 'CheckoutController@successPage')->name('payment.success');
    Route::get('/payment/fail', 'CheckoutController@failPage')->name('payment.fail');
});
