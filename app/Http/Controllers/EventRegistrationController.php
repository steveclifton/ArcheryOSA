<?php

namespace App\Http\Controllers;

use App\ArcherRelation;
use App\EventEntry;
use App\Http\Requests\Events\EventRegisterValidator;
use App\Jobs\SendEventEntryConfirmationEmail;
use App\Jobs\SendEventEntryEmail;
use App\Organisation;
use App\Round;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Club;
use App\Division;
use App\Event;
use App\EventRound;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

class EventRegistrationController extends Controller
{

    private function sendEventRegisterEmail($email, $eventname)
    {

        // check that it is a valid email address and not a placeholder one
        if ( filter_var($email, FILTER_VALIDATE_EMAIL ) ) {
            $this->dispatch(new SendEventEntryEmail($email, $eventname));
            return true;
        }
        return false;
    }

    private function sendEventRegisterConfirmation($email, $firstname, $eventname, $eventurl)
    {
        // check that it is a valid email address and not a placeholder one
        if ( filter_var($email, FILTER_VALIDATE_EMAIL ) ) {
            $this->dispatch(new SendEventEntryConfirmationEmail($email, $firstname, $eventname, $eventurl));
            return true;
        }
        return false;

    }

    public function getAddUserView(Request $request)
    {
        $eventid = $this->geteventurlid($request->eventurl);

        if (empty($eventid) && !is_int($eventid)) {
            return Redirect::route('home');
        }

        $event = Event::where('eventid', $eventid)
                        ->get()
                        ->first();

        $eventround = EventRound::where('eventid', $event->eventid)->get();

        if (empty($event) || $eventround->isEmpty()) {
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
            @list($first, $last) = explode(' ', $request->input('name'));
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

    /**
     * GET
     * Returns the view for entering a competition
     */
    public function getRegisterForEventView(Request $request)
    {
        $eventid = $this->geteventurlid($request->eventurl);

        if (empty($eventid) && !is_int($eventid)) {
            return Redirect::route('home');
        }

        $event = Event::where('eventid', $eventid)
                        ->get()
                        ->first();

        if (empty($event)) {
            return Redirect::route('home');
        }


        $data = $this->getRegisterEventDetails($event->eventid, Auth::id() );

        // Add relations to the array
        $data['relations'] = DB::select("SELECT u.*
            FROM `userrelationships` ur
            JOIN `users` u ON (ur.`relationuserid` = u.`userid`)
            WHERE ur.`userid` = :userid
        ", ['userid' => Auth::id()]
        );

        return view ('auth.events.registration.register_events', $data);

    } // getRegisterForEventView


    /**
     * Returns the information required for a users event registration form
     */
    public function getRegisterEventDetails($eventid, $userid)
    {
        $event = Event::where('eventid', $eventid)->get()->first();

        if (empty($event)) {
            return false;
        }

        $eventround = EventRound::where('eventid', $event->eventid)->get();
        $divArr = unserialize($event->divisions);
        $divisions = Division::whereIn('divisionid', $divArr)->orderBy('name', 'asc')->get();
        $clubs = Club::orderby('name')->get();

        $organisationids = DB::select("SELECT `membershipcode`
                                            FROM `usermemberships`
                                            WHERE `userid` = :userid
                                            AND `organisationid` = :eventid
                                            LIMIT 1
                                        ", ['userid' => $userid, 'eventid' => $eventid]);

        $userorgid = $organisationids[0]->membershipcode ?? ''; // set the userorganisationid to be the return or an empty string

        $organisationname = Organisation::where('organisationid', $event->organisationid)->pluck('name')->first();
        if (empty($organisationname)) {
            $organisationname = '';
        }

        $archerentry = $this->getArchersEntry($event->eventid, $userid);


        return compact('event', 'eventround', 'divisions', 'clubs', 'userorgid', 'organisationname', 'archerentry');

    } // getRegisterEventDetails






    /**
     * POST
     * Creates the event entry
     */
    public function eventRegister(EventRegisterValidator $request)
    {

        if ($request->input('submit') == 'remove') {
            $this->deleteUserEntry($request->input('userid'), $request->input('eventid'));
            return back()->with('message', 'Entry Removed');
        }

        $eventid = $this->geteventurlid($request->eventurl);

        if (empty($eventid) && !is_int($eventid)) {
            return Redirect::route('home');
        }

        $event = Event::where('eventid', $eventid)
                        ->where('visible', 1)
                        ->get()
                        ->first();

        if (empty($event)) {
            return back()->with('failure', 'Registration Failed, please contact archeryosa@gmail.com');
        }

        // Make sure they are able to register this event and user
        if ($request->input('userid') != Auth::id()) {
            $userrelation = ArcherRelation::where('userid', Auth::id())
                ->where('relationuserid', $request->input('userid'))
                ->get()
                ->first();

            if (empty($userrelation)) {
                return back()->with('failure', 'Registration Failed, please contact archeryosa@gmail.com');
            }
        }



        /* non league */
        if ($event->eventtype == 0 && $event->multipledivisions == 0) {
            // Multiple entry comp

            $evententry = $this->singleEntryUpdate($request, $event->eventid);

            if (empty($evententry)){
                return redirect()->back()->withInput()->with('failure', 'Please check entry and try again');
            }
        }
        /* league processing */
        else {

            $evententry = $this->league_eventRegister($request, $event->eventid);
            if (empty($evententry)) {
                return back()->with('key', 'Entry Removed');
            }
        }

        $this->sendEventRegisterEmail($evententry->email ?? 'none', $event->name);

        return redirect()->back()->withInput()->with('message', 'Registration Successful');

    } // eventRegister

    /**
     * Registers a user for a league event
     */
    private function league_eventRegister($request, $eventid)
    {

        if ($request->input('submit') == 'remove') {
            $this->deleteUserEntry($request->input('userid'), $eventid);
            return false;
        }

        foreach ($request->input('divisions') as $division) {

            $alreadyentered = EventEntry::where('userid', $request->input('userid'))
                ->where('eventid', $eventid)
                ->where('divisionid', $division)
                ->get()
                ->first();

            if (!empty($alreadyentered)) {
                continue;
            }

            if (!empty($request->input('dateofbirth'))) {
                $dateofbirth = Carbon::createFromFormat('d/m/Y', $request->input('dateofbirth'));
                $request->replace(['dateofbirth' => $dateofbirth]);
            }
            $eventround = EventRound::where('eventid', $eventid)->pluck('eventroundid')->first();
            $evententry = new EventEntry();
            $evententry->fullname = $request->input('name');
            $evententry->userid = $request->input('userid');
            $evententry->clubid = $request->input('clubid');
            $evententry->email = $request->input('email');
            $evententry->divisionid = $division;
            $evententry->membershipcode = $request->input('membershipcode');
            $evententry->enteredbyuserid = Auth::id(); // set the created by as the person who is logged in
            $evententry->phone = $request->input('phone');
            $evententry->address = $request->input('address');
            $evententry->notes = html_entity_decode($request->input('notes'));
            $evententry->hash = substr(md5(time()), 0, 10);
            $evententry->entrystatusid = '1';
            $evententry->eventid = $eventid;
            $evententry->eventroundid = $eventround ?? '';
            $evententry->gender = in_array($request->input('gender'), ['M','F']) ? $request->input('gender') : '';
            $evententry->dateofbirth = !empty($request->input('dateofbirth')) ? $request->input('dateofbirth') : NULL;

            $evententry->save();

        } // foreach

        $evententry = EventEntry::where('userid', $request->input('userid'))
                        ->where('eventid', $eventid)
                        ->get()
                        ->first();

        return $evententry;

    } // league_eventRegister



    /**
     * POST
     * Updates an events entry status's
     */
    public function updateEventEntryStatus(Request $request)
    {

        $userids = $request->input('userid');
        $userstatus = $request->input('userstatus');
        $userpaid = $request->input('userpaid');
        $userdivisionid = $request->input('divisionid');

        // define the event here, set the obj in the foreach loop
        $event = null;

        for ($i = 0; $i < count($userids); $i++) {
            $evententry = EventEntry::where('userid', $userids[$i])
                                    ->where('eventid', $request->eventid)
                                    ->where('divisionid', $userdivisionid[$i])
                                    ->get();

            if (empty($evententry)) {
                continue;
            }



            foreach ($evententry as $ee) {

                // Set the event on the first occurance of the loop
                if (empty($event)){
                    $event = Event::where('eventid', $ee->eventid)->get()->first();
                }

                $waseventstatus = intval($ee->entrystatusid);
                $ee->paid = intval($userpaid[$i]) ?: 0;
                $ee->entrystatusid = intval($userstatus[$i]) ?: 0;

                if ($waseventstatus == 1 && intval($userstatus[$i] == 2)) {

                    $this->sendEventRegisterConfirmation($ee->email, $ee->fullname, $event->name ?? '', $event->url ?? '');

                    $ee->confirmationemail = 1;
                }

                $ee->save();

            }


        }
        

        return Redirect::route('updateevent', $request->eventid)->with('message', 'Update Successful');
    } // updateEventEntryStatus




    /*****************************************************
     *                PRIVATE METHODS                    *
     *****************************************************/

    /**
     * Creates a Single Entry
     */
    public function createEntry($request, $eventroundid, $hash)
    {
        $dateofbirth = NULL;
        if (!empty($request->input('dateofbirth'))) {
            $dateofbirth = Carbon::createFromFormat('d/m/Y', $request->input('dateofbirth'));
        }

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
        $evententry->dateofbirth = $dateofbirth;

        $evententry->save();

        return $evententry;

    } // createEntry

    /**
     * CREATES as well as updates a Single Entry
     */
    public function singleEntryUpdate($request, $eventid)
    {

        // get all the rounds, if any is missing , delete it
        $userentry = EventEntry::where('userid', $request->userid)
                                ->where('eventid', $eventid)
                                ->get();


        $hash = '';
        // These are rounds that are already in the database
        $existingroundids = [];
        foreach ($userentry as $entry) {
            $existingroundids[$entry->eventroundid] = $entry->eventroundid;
            $hash = !empty($entry->hash) ? $entry->hash : '';
        }
        if (empty($hash)){
            $hash = $this->createHash();
        }

        if (empty($request->input('eventroundid'))) {
            return false;
        }



        // Create a new array that has the new ones
        $newroundids = [];
        foreach ($request->input('eventroundid') as $entryid) {
            $newroundids[$entryid] = intval($entryid);
        }

        // add those that need to be added
        foreach (array_diff($newroundids, $existingroundids) as $add) {
            $entry = $this->createEntry($request, $add, $hash);
        }

        // remove those that need to be deleted
        foreach(array_diff($existingroundids, $newroundids) as $delete) {
            $this->deleteUserEventRound($request->userid, $request->eventid, $delete);
        }

        $dateofbirth = NULL;
        if (!empty($request->input('dateofbirth'))) {
            $dateofbirth = Carbon::createFromFormat('d/m/Y', $request->input('dateofbirth'));
        }


        // need to find rounds that have been unticked - IE removed
        foreach ($userentry as $entry) {
            // update anything that is needed
            $entry->fullname = $request->input('name');
            $entry->clubid = $request->input('clubid');
            $entry->email = $request->input('email');
            $entry->divisionid = $request->input('divisions');
            $entry->membershipcode = $request->input('membershipcode');
            $entry->enteredbyuserid = Auth::id(); // set the created by as the person who is logged in
            $entry->phone = $request->input('phone');
            $entry->address = $request->input('address');
            $entry->notes = $request->input('notes');
            $entry->gender = in_array($request->input('gender'), ['M','F']) ? $request->input('gender') : '';
            $entry->fullname = $request->input('name');
            $entry->hash = $hash;
            $entry->dateofbirth = $dateofbirth;

            $entry->save();
        } // foreach

        return $entry;

    } // singleEntryUpdate

    /**
     * Updates a Multiple Entry
     */
    public function multipleEntryUpdate($request)
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

            if (empty($userentry)) {
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

            }
            else {
                // exisiting, update timestamp
                $userentry->save();
            }
        }

        return true;
    } // multipleEntryUpdate

    /**
     * Deletes a users entry to the whole competition
     */
    public function deleteUserEntry($userid, $eventid)
    {
        return EventEntry::where('userid', $userid)
                    ->where('eventid', $eventid)
                    ->delete();

    } // deleteUserEntry

    /**
     * Deletes a users SINGLE entry to the whole competition
     */
    public function deleteUserEventRound($userid, $eventid, $roundid)
    {
        return EventEntry::where('userid', $userid)
            ->where('eventid', $eventid)
            ->where('eventroundid', $roundid)
            ->delete();

    } // deleteUserEventRound


    public function updateTargetAllocation(Request $request)
    {
        if ( empty($request->input('eventid')) || empty($request->input('name'))) {
            return redirect()->back()->with('failure', 'Target Allocations failed, please contact ArcheryOSA');
        }

        $event = Event::where('eventid', $request->input('eventid'))
                                ->get()
                                ->first();


        if (empty($event)) {
            return redirect()->back()->with('failure', 'Update failed, please contact ArcheryOSA');
        }

        foreach ($request->input('name') as $key => $ta) {
            $evententry = EventEntry::where('evententryid', $key)
                                        ->get()
                                        ->first();

            if (!empty($evententry)) {
                $evententry->targetallocation = $ta;
                $evententry->save();
            }

        }

        return redirect()->back()->with('message', 'Update Succesfull');

    }



}

