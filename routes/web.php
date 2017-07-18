<?php

Route::get('/', 'Home@index')->name('home');

Auth::routes();

Route::get('/home', 'HomeController@index');
Route::get('logout', 'Auth\LogoutController@logout');