<?php


Route::get('/', 'HomeController@index')->name('home');
Route::get('/clubs', 'ClubController@getPublicViewClubs')->name('public.clubs');


Route::middleware(['web'])->group(function() {

	// Guest
	Route::middleware(['guest'])->group(function () {

		Route::get('/login', 'UserController@getLoginView')->name('login');
		Route::post('/login', 'UserController@login');

		Route::get('/register', 'UserController@getRegisterView')->name('register');
		Route::post('/register', 'UserController@register');
	});

	// Auth
	Route::middleware(['auth'])->group(function () {
		Route::get('/profile', 'UserController@getProfileView')->name('profile');
		Route::post('/updateprofile', 'UserController@updateProfile')->name('updateprofile');
		Route::get('/logout', 'UserController@logout')->name('logout');

	});

	// Admin
	Route::middleware(['admin'])->group(function () {

        Route::get('/admin/events', 'EventController@getEventsView')->name('events');
        Route::get('/admin/events/create', 'EventController@getCreateView')->name('createeventview');
        Route::get('/admin/events/update/{eventid}', 'EventController@getUpdateEventView')->name('updateeventview');
        Route::post('/admin/events/update/{eventid}', 'EventController@update')->name('updateevent');
        Route::post('/admin/events/create', 'EventController@create')->name('createevent');




        Route::get('/admin/events/create/eventday/{eventid}', 'EventDayController@getCreateEventDayFunction')->name('createeventdayview');
        Route::post('/admin/events/create/eventday/', 'EventDayController@create')->name('createeventday');

        Route::get('/admin/events/update/eventday/{eventdayid}', 'EventDayController@getUpdateDayEventView')->name('updateeventdayview');
        Route::post('/admin/events/update/eventday/{eventdayid}', 'EventDayController@update')->name('updateeventday');




        Route::get('/admin/divisions', 'DivisionController@getDivisionsView')->name('divisions');
        Route::get('/admin/divisions/create', 'DivisionController@getDivisionCreateView')->name('createdivisionview');
        Route::get('/admin/divisions/update/{name}', 'DivisionController@getUpdateDivisionView')->name('updatedivisionview');
        Route::post('/admin/divisions/update/{name}', 'DivisionController@update')->name('updatedivision');
        Route::post('/admin/divisions/create', 'DivisionController@create')->name('createdivision');

        Route::get('/admin/organisations', 'OrganisationController@getOrganisationView')->name('organisations');
        Route::get('/admin/organisations/create', 'OrganisationController@getOrganisationCreateView')->name('createorganisationview');
        Route::get('/admin/organisations/update/{name}', 'OrganisationController@getUpdateOrganisationView')->name('updateorganisationview');
        Route::post('/admin/organisations/update/{name}', 'OrganisationController@update')->name('updateorganisation');
        Route::post('/admin/organisations/create', 'OrganisationController@create')->name('createorganisation');

        Route::get('/admin/federations', 'FederationController@getFederationView')->name('federations');
        Route::get('/admin/federations/create', 'FederationController@getFederationCreateView')->name('createfederationview');
        Route::get('/admin/federations/update/{name}', 'FederationController@getUpdateFederationView')->name('updatefederationview');
        Route::post('/admin/federations/update/{name}', 'FederationController@update')->name('updatefederation');
        Route::post('/admin/federations/create', 'FederationController@create')->name('createfederation');

        Route::get('/admin/rounds', 'RoundController@getRoundsView')->name('rounds');
        Route::get('/admin/rounds/create', 'RoundController@getRoundCreateView')->name('createroundview');
        Route::get('/admin/rounds/update/{name}', 'RoundController@getUpdateRoundView')->name('updateroundview');
        Route::post('/admin/rounds/update/{name}', 'RoundController@update')->name('updateround');
        Route::post('/admin/rounds/create', 'RoundController@create')->name('createround');

        Route::get('/admin/clubs', 'ClubController@getClubView')->name('clubs');
        Route::get('/admin/clubs/create', 'ClubController@getClubCreateView')->name('createclubview');
        Route::get('/admin/clubs/update/{name}', 'ClubController@getUpdateClubView')->name('updateclubview');
        Route::post('/admin/clubs/update/{name}', 'ClubController@update')->name('updateclub');
        Route::post('/admin/clubs/create', 'ClubController@create')->name('createclub');

    });
});
