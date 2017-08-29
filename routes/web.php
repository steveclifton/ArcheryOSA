<?php

Auth::routes();


Route::get('/', 'HomeController@index')->name('home');

Route::get('logout', 'Auth\LogoutController@logout');
Route::get('profile', 'UserController@index');
Route::post('updateprofile', 'UserController@updateProfile')->name('updateprofile');


