<?php

use App\City;
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


Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group([ 'auth', 'user_is_admin' ], function (){
    Route::get('units' , 'UnitController@index')->name('units');
    Route::get( 'add-unit', 'UnitController@showAdd' )-> name('new-unit');

});

