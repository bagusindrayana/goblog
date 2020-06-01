<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'BlogController@welcomePage');



Route::group(['prefix'=>'admin','as'=>'admin.'], function(){
    Auth::routes(['register'=>false]);
    Route::group(['middleware'=>['auth']], function(){
        Route::get('/home', 'HomeController@index')->name('home');
        Route::resource('/post', 'PostController');
        Route::resource('/category', 'CategoryController');
        Route::resource('/tag', 'TagController');
        Route::resource('/user', 'UserController');
    });
});


Route::group(['as'=>'blog.'], function(){
    Route::get('/{year}/{month}/{day}/{slug}','BlogController@singlePost');

    Route::group(['as'=>'filter.'], function(){
        Route::get('/category/{slug}','BlogController@filterPostByCategory')->name('category');
        Route::get('/tag/{slug}','BlogController@filterPostByTag')->name('tag');
    });

    
   
});




