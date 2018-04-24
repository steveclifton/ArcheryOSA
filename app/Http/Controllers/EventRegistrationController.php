<?php

namespace App\Http\Controllers;

use App\EventEntry;
use App\Http\Requests\Events\EventRegisterValidator;
use App\Http\Requests\Events\UpdateEventRegisterValidator;
use App\Mail\EntryConfirmation;
use App\Organisation;
use App\Round;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\Club;
use App\Division;
use App\Event;
use App\EventRound;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;

class EventRegistrationController extends Controller
{

    public function getAddUserView(Request $request)
    {
        //dd($request->eventid, $request->eventhash);

        $event = Event::where('eventid', $request->eventid)
                        ->where('hash', $request->eventhash)
                        ->get()
                        ->first();

        if (empty($event)) {
            return back()->with('failure', 'Invalid Request');
        }


        $eventround = EventRound::where('eventid', $event->eventid)->get();

        $divArr = unserialize($event->divisions);


        $divisions = Division::whereIn('divisionid', $divArr)->orderBy('name', 'asc')->get(); // collection array of divisions

        $clubs = Club::orderby('name')->get();

        $organisationids = DB::select("SELECT `membershipcode`
                                            FROM `usermemberships`
                                            WHERE `userid` = " . Auth::user()->userid . "
                                            AND `organisationid` = '". $event->organisationid ."'
                                            LIMIT 1
                                        ");

        $userorgid = $organisationids[0]->membershipcode ?? ''; // set the userorganisationid to be the return or an empty string

        $organisationname = Organisation::where('organisationid', $event->organisationid)->pluck('name')->first();

        if (is_null($organisationname)) {
            $organisationname = '';
        }

        return view('auth.events.registration.manual_adduser', compact('event', 'eventround', 'clubs', 'divisions', 'userorgid', 'organisationname'));

    }

    public function adminAddUser(Request $request)
    {
        if (empty($request->eventid)) {
            return redirect()->route('/');
        }

        $request->validate([
            'eventid' => 'required',
            'enteredbyuserid' => 'required',
            'name' => 'required',
            'divisions' => 'required',
            'eventroundid' => 'required'
        ], [
            'name.required' => 'Please enter the Archer\'s name',
            'divisions.required' => 'Please select a division',
            'eventroundid.required' => 'Please select a round'
        ]);


        if ($request->input('userid') != -1) {
            // create an event entry with the users details
            $user = User::where('userid', $request->input('userid'))->get()->first();
            if (empty($user)) {
                return back()->with('failure', 'User not found. Please contact ArcheryOSA');
            }

            $alreadyentered = EventEntry::where('userid', $user->userid)
                ->where('eventid', $request->eventid)
                ->wherein('eventroundid', $request->input('eventroundid'))
                ->get()
                ->first();

            if (!empty($alreadyentered)) {
                return back()->with('failure', 'User already Entered');
            }
        } else {
            // create new user
            $user = new User();
            list($first, $last) = explode(' ', $request->input('name'));
            $user->firstname = !empty($first) ? $first : '';
            $user->lastname = !empty($last) ? $last : '';
            $user->username = $first . $last;
            $user->usertype = '3';
            $user->email = substr(md5(time()),0,20);
            $user->username = strtolower(preg_replace("/[^a-zA-Z0-9]/", "", $user->username)) . rand(1,1440);
            $user->save();

        }


        foreach ($request->input('eventroundid') as $eventroundid) {

            $evententry = new EventEntry();
            $evententry->fullname = $request->input('name');
            $evententry->userid = $user->userid;
            $evententry->email = $user->email;
            $evententry->divisionid = $request->input('divisions');
            $evententry->enteredbyuserid = Auth::id(); // set the created by as the person who is logged in
            $evententry->entrystatusid = '1';
            $evententry->eventid = $request->eventid;
            $evententry->eventroundid = $eventroundid;
            $evententry->membershipcode = '';
            $evententry->hash = substr(md5(time()),0,10);
            $evententry->gender = in_array($request->input('gender'), ['M','F']) ? $request->input('gender') : '';
            $evententry->save();
        }
        return back()->with('message', 'Archer Added');
    }

    public function getRegisterForEventView(Request $request)
    {
        $event = Event::where('eventid', urlencode($request->eventid))->get()->first();

        if (empty($event)) {
            return Redirect::route('home');
        }
        $eventround = EventRound::where('eventid', $event->eventid)->get();

        $divArr = unserialize($event->divisions);


        $divisions = Division::whereIn('divisionid', $divArr)->orderBy('name', 'asc')->get(); // collection array of divisions
//        dd($divisions);

        $clubs = Club::orderby('name')->get();

        $organisationids = DB::select("SELECT `membershipcode`
                                            FROM `usermemberships`
                                            WHERE `userid` = " . Auth::user()->userid . "
                                            AND `organisationid` = '". $event->organisationid ."'
                                            LIMIT 1
                                        ");

        $userorgid = $organisationids[0]->membershipcode ?? ''; // set the userorganisationid to be the return or an empty string

        $organisationname = Organisation::where('organisationid', $event->organisationid)->pluck('name')->first();

        if (is_null($organisationname)) {
            $organisationname = '';
        }

//        dd($eventround);
        return view('auth.events.registration.register_events', compact('event', 'eventround', 'clubs', 'divisions', 'userorgid', 'organisationname'));
    } // getRegisterForEventView

    public function getUpdateEventRegistrationView(Request $request)
    {

        $eventregistration = EventEntry::where('eventid', $request->eventid)
                                        ->where('userid', Auth::id())
                                        ->get();





        if (is_null($eventregistration)) {
            return Redirect::route('eventdetails', urlencode($request->name))->with('failure', 'Unable to find registration, please contact ArcheryOSA Admin');
        }

        $userdivisions = [];
        foreach ($eventregistration as $registration) {
            $userdivisions[] = $registration->divisionid;
        }

        $usereventrounds = [];
        foreach ($eventregistration as $registration) {
            $usereventrounds[] = $registration->eventroundid;
        }



        $event = Event::where('eventid', urlencode($request->eventid))->get()->first();
        $eventrounds = EventRound::where('eventid', $event->eventid)->get();

        $divArr = unserialize($event->divisions);
        $divisions = Division::whereIn('divisionid', $divArr)->orderBy('name', 'asc')->get(); // collection array of divisions
        $clubs = Club::orderby('name')->get();

        $organisationname = Organisation::where('organisationid', $event->organisationid)->pluck('name')->first();

        if (is_null($organisationname)) {
            $organisationname = '';
        }

        return view('auth.events.registration.update_register_events', compact('event', 'eventregistration', 'eventrounds', 'clubs', 'divisions', 'organisationname', 'userdivisions', 'usereventrounds'));

    } // getUpdateEventRegistrationView

    public function eventRegister(EventRegisterValidator $request)
    {

        $event = Event::where('eventid', $request->eventid)->where('name', urldecode($request->eventname))->get()->first();

        if (is_null($event)) {
            return back()->with('failure', 'Registration Failed, please contact archeryosa@gmail.com');
        }

        /* non league */
        if ($event->multipledivisions == 0) {

            $alreadyentered = EventEntry::where('userid', Auth::id())
                ->where('eventid', $request->eventid)
                ->wherein('eventroundid', $request->input('eventroundid'))
                ->get()
                ->first();

            if (!is_null($alreadyentered)) {
                return back()->with('failure', 'Registration Failed, please contact archeryosa@gmail.com');
            }

            // loop through each event round they are trying to enter and enter them

            foreach ($request->input('eventroundid') as $eventroundid) {
                $evententry = new EventEntry();
                $evententry->fullname = $request->input('name');
                $evententry->userid = Auth::id();
                $evententry->clubid = $request->input('clubid');
                $evententry->email = $request->input('email');
                $evententry->divisionid = $request->input('divisions');
                $evententry->membershipcode = $request->input('membershipcode');
                $evententry->enteredbyuserid = Auth::id(); // set the created by as the person who is logged in
                $evententry->phone = $request->input('phone');
                $evententry->address = $request->input('address');
                $evententry->notes = $request->input('notes');
                $evententry->entrystatusid = '1';
                $evententry->eventid = $request->eventid;
                $evententry->eventroundid = $eventroundid;
                $evententry->gender = in_array($request->input('gender'), ['M','F']) ? $request->input('gender') : '';
                $evententry->hash = substr(md5(time()),0,10);
                $evententry->save();

            }



        } else {

            /* league processing */
            $evententry = $this->league_eventRegister($request);


            if (!$evententry) {
                return back()->with('failure', 'Registration Failed, please contact archeryosa@gmail.com');
            }

        }


        $this->touchurl('sendregistrationemail/' . $evententry->userid . '/' . $evententry->evententryid . '/' . $evententry->hash);

        return Redirect::route('eventdetails', ['name' => $request->eventname])->with('message', 'Registration Successful');

    } // eventRegister

    private function league_eventRegister($request)
    {

        foreach ($request->input('divisions') as $division) {

            $alreadyentered = EventEntry::where('userid', Auth::id())
                ->where('eventid', $request->eventid)
                ->where('divisionid', $division)
                ->get()
                ->first();

            if (!is_null($alreadyentered)) {
                return back()->with('failure', 'Registration Failed, please contact archeryosa@gmail.com');
            }


            $evententry = new EventEntry();

            $evententry->fullname = $request->input('name');
            $evententry->userid = Auth::id();
            $evententry->clubid = $request->input('clubid');
            $evententry->email = $request->input('email');
            $evententry->divisionid = $division;
            $evententry->membershipcode = $request->input('membershipcode');
            $evententry->enteredbyuserid = Auth::id(); // set the created by as the person who is logged in
            $evententry->phone = $request->input('phone');
            $evententry->address = $request->input('address');
            $evententry->notes = html_entity_decode($request->input('notes'));
            $evententry->hash = substr(md5(time()),0,10);
            $evententry->entrystatusid = '1';
            $evententry->eventid = $request->eventid;
            $evententry->eventroundid = $request->input('eventroundid');
            $evententry->gender = in_array($request->input('gender'), ['M','F']) ? $request->input('gender') : '';

            $evententry->save();
        }

        return $evententry;
    } // league_eventRegister

    public function updateEventRegistration(UpdateEventRegisterValidator $request)
    {

        if (is_null(Event::where('eventid', $request->eventid)->where('name', urldecode($request->eventname))->get()->first())) {
            return back()->with('failure', 'Registration Failed, please contact archeryosa@gmail.com');
        }

        $event = Event::where('eventid', $request->eventid)
                        ->get()
                        ->first();


        if ($request->input('submit') == 'remove') {
            $this->deleteUserEntry($request);
            // Send email to confirm removing entry
            return Redirect::route('eventdetails', ['name' => $request->eventname])->with('message', 'Entry removed from event');

        } else if ($event->multipledivisions == 0) {
            // Single entry comp
            $this->singleEntryUpdate($request);

            return redirect()->back()->withInput()->with('message', 'Update Successful');

        } else {
            // Multiple entry comp
            $this->multipleEntryUpdate($request);
            return redirect()->back()->withInput()->with('message', 'Update Successful');
        }

    } // updateEventRegistration

    public function updateEventEntryStatus(Request $request)
    {

        $userids = $request->input('userid');
        $userstatus = $request->input('userstatus');
        $userpaid = $request->input('userpaid');
        $userdivisionid = $request->input('divisionid');

        for ($i = 0; $i < count($userids); $i++) {
            $evententry = EventEntry::where('userid', $userids[$i])
                                    ->where('eventid', $request->eventid)
                                    ->where('divisionid', $userdivisionid[$i])
                                    ->get();



            if (empty($evententry)) {
                continue;
            }


            foreach ($evententry as $event) {

                $waseventstatus = intval($event->entrystatusid);
                $event->paid = intval($userpaid[$i]) ?: 0;
                $event->entrystatusid = intval($userstatus[$i]) ?: 0;

                if ($waseventstatus == 1 && intval($userstatus[$i] == 2)) {
                    $this->touchurl('sendconfirmationemail/' . $event->userid . '/' . $event->evententryid . '/' . $event->hash);
                    $event->confirmationemail = 1;
                }

                $event->save();

            }


        }
        

        return Redirect::route('updateevent', $request->eventid)->with('message', 'Update Successful');
    } // updateEventEntryStatus

    private function singleEntryUpdate($request)
    {

        // get all the rounds, if any is missing , delete it
        $userentry = EventEntry::where('userid', $request->userid)
            ->where('eventid', $request->eventid)
            ->get();

        if (empty($userentry)) {
            return false;
        } else {
            $hash = '';
            // These are rounds that are already in the database
            $existingroundids = [];
            foreach ($userentry as $entry) {
                $existingroundids[$entry->eventroundid] = $entry->eventroundid;
                $hash = $entry->hash;
            }
            // Create a new array that has the new ones
            $newroundids = [];
            foreach ($request->input('eventroundid') as $entryid) {
                $newroundids[$entryid] = intval($entryid);
            }


            // add those that need to be added
            foreach (array_diff($newroundids, $existingroundids) as $add) {
                $this->createEntry($request, $add, $hash);
            }
            // remove those that need to be deleted
            foreach(array_diff($existingroundids, $newroundids) as $delete) {
                $this->deleteUserEventRound($request->userid, $request->eventid, $delete);
            }


            // need to find rounds that have been unticked - IE removed
            foreach ($userentry as $entry) {
                // update anything that is needed
                $entry->fullname = htmlentities($request->input('name'));
                $entry->clubid = htmlentities($request->input('clubid'));
                $entry->email = htmlentities($request->input('email'));
                $entry->divisionid = htmlentities($request->input('divisions'));
                $entry->membershipcode = htmlentities($request->input('membershipcode'));
                $entry->enteredbyuserid = Auth::id(); // set the created by as the person who is logged in
                $entry->phone = htmlentities($request->input('phone'));
                $entry->address = htmlentities($request->input('address'));
                $entry->notes = html_entity_decode($request->input('notes'));
                $entry->gender = in_array($request->input('gender'), ['M','F']) ? $request->input('gender') : '';
                $entry->fullname = htmlentities($request->input('name'));

                $entry->save();
            }
        }
    } // singleEntryUpdate

    private function multipleEntryUpdate($request)
    {

        // current divisions
        $currentdivisions = EventEntry::where('userid', Auth::id())
            ->where('eventid', $request->eventid)
            ->pluck('divisionid')
            ->toArray();

        // loop through those that are not in the current request (as they have been unticked) and delete them
        foreach (array_diff($currentdivisions, $request->divisions) as $division) {

            $userentry = EventEntry::where('userid', Auth::id())
                                    ->where('eventid', $request->eventid)
                                    ->where('divisionid', $division)
                                    ->get()
                                    ->first();

            if (!is_null($userentry)) {
                $userentry->delete();
            }
        }

        // to here we have removed the existing entries , not find them and update/create
        foreach ($request->divisions as $division) {

            $userentry = EventEntry::where('userid', Auth::id())
                                    ->where('eventid', $request->eventid)
                                    ->where('divisionid', $division)
                                    ->get()
                                    ->first();

            if (is_null($userentry)) {
                // create a new one here
                $evententry = new EventEntry();

                $evententry->fullname = htmlentities($request->input('name'));
                $evententry->userid = Auth::id();
                $evententry->clubid = htmlentities($request->input('clubid'));
                $evententry->email = htmlentities($request->input('email'));
                $evententry->divisionid = htmlentities($division);
                $evententry->membershipcode = htmlentities($request->input('membershipcode'));
                $evententry->enteredbyuserid = Auth::id(); // set the created by as the person who is logged in
                $evententry->phone = htmlentities($request->input('phone'));
                $evententry->address = htmlentities($request->input('address'));
                $evententry->notes = html_entity_decode($request->input('notes'));
                $evententry->entrystatusid = '1';
                $evententry->eventid = htmlentities($request->eventid);

                $evententry->save();



            } else {
                // exisiting, update timestamp
                $userentry->save();
            }
        }


    } // multipleEntryUpdate

    private function deleteUserEntry($request)
    {
        $userentries = EventEntry::where('userid', $request->userid)
                    ->where('eventid', $request->eventid)
                    ->delete();

    } // deleteUserEntry

    private function deleteUserEventRound($userid, $eventid, $roundid)
    {

        EventEntry::where('userid', $userid)
            ->where('eventid', $eventid)
            ->where('eventroundid', $roundid)
            ->delete();

    } // deleteUserEventRound

    private function createEntry($request, $eventroundid, $hash)
    {


        $evententry = new EventEntry();
        $evententry->fullname = $request->input('name');
        $evententry->userid = $request->input('userid');
        $evententry->clubid = $request->input('clubid');
        $evententry->email = $request->input('email');
        $evententry->divisionid = $request->input('divisions');
        $evententry->membershipcode = $request->input('membershipcode');
        $evententry->enteredbyuserid = Auth::id(); // set the created by as the person who is logged in
        $evententry->phone = $request->input('phone');
        $evententry->address = $request->input('address');
        $evententry->notes = $request->input('notes');
        $evententry->entrystatusid = '1';
        $evententry->eventid = $request->eventid;
        $evententry->eventroundid = $eventroundid;
        $evententry->gender = in_array($request->input('gender'), ['M','F']) ? $request->input('gender') : '';
        $evententry->hash = $hash;
        $evententry->save();

    } // createEntry


}

