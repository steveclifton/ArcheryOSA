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

        Route::get('/events', 'EventController@getEventsView')->name('events');
        Route::get('/events/create', 'EventController@getCreateView')->name('createevent');

        Route::get('/divisions', 'DivisionController@getDivisionsView')->name('divisions');
        Route::get('/divisions/create', 'DivisionController@getDivisionCreateView')->name('createdivisionview');
        Route::get('/divisions/update/{name}', 'DivisionController@updateDivision')->name('updatedivisionview');
        Route::post('/divisions/update/{name}', 'DivisionController@update')->name('updatedivision');
        Route::post('/divisions/create', 'DivisionController@create')->name('createdivision');

        Route::get('/organisations', 'OrganisationController@getOrganisationView')->name('organisations');
        Route::get('/organisations/create', 'OrganisationController@getOrganisationCreateView')->name('createorganisationview');
        Route::get('/organisations/update/{name}', 'OrganisationController@updateOrganisation')->name('updateorganisationview');
        Route::post('/organisations/update/{name}', 'OrganisationController@update')->name('updateorganisation');
        Route::post('/organisations/create', 'OrganisationController@create')->name('createorganisation');

        Route::get('/rounds', 'RoundController@getRoundsView')->name('rounds');
        Route::get('/rounds/create', 'RoundController@getRoundCreateView')->name('createroundview');
        Route::get('/rounds/update/{name}', 'RoundController@getUpdateRoundView')->name('updateroundview');
        Route::post('/rounds/update/{name}', 'RoundController@update')->name('updateround');
        Route::post('/rounds/create', 'RoundController@create')->name('createround');


    });
});
