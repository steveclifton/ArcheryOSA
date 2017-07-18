<?php

use App\Club;
use App\Event;
use App\Round;
use App\Roundgroup;
use App\Roundtype;
use App\User;
use App\District;

Route::get('/', 'Home@index')->name('home');

Auth::routes();

Route::get('/home', 'HomeController@index');




// Authentication routes...
// Route::get('auth/login', 'Auth\AuthController@getLogin');
// Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('logout', 'auth\LogoutController@logout');

// Registration routes...
//Route::get('/register', 'Auth\RegisterController@getRegister');
// Route::post('auth/register', 'Auth\AuthController@postRegister');


// Route::controllers([
//    'password' => 'Auth\PasswordController',
// ]);
