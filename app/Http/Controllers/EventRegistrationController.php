<?php

namespace App\Http\Controllers;

use App\EventEntry;
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
use Illuminate\Support\Facades\Validator;

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
        $eventregistration = EventEntry::where('eventid', $request->eventid)->where('userid', Auth::id())->get()->first();

        if (is_null($eventregistration)) {
            return Redirect::route('eventdetails', $request->eventid)->with('failure', 'Unable to find registration, please contact ArcheryOSA Admin');
        }

        $event = Event::where('eventid', urlencode($request->eventid))->get()->first();
        $lc_eventrounds = EventRound::where('eventid', $event->eventid)->get();

        $divisions = Division::whereIn('divisionid', $this->processEventRoundDivisions($lc_eventrounds))->get(); // collection array of divisions
        $clubs = Club::where('organisationid', $event->organisationid)->get();

        $organisationname = Organisation::where('organisationid', $event->organisationid)->pluck('name')->first();

        if (is_null($organisationname)) {
            $organisationname = '';
        }

        return view('auth.events.registration.update_register_events', compact('event', 'eventregistration', 'lc_eventrounds', 'clubs', 'divisions', 'organisationname'));

    }






    public function eventRegister(Request $request)
    {
        Validator::make($request->all(), [
            'name' => 'required',
            'clubid' => 'required',
            'email' => 'required',
            'divisionid' => 'required',
        ], [
            'divisionid.required' => 'Division is required'
        ])->validate();

        $alreadyentered = EventEntry::where('userid', Auth::id())->where('eventid', $request->eventid)->get()->first();

        if (!is_null($alreadyentered)) {
            return Redirect::route('eventdetails', $request->eventid)->with('failure', 'Already Registered.');
        }

        $evententry = new EventEntry();

        $evententry->fullname = htmlentities($request->input('name'));
        $evententry->userid = Auth::id();
        $evententry->clubid = htmlentities($request->input('clubid'));
        $evententry->email = htmlentities($request->input('email'));
        $evententry->divisionid = htmlentities($request->input('divisionid'));
        $evententry->membershipcode = htmlentities($request->input('membershipcode'));
        $evententry->enteredbyuserid = Auth::id(); // set the created by as the person who is logged in
        $evententry->phone = htmlentities($request->input('phone'));
        $evententry->address = htmlentities($request->input('address'));
        $evententry->entrystatusid = '1';
        $evententry->eventid = htmlentities($request->eventid);

        $evententry->save();


        $eventname = Event::where('eventid', $request->eventid)->pluck('name')->first();
        $this->sendEventEntryConfirmation($eventname);



        return Redirect::route('eventdetails', $request->eventid)->with('message', 'Registration Successful');
    }

    public function updateEventRegistration(Request $request)
    {
        Validator::make($request->all(), [
            'name' => 'required',
            'clubid' => 'required',
            'email' => 'required',
            'divisionid' => 'required',
        ], [
            // custom messages
        ])->validate();

        $evententry = EventEntry::where('userid', Auth::id())->where('eventid', $request->eventid)->get()->first();

        if (is_null($evententry)) {
            $evententry = new EventEntry();
        }


        $evententry->fullname = htmlentities($request->input('name'));
        $evententry->userid = Auth::id();
        $evententry->clubid = htmlentities($request->input('clubid'));
        $evententry->email = htmlentities($request->input('email'));
        $evententry->divisionid = htmlentities($request->input('divisionid'));
        $evententry->membershipcode = htmlentities($request->input('membershipcode'));
        $evententry->enteredbyuserid = Auth::id(); // set the created by as the person who is logged in
        $evententry->phone = htmlentities($request->input('phone'));
        $evententry->address = htmlentities($request->input('address'));
        $evententry->eventid = htmlentities($request->eventid);
        $evententry->entrystatusid = $evententry->entrystatusid ?? '1';

        $evententry->save();



        return Redirect::route('eventdetails', $request->eventid)->with('message', 'Update Successful');

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

    public function updateEventEntryStatus(Request $request)
    {

        $userids = $request->input('userid');
        $userstatus = $request->input('userstatus');
        $userpaid = $request->input('userpaid');

        for ($i = 0; $i < count($userids); $i++) {
            $evententry = EventEntry::where('userid', $userids[$i])->where('eventid', $request->eventid)->get()->first();

            if (is_null($evententry)) {
                continue;
            }

            $evententry->paid = intval($userpaid[$i]) ?: 0;
            $evententry->entrystatusid = intval($userstatus[$i]) ?: 0;
            $evententry->save();
        }

        return Redirect::route('updateevent', $request->eventid)->with('message', 'Update Successful');
    }

    private function sendEventEntryConfirmation($eventname)
    {
        Mail::to(Auth::user()->email)
            ->send(new EntryConfirmation(ucwords($eventname)));
    }
}

