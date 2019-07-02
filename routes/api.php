<?php

use Illuminate\Http\Request;

Route::group([], function(){
    // Route::post('/', 'PnrApiController@apiAuth');
    Route::get('blog', 'BlogController@getArticles');
    Route::get('blog/{slug}', 'BlogController@getArticleBySlug');

    Route::post('write-file', 'LogController@logUserInput');
    Route::post('convert', 'PnrController@convertPnr');

});

Route::post('/', 'PnrApiController@apiAuth');
//probavly dont need the below
Route::post('/payment', 'PaymentController@subscription');

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signup');
    Route::get('signup/activate/{token}', 'AuthController@signupActivate');
    Route::post('/subscription', 'SubscriptionController@create');
    Route::post('/cancelsubscription', 'SubscriptionController@cancel');
    Route::post('/updatesubscription', 'SubscriptionController@update');
    Route::get('plans', 'PlanController@index');
    
    Route::group([
        'middleware' => 'auth:api'
    ], function() {
        Route::get('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user');
        Route::put('update', 'AuthController@update');
        Route::put('updatealluserdetails', 'AuthController@updateAllUserDetails');
        Route::get('gettoken/{id}', 'AuthController@gettoken');
        Route::get('/planbyslug/{slug}', 'PlanController@planbyslug');
        Route::get('/braintree/token', 'BraintreeTokenController@index');
    });
});