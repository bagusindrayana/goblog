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
Route::get('/{page_slug}', 'BlogController@openPage');



Route::group(['prefix'=>'admin','as'=>'admin.'], function(){
    Auth::routes(['register'=>false]);
    Route::group(['middleware'=>['auth']], function(){
        Route::get('/home', 'HomeController@index')->name('home');
        Route::resource('/post', 'PostController');
        Route::resource('/category', 'CategoryController');
        Route::resource('/tag', 'TagController');

        Route::resource('/page', 'PageController');
        Route::get("/page/create/web-builder",'PageController@viewWebBuilder')->name("page.web-builder");
        Route::get('/page/edit/web-builder/{id}', 'PageController@editWebBuilder')->name('page.web-builder.edit');
        Route::post('/save-web-builder', 'PageController@saveWebBuilder')->name('page.save.web-builder');
        Route::post('/update-web-builder/{id}', 'PageController@updateWebBuilder')->name('page.update.web-builder');
        Route::get('/load-web-builder/{id}', 'PageController@loadWebBuilder')->name('page.load.web-builder');

        Route::resource('/role', 'RoleController');
        Route::resource('/user', 'UserController');

        Route::get('/media', function (){
            return view('admin.media.index');
        });

        Route::get('/menu','MenuController@index');
        Route::resource('/setting','SettingController');
    });
});


Route::group(['as'=>'blog.'], function(){

    Route::get('/archive/{year}','BlogController@archivePostYear')->name('archive.year');
    Route::get('/archive/{year}/{month}','BlogController@archivePostYearMonth')->name('archive.year-month');
    Route::get('/archive/{year}/{month}/{day}','BlogController@archivePostYearMonthDay')->name('archive.year-month-day');


    Route::get('/{year}/{month}/{day}/{slug}','BlogController@singlePost')->name('single-post');

    Route::group(['as'=>'filter.'], function(){
        Route::get('/category/{slug}','BlogController@filterPostByCategory')->name('category');
        Route::get('/tag/{slug}','BlogController@filterPostByTag')->name('tag');
    });

    
   
});




