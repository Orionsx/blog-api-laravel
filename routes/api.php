<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->group(function() {

    Route::prefix('category')->group(function() {

        Route::post('/create-category', 'CategoryController@store');
    
        Route::put('/update-category/{id}', 'CategoryController@update');
    
        Route::delete('/delete-category/{id}', 'CategoryController@delete');
    });
    
    
    Route::prefix('news')->group(function() {
        
        Route::post('/create-news', 'NewsController@store');
    
        Route::put('/update-news/{id}', 'NewsController@update');
    
        Route::delete('/delete-news/{id}', 'NewsController@delete');
    });

});


Route::post('/login', 'UserController@login');
Route::post('/registration', 'UserController@registration');

Route::prefix('category')->group(function() {

    Route::get('/', 'CategoryController@get');

    Route::get('/{id}', 'CategoryController@get');
});


Route::prefix('news')->group(function() {
    
    Route::get('/', 'NewsController@get');

    Route::get('/{id}', 'NewsController@get');
});
