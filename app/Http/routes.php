<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


use Illuminate\Http\Request;

Route::get('/', [
    'uses' => 'PostController@getIndex'
]);

Route::get('/login', [
    'uses' => 'PostController@getLogin',
    'as' => 'login'
]);

Route::get('/dashboard', [
    'uses' => 'PostController@getDashboard',
    'as' => 'dashboard',
    'middleware' => 'auth'
]);

Route::post('/signup', [
    'uses' => 'UserController@postSignUp',
    'as' => 'signup'
]);

Route::post('/signin', [
    'uses' => 'UserController@postSignIn',
    'as' => 'signin'
]);

Route::get('/logout', [
    'uses' => 'UserController@getLogout',
    'as' => 'logout',
    'middleware' => 'auth'
]);

Route::get('/profile', [
    'uses' => 'UserController@getProfile',
    'as' => 'profile',
    'middleware' => 'auth'
]);

Route::get('/getimage/{filename}', [
    'uses' => 'UserController@getImage',
    'as' => 'getimage',
    'middleware' => 'auth'
]);

Route::post('/saveprofile', [
    'uses' => 'UserController@postSaveProfile',
    'as' => 'profile.save',
    'middleware' => 'auth'
]);

Route::post('/createpost', [
    'uses' => 'PostController@postCreatePost',
    'as' => 'post.create',
    'middleware' => 'auth'
]);

Route::post('/likepost', [
    'uses' => 'PostController@postLikePost',
    'as' => 'post.like',
    'middleware' => 'auth'
]);

Route::post('/createcomment', [
    'uses' => 'PostController@postCreateComment',
    'as' => 'post.create.comment',
    'middleware' => 'auth'
]);

Route::get('/deletepost/{postId}', [
    'uses' => 'PostController@getDeletePost',
    'as' => 'post.delete',
    'middleware' => 'auth'
]);


Route::get('/deletecomment/{commentId}', [
    'uses' => 'PostController@getDeleteComment',
    'as' => 'comment.delete',
    'middleware' => 'auth'
]);

Route::post('/searchuser', [
    'uses' => 'UserController@postSearchUser',
    'as' => 'user.search',
    'middleware' => 'auth'
]);

Route::get('/chat/{receiver_id}', [
    'uses' => 'ChatController@getChatMessages',
    'as' => 'chat',
    'middleware' => 'auth'
]);

Route::post('/savechatmsg', [
    'uses' => 'ChatController@postSaveChatMsg',
    'as' => 'chat.save',
    'middleware' => 'auth'
]);

Route::get('/userslist', [
    'uses' => 'UserController@getUserList',
    'as' => 'user.list',
    'middleware' => 'auth'
]);


Route::get('/trackupdate', [
    'uses' => 'ActivityController@getTrackUpdate',
    'as' => 'trackupdate',
    'middleware' => 'auth'
]);