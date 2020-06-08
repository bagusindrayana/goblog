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

Route::get('/test-notif',function(){

// API access key from Google API's Console
define( 'API_ACCESS_KEY', 'AIzaSyBzORT2wF-E2LnzInaggtrbHzQ-2ViY_A0' );
$registrationIds = array( $_GET['id'] );
// prep the bundle
$msg = array
(
    'message'   => 'here is a message. message',
    'title'     => 'This is a title. title',
    'subtitle'  => 'This is a subtitle. subtitle',
    'tickerText'    => 'Ticker text here...Ticker text here...Ticker text here',
    'vibrate'   => 1,
    'sound'     => 1,
    'largeIcon' => 'large_icon',
    'smallIcon' => 'small_icon'
);
$fields = array
(
    'registration_ids'  => $registrationIds,
    'data'          => $msg
);
  
$headers = array
(
    'Authorization: key=' . API_ACCESS_KEY,
    'Content-Type: application/json'
);
  
$ch = curl_init();
curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send' );
curl_setopt( $ch,CURLOPT_POST, true );
curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
$result = curl_exec($ch );
curl_close( $ch );
echo $result;
});

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

    Route::get('/archive/{year}','BlogController@archivePostYear')->name('archive.year');
    Route::get('/archive/{year}/{month}','BlogController@archivePostYearMonth')->name('archive.year-month');
    Route::get('/archive/{year}/{month}/{day}','BlogController@archivePostYearMonthDay')->name('archive.year-month-day');


    Route::get('/{year}/{month}/{day}/{slug}','BlogController@singlePost')->name('single-post');

    Route::group(['as'=>'filter.'], function(){
        Route::get('/category/{slug}','BlogController@filterPostByCategory')->name('category');
        Route::get('/tag/{slug}','BlogController@filterPostByTag')->name('tag');
    });

    
   
});




