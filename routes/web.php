<?php



Route::get('/', 'HomeController@index')->name('home');
Route::get('/clubs', 'ClubController@PUBLIC_getViewClubs')->name('public.clubs');
Route::get('/upcomingevents', 'EventController@PUBLIC_getAllUpcomingEventsView')->name('upcomingevents');
Route::get('/previousevents', 'EventController@PUBLIC_getAllPreviousEventsView');
Route::get('/eventdetails/{eventid}/{name}', 'EventController@PUBLIC_getEventDetailsView')->name('eventdetails');
Route::get('/authorisearcherrelation/{hash}', 'UserController@authoriseUserRelationship')->name('authoriseuserrelationship');




Route::middleware(['web'])->group(function() {

	// Guest
	Route::middleware(['guest'])->group(function () {
		Route::get('/login', 'UserController@PUBLIC_getLoginView')->name('login');
		Route::post('/login', 'UserController@login');

		Route::get('/register', 'UserController@PUBLIC_getRegisterView')->name('register');
		Route::post('/register', 'UserController@register');

        Route::get('/resetpassword', 'PasswordResetController@PUBLIC_getPasswordResetView')->name('passwordresetview');
        Route::post('/resetpassword', 'PasswordResetController@resetpassword')->name('resetpassword');

        Route::post('/updatepassword', 'PasswordResetController@updatepassword')->name('updatepassword');
        Route::get('/updatepassword/{hash}', 'PasswordResetController@PUBLIC_getResetPasswordView');


    });

	// Auth
	Route::middleware(['auth'])->group(function () {
		Route::get('/profile', 'UserController@getProfileView')->name('profile');
		Route::get('/logout', 'UserController@logout')->name('logout');
        Route::post('/updateprofile', 'UserController@updateProfile')->name('updateprofile');

        Route::get('/updateprofile/addarcher', 'UserController@getCreateArcherRelationship')->name('createaddarcherview');
        Route::get('/updateprofile/addmembership', 'UserMembershipController@getCreateView')->name('createusermembershipview');
        Route::get('/updateprofile/updatemembership/{membershipcode}', 'UserMembershipController@getUpdateView')->name('updateusermembershipview');
        Route::get('/updateprofile/removearcherrelation/{hash}', 'UserController@removeUserRelationship')->name('removeuserrelation');

        Route::post('/updateprofile/addarcher/submit', 'UserController@createArcherRelationship')->name('createarcherrelationship');
        Route::post('/updateprofile/addorganisation/submit', 'UserMembershipController@create')->name('createusermembership');
        Route::post('/updateprofile/updateorganisation/submit/{usermembershipid}', 'UserMembershipController@update')->name('updateusermembership');


		Route::get('/event/register/{eventid}/{name}', 'EventRegistrationController@getRegisterForEventView')->name('eventregistrationview');
		Route::post('/event/register/{eventid}/{eventname}', 'EventRegistrationController@eventRegister')->name('eventregistration');


        Route::get('/event/registration/update/{eventid}', 'EventRegistrationController@getUpdateEventRegistrationView')->name('updateeventregistrationview');

        Route::post('/event/registration/update/{eventid}/{eventname}', 'EventRegistrationController@updateEventRegistration')->name('updateeventregistration');




    });

	// Admin
	Route::middleware(['admin'])->group(function () {

	    // Events
        Route::get('/admin/events', 'EventController@getEventsView')->name('events');
        Route::get('/admin/events/create', 'EventController@getCreateView')->name('createeventview');
        Route::get('/admin/events/update/{eventid}', 'EventController@getUpdateEventView')->name('updateeventview');
        Route::post('/admin/events/update/{eventid}', 'EventController@update')->name('updateevent');
        Route::post('/admin/events/create', 'EventController@create')->name('createevent');
        Route::get('/admin/events/delete/{eventid}/{eventname}', 'EventController@delete')->name('deleteevent');

        // Event Rounds
        Route::get('/admin/events/create/eventround/{eventid}', 'EventRoundController@getCreateEventRoundView')->name('createeventroundview');
        Route::post('/admin/events/create/eventround/', 'EventRoundController@create')->name('createeventround');
        Route::get('/admin/events/update/eventround/{eventroundid}', 'EventRoundController@getUpdateRoundEventView')->name('updateeventroundview');
        Route::post('/admin/events/update/eventround/{eventroundid}', 'EventRoundController@update')->name('updateeventround');
        Route::get('/admin/eventround/delete/{eventroundid}/{eventroundname}', 'EventRoundController@delete')->name('deleteeventround');

        Route::post('/admin/events/updateregistration/{eventid}', 'EventRegistrationController@updateEventEntryStatus')->name('updateregistrationstatus');

        // Divisions
        Route::get('/admin/divisions', 'DivisionController@getDivisionsView')->name('divisions');
        Route::get('/admin/divisions/create', 'DivisionController@getDivisionCreateView')->name('createdivisionview');
        Route::get('/admin/divisions/update/{name}', 'DivisionController@getUpdateDivisionView')->name('updatedivisionview');
        Route::post('/admin/divisions/update/{name}', 'DivisionController@update')->name('updatedivision');
        Route::post('/admin/divisions/create', 'DivisionController@create')->name('createdivision');
        Route::get('/admin/divisions/delete/{divisionid}/{divisionname}', 'DivisionController@delete')->name('deletedivision');

        // Organisations
        Route::get('/admin/organisations', 'OrganisationController@getOrganisationView')->name('organisations');
        Route::get('/admin/organisations/create', 'OrganisationController@getOrganisationCreateView')->name('createorganisationview');
        Route::get('/admin/organisations/update/{name}', 'OrganisationController@getUpdateOrganisationView')->name('updateorganisationview');
        Route::post('/admin/organisations/update/{name}', 'OrganisationController@update')->name('updateorganisation');
        Route::post('/admin/organisations/create', 'OrganisationController@create')->name('createorganisation');
        Route::get('/admin/organisations/delete/{organisationid}/{organisationname}', 'OrganisationController@delete')->name('deleteorganisation');

        // Federations
        Route::get('/admin/federations', 'FederationController@getFederationView')->name('federations');
        Route::get('/admin/federations/create', 'FederationController@getFederationCreateView')->name('createfederationview');
        Route::get('/admin/federations/update/{name}', 'FederationController@getUpdateFederationView')->name('updatefederationview');
        Route::post('/admin/federations/update/{name}', 'FederationController@update')->name('updatefederation');
        Route::post('/admin/federations/create', 'FederationController@create')->name('createfederation');

        // Rounds
        Route::get('/admin/rounds', 'RoundController@getRoundsView')->name('rounds');
        Route::get('/admin/rounds/create', 'RoundController@getRoundCreateView')->name('createroundview');
        Route::get('/admin/rounds/update/{name}', 'RoundController@getUpdateRoundView')->name('updateroundview');
        Route::post('/admin/rounds/update/{name}', 'RoundController@update')->name('updateround');
        Route::post('/admin/rounds/create', 'RoundController@create')->name('createround');
        Route::get('/admin/rounds/delete/{roundid}/{roundname}', 'RoundController@delete')->name('deleteround');

        // Clubs
        Route::get('/admin/clubs', 'ClubController@getClubView')->name('clubs');
        Route::get('/admin/clubs/create', 'ClubController@getClubCreateView')->name('createclubview');
        Route::get('/admin/clubs/update/{name}', 'ClubController@getUpdateClubView')->name('updateclubview');
        Route::post('/admin/clubs/update/{name}', 'ClubController@update')->name('updateclub');
        Route::post('/admin/clubs/create', 'ClubController@create')->name('createclub');
        Route::get('/admin/clubs/delete/{clubid}/{clubname}', 'ClubController@delete')->name('deleteclub');


    });
});
