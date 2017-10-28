<?php

namespace App\Http\Controllers;

use App\EventEntry;
use App\Http\Requests\Events\EventRegisterValidator;
use App\Http\Requests\Events\UpdateEventRegisterValidator;
use App\Mail\EntryConfirmation;
use App\Organisation;
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

    public function getRegisterForEventView(Request $request)
    {
        $event = Event::where('eventid', urlencode($request->eventid))->get()->first();

        if (is_null($event)) {
            return Redirect::route('home');
        }
        $eventround = EventRound::where('eventid', $event->eventid)->get();

        $divisions = Division::whereIn('divisionid', $this->processEventRoundDivisions($eventround))->get(); // collection array of divisions
        $clubs = Club::where('organisationid', $event->organisationid)->get();

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

        return view('auth.events.registration.register_events', compact('event', 'eventround', 'clubs', 'divisions', 'userorgid', 'organisationname'));
    }

    public function getUpdateEventRegistrationView(Request $request)
    {

        $eventregistration = EventEntry::where('eventid', $request->eventid)
                                        ->where('userid', Auth::id())
                                        ->get();

        if (is_null($eventregistration)) {
            return Redirect::route('eventdetails', $request->eventid)->with('failure', 'Unable to find registration, please contact ArcheryOSA Admin');
        }

        $userdivisions = [];

        foreach ($eventregistration as $registration) {
            $userdivisions[] = $registration->divisionid;
        }

        $event = Event::where('eventid', urlencode($request->eventid))->get()->first();
        $eventrounds = EventRound::where('eventid', $event->eventid)->get();

        $divisions = Division::whereIn('divisionid', $this->processEventRoundDivisions($eventrounds))->get(); // collection array of divisions
        $clubs = Club::where('organisationid', $event->organisationid)->get();

        $organisationname = Organisation::where('organisationid', $event->organisationid)->pluck('name')->first();

        if (is_null($organisationname)) {
            $organisationname = '';
        }

        return view('auth.events.registration.update_register_events', compact('event', 'eventregistration', 'eventrounds', 'clubs', 'divisions', 'organisationname', 'userdivisions'));

    }


    public function eventRegister(EventRegisterValidator $request)
    {

        if (is_null(Event::where('eventid', $request->eventid)->where('name', urldecode($request->eventname))->get()->first())) {
            return back()->with('failure', 'Registration Failed, please contact archeryosa@gmail.com');
        }

        // The input for divisions is stored as an array
        if (!is_array($request->input('divisions'))) {
            return back()->with('failure', 'Registration Failed, please contact archeryosa@gmail.com');
        }

        foreach ($request->input('divisions') as $division) {
            $alreadyentered = EventEntry::where('userid', Auth::id())
                ->where('eventid', $request->eventid)
                ->where('divisionid', $division)
                ->get()->first();

            if (!is_null($alreadyentered)) {
                return back()->with('failure', 'Registration Failed, please contact archeryosa@gmail.com');
            }


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
            $evententry->entrystatusid = '1';
            $evententry->eventid = htmlentities($request->eventid);

            $evententry->save();
        }


        $eventname = Event::where('eventid', $request->eventid)->pluck('name')->first();
        $this->sendEventEntryConfirmation($eventname);

        return Redirect::route('eventdetails', ['eventid' => $request->eventid, 'name' => $request->eventname])->with('message', 'Registration Successful');


    }

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
            return Redirect::route('eventdetails', ['eventid' => $request->eventid, 'name' => $request->eventname])->with('message', 'Entry removed from event');

        } else if ($event->multipledivisions == 0) {
            // Single entry comp
            $this->singleEntryUpdate($request);
            return Redirect::route('eventdetails', ['eventid' => $request->eventid, 'name' => $request->eventname])->with('message', 'Update Successful');

        } else {
            // Multiple entry comp
            $this->multipleEntryUpdate($request);
            return Redirect::route('eventdetails', ['eventid' => $request->eventid, 'name' => $request->eventname])->with('message', 'Update Successful');

        }

    }

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
                                    ->get()
                                    ->first();

            if (is_null($evententry)) {
                continue;
            }



            $evententry->paid = intval($userpaid[$i]) ?: 0;
            $evententry->entrystatusid = intval($userstatus[$i]) ?: 0;
            $evententry->save();
        }

        return Redirect::route('updateevent', $request->eventid)->with('message', 'Update Successful');
    }

    private function processEventRoundDivisions($eventRounds) : array
    {
        $la_divisions = [];
        foreach ($eventRounds as $eventRound) {
            $la_div = unserialize($eventRound->divisions);
            foreach ($la_div as $li_div) {
                $la_divisions[$li_div] = $li_div;
            }
        }
       return $la_divisions;
    }


    private function singleEntryUpdate($request)
    {

        $userentry = EventEntry::where('userid', Auth::id())
            ->where('eventid', $request->eventid)
            ->get()
            ->first();

        if (is_null($userentry)) {
            return false;
        } else {

            $division = $request->input('divisions');
            $userentry->fullname = htmlentities($request->input('name'));
            $userentry->clubid = htmlentities($request->input('clubid'));
            $userentry->email = htmlentities($request->input('email'));
            $userentry->divisionid = htmlentities(reset($division));
            $userentry->membershipcode = htmlentities($request->input('membershipcode'));
            $userentry->enteredbyuserid = Auth::id(); // set the created by as the person who is logged in
            $userentry->phone = htmlentities($request->input('phone'));
            $userentry->address = htmlentities($request->input('address'));

            $userentry->save();
        }

    }

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
            $evententry->entrystatusid = '1';
            $evententry->eventid = htmlentities($request->eventid);

            $evententry->save();


            } else {
                // exisiting, update timestamp
                $userentry->save();
            }
        }


    }

    private function deleteUserEntry($request)
    {
        $userentries = EventEntry::where('userid', Auth::id())
                    ->where('eventid', $request->eventid)
                    ->delete();

    }

    private function sendEventEntryConfirmation($eventname)
    {
        Mail::to(Auth::user()->email)
            ->send(new EntryConfirmation(ucwords($eventname)));
    }
}

