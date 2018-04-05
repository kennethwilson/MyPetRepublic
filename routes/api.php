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
Route::get('password/reset', 'Auth\ResetPasswordController@postReset')->name('password.reset');
Route::post('register','AuthController@register');
Route::post('login','AuthController@login');
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
    Route::get('viewMyFollowings','UserController@viewMyFollowings');
    Route::get('viewMyFollowers','UserController@viewMyFollowers');
    Route::get('countFollowings','UserController@countFollowings');
    Route::get('countFollowers','UserController@countFollowers');

    Route::post('addDoggie','UserController@addDoggie');
    Route::get('viewAllDoggie','UserController@viewAllDoggie');
    Route::get('viewAllMyDoggie','UserController@viewAllMyDoggie');
    Route::delete('delete/{doggieID}','UserController@deleteDoggie');
    Route::post('update/{doggieID}','UserController@updateDoggie');
    Route::get('getName','UserController@getName');


    Route::post('/post/{dog_id}','PostsController@post');
    Route::delete('deletePost/{post_id}','PostsController@deletePost');
    Route::get('viewAllPosts/{dog_id}','PostsController@viewAllPosts');
    Route::get('viewPost/{post_id}','PostsController@viewPost');
    Route::post('updatePost/{post_id}','PostsController@updatePost');
    Route::get('likeCount/{post_id}','PostsController@likeCount');

    Route::get('likePost/{post_id}','UserController@likePost');
    Route::get('unlikePost/{post_id}','UserController@unlikePost');
});
