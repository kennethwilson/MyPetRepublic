<?php

use Illuminate\Http\Request;

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

Route::get('user/verify/{verification_code}', 'AuthController@verifyUser');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.request');
Route::post('password/reset', 'Auth\ResetPasswordController@postReset')->name('password.reset');
Route::post('register','AuthController@register');
Route::post('login','AuthController@login');
Route::get('logout','AuthController@logout');
Route::post('recoverpass','AuthController@recover');



Route::group(['middleware' => ['jwt.auth']], function() {
    Route::get('logout', 'AuthController@logout');
    Route::get('test', function(){
        return response()->json(['foo'=>'bar']);
    });
    Route::get('user',function (Request $request){
        return $request->user();
    });
    Route::post('profile/{profileId}/follow', 'ProfileController@followUser')->name('user.follow');
    Route::post('/{profileId}/unfollow', 'ProfileController@unFollowUser')->name('user.unfollow');

    Route::post('update','UserController@update');
});
