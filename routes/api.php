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


Route::get('password/reset', 'Auth\ResetPasswordController@postReset')->name('password.reset');
Route::post('register','AuthController@register');
Route::post('login','AuthController@login');
Route::post('recoverpass','AuthController@recover');

Route::get('profile/{id}','UserController@getUserProfile');
Route::get('followingscount/{id}','UserController@followingCount');
Route::get('getDogProfile/{id}','UserController@getDogProfile');
  //Route::post('updateDisplayPic/{id}','UserController@updateDisplayPic');
Route::group(['middleware' => ['jwt.auth']], function() {
    Route::get('logout', 'AuthController@logout');
    Route::get('test', function(){
        return response()->json(['foo'=>'bar']);
    });
    Route::get('user',function (Request $request){
        return $request->user();
    });

    Route::get('sendMeetRequest/{dogid1}/{dogid2}','UserController@sendMeetRequest');

    Route::get('notifications', 'UserController@notifications');
    Route::get('readNotif','UserController@readNotif');
    Route::get('readNotifications/{id}','UserController@readNotifications');
    Route::get('clearAllNotif','UserController@clearAllNotif');

    Route::post('profile/{profileId}/follow', 'ProfileController@followUser');
    Route::post('profile/{profileId}/unfollow', 'ProfileController@unFollowUser');
    Route::get('profile/is_followed/{id}','ProfileController@is_followed');

    Route::post('update','UserController@update');
    Route::post('updateDisplayPic','UserController@updateDisplayPic');

    Route::get('viewFollowings/{id}','UserController@viewFollowings');
    Route::get('viewFollowers/{id}','UserController@viewFollowers');
    Route::get('countFollowings/{id}','UserController@countFollowings');
    Route::get('countFollowers/{id}','UserController@countFollowers');

    Route::post('addDoggie','UserController@addDoggie');
    Route::get('viewAllDoggie','UserController@viewAllDoggie');
    Route::get('viewAllMyDoggie','UserController@viewAllMyDoggie');
    Route::delete('delete/{doggieID}','UserController@deleteDoggie');
    Route::post('update/{doggieID}','UserController@updateDoggie');
    Route::post('updateDoggiePic/{doggieID}','UserController@updateDoggiePic');
    Route::get('getName','UserController@getName');


    Route::post('/post/{dog_id}','PostsController@post');
    Route::delete('deletePost/{post_id}','PostsController@deletePost');
    Route::get('viewAllPosts/{dog_id}','PostsController@viewAllPosts');
    Route::get('viewPost/{post_id}','PostsController@viewPost');
    Route::post('updatePost/{post_id}','PostsController@updatePost');
    Route::get('likeCount/{post_id}','PostsController@likeCount');

    Route::get('likePost/{post_id}','UserController@likePost');
    Route::get('unlikePost/{post_id}','UserController@unlikePost');
    Route::get('is_post_liked/{post_id}','UserController@post_is_liked');

    Route::post('comment_post/{post_id}','UserController@comment_post');
    Route::delete('delete_comment/{comment_id}','UserController@delete_comment');
});
