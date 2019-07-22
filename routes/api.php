<?php

use Illuminate\Http\Request;

Route::group([], function(){
    // Route::post('/', 'PnrApiController@apiAuth');
    Route::get('blog', 'BlogController@getArticles');
    Route::get('blog/{slug}', 'BlogController@getArticleBySlug');

    Route::post('write-file', 'LogController@logUserInput');
    Route::post('convert', 'PnrController@convertPnr');
    Route::post('paypal-transaction-complete', 'PaypalController@paypal');
    Route::put('emailsend', 'emailController@sendemail');
    

});

Route::post('/', 'PnrApiController@apiAuth');
//probavly dont need the below
Route::post('/payment', 'PaymentController@subscription');

Route::group([
    'prefix' => 'auth',
    // 'middleware' => 'throttle:20',
], function () {
    Route::post('login', 'AuthController@login');
});

Route::group([
    'prefix' => 'auth'
], function () {
    // Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signup');
    Route::get('signup/activate/{token}', 'AuthController@signupActivate');
    Route::post('/subscription', 'SubscriptionController@create');
    Route::post('/cancelsubscription', 'StripeSubscriptionController@cancel');
    Route::post('/updatesubscription', 'StripeSubscriptionController@update');
    Route::post('/stripesubscription', 'StripeSubscriptionController@create');
    Route::post('/getnextpaymentdate', 'StripeSubscriptionController@getSubscriptionRenewDate');
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
        Route::put('contactusmail', 'AuthController@contactus');
        Route::get('/getinvoices/{user_id}', 'StripeSubscriptionController@getInvoices');
        Route::get('/getinvoice/{user_id}/{invoice_id}', 'StripeSubscriptionController@view_invoice');
    });
});

Route::group([    
    'namespace' => 'Auth',    
    'middleware' => 'api',    
    'prefix' => 'password'
], function () {    
    Route::post('create', 'PasswordResetController@create');
    Route::get('find/{token}', 'PasswordResetController@find');
    Route::post('reset', 'PasswordResetController@reset');
});