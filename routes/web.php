<?php

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

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    return view('test');
});

Route::get('/index', function () {
    return view('index');
});

Route::group(['middleware' => 'auth'], function () {
     
    Route::get('/projects', 'ProjectsController@index');

    Route::get('/projects/create', 'ProjectsController@create');

    Route::get('/projects/{project}', 'ProjectsController@show');

    Route::post('/projects', 'ProjectsController@store');    

});
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
