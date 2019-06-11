<?php

use Illuminate\Http\Request;

Route::group([], function(){
    Route::get('blog', 'BlogController@getArticles');
    Route::get('blog/{slug}', 'BlogController@getArticleBySlug');

    Route::post('write-file', 'LogController@logUserInput');
//still need to do the above

    Route::post('convert', 'PnrController@convertPnr');

});

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signup');
    Route::get('signup/activate/{token}', 'AuthController@signupActivate');
  
    Route::group([
      'middleware' => 'auth:api'
    ], function() {
        Route::get('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user');
        Route::put('update', 'AuthController@update');
        Route::get('gettoken/{id}', 'AuthController@gettoken');
    });
});