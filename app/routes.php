<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	echo Form::open(array('url' => 'upload', 'files'=>true));
    echo Form::file('file[]', array('multiple'=>true));
    echo Form::submit();
    echo Form::close();
	//return View::make('hello');
	//return Redirect::to('https://www.vdomax.com');
});




// =============================================
// API ROUTES ==================================
// =============================================
Route::group(array('prefix' => '1.0', 'before' => 'auth.token'), function() {
	// authentication service



	Route::get('auth', 'Tappleby\AuthToken\AuthTokenController@index');
	Route::delete('auth', 'Tappleby\AuthToken\AuthTokenController@destroy');
	// timeline
	Route::post('posts/user_timeline/{id}', 'PostsController@user_timeline');
	Route::post('posts/home_timeline/{id}', 'PostsController@home_timeline');

	Route::post('posts/{id}/comment', 'PostsController@comment');
	Route::post('posts/{id}/love', 'PostsController@love_post');
	Route::post('posts/{id}/follow', 'PostsController@follow_post');
	Route::post('posts/{id}/share', 'PostsController@share_post');
	Route::post('posts/{id}/report', 'PostsController@report_post');
	Route::post('posts/{id}/delete', 'PostsController@destroy');

	Route::post('followers/{id}', 'AccountController@followers');
	Route::post('followings/{id}', 'AccountController@followings');
	Route::post('friends/{id}', 'AccountController@friends');
	Route::post('pages/{id}', 'AccountController@pages');
	Route::post('follow_suggestion', 'AccountController@follow_suggestion');
	Route::post('follow/{id}','AccountController@follow');

	Route::post('user/password/update', 'UserController@changePassword');

	// user
	Route::resource('user', 'UserController', 
		array('except' => array('index', 'create', 'edit')));

	Route::post('user/{id}', 'UserController@show');
	// posts
	Route::resource('posts', 'PostsController', 
		array('except' => array('index', 'create', 'edit')));
});

// No authenticated route
Route::post('1.0/auth', 'Tappleby\AuthToken\AuthTokenController@store');
Route::post('1.0/fbAuth', 'UserController@facebookLogin');

Route::post('user/{username}/check', 'UserController@check');
Route::post('user/register', 'UserController@store');
Route::post('user/update/{id}', 'UserController@update');
Route::post('user/otp', 'UserController@requestOTP');
Route::get('search', 'SearchController@show');
Route::get('search/social', 'SearchController@social');
Route::get('search/channel', 'SearchController@channel');
Route::get('search/video', 'SearchController@video');
Route::get('search/video/related', 'SearchController@videoRelated');
Route::get('search/photo', 'SearchController@photo');
Route::get('search/photo/related', 'SearchController@photoRelated');
Route::get('search/hashtag', 'PostsController@hashtag');

Route::get('user/{id}/friends', 'AccountController@friends');
Route::get('user/{id}/followers', 'AccountController@followers');
Route::get('user/{id}/followings', 'AccountController@followings');
Route::get('user/{id}/pages', 'AccountController@pages');
Route::get('user/{id}/page', 'UserController@page');
Route::get('user/{id}/history', 'LiveController@history');
Route::get('user/{id}', 'UserController@show');
Route::get('username/{username}', 'UserController@showUsername');


Route::get('live/history/{id}', 'LiveController@history');
Route::get('live/history', 'LiveController@historyAll');
Route::get('live/now', 'LiveController@now');
Route::get('live/viewer/{id}', 'LiveController@viewer');

Route::get('story/{id}', 'PostsController@show');

	Route::get('posts/user_timeline/{id}', 'PostsController@user_timeline');
	Route::get('posts/home_timeline/{id}', 'PostsController@home_timeline');

	Route::get('posts/photo', 'PostsController@photos');
	Route::get('posts/video', 'PostsController@videos');

	

	Route::post('upload', 'UploadController@store');
	//Route::get('chat/relation/{id}', 'AccountController@relationList');






