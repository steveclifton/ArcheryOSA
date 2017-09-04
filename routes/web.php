<?php


Route::get('/', 'HomeController@index')->name('home');

Route::middleware(['web'])->group(function() {

	// Guest
	Route::middleware(['guest'])->group(function () {
		Route::get('/login', 'UserController@getLoginView')->name('login');
		Route::post('/login', 'UserController@login');

		Route::get('/register', 'UserController@getRegisterView')->name('register');
		Route::post('/register', 'UserController@register');
	});

	//auth
	Route::middleware(['auth'])->group(function () {
		Route::get('/profile', 'UserController@getProfileView')->name('profile');
		Route::post('/updateprofile', 'UserController@updateProfile')->name('updateprofile');
		Route::get('/logout', 'UserController@logout')->name('logout');

	});

	Route::middleware(['admin'])->group(function () {
	   Route::get('/create-event', 'CreateEventController@getCreateView')->name('create-event');
	   Route::post('/create-new-event', 'CreateEventController@create')->name('create-new-event');

    });
});
