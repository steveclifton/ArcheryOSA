<?php

namespace App\Http\Controllers;

use App\EventEntry;
use Illuminate\Support\Facades\Auth;
use App\Club;
use App\Division;
use App\Event;
use App\EventRound;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class EventRegistrationController extends Controller
{

    public function getRegisterForEventView(Request $request)
    {
        $lc_event = Event::where('eventid', urlencode($request->eventid))->get();
        $lc_eventrounds = EventRound::where('eventid', $lc_event->first()->eventid)->get();

        $lc_divisions = Division::whereIn('divisionid', $this->processEventRoundDivisions($lc_eventrounds))->get(); // collection array of divisions
        $lc_clubs = Club::where('organisationid', $lc_event->first()->organisationid)->get();

        $la_userorganisationid = DB::select("SELECT `membershipcode`
                                            FROM `usermemberships`
                                            WHERE `userid` = " . Auth::user()->userid . "
                                            AND `organisationid` = '". $lc_event->first()->organisationid ."'
                                            LIMIT 1
                                        ");

        $ls_userorgid = $la_userorganisationid[0]->membershipcode ?? ''; // set the userorganisationid to be the return or an empty string


        return view('auth.events.registration.register_events', compact('lc_event', 'lc_eventrounds', 'lc_clubs', 'lc_divisions', 'ls_userorgid'));
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


        return view('auth.events.registration.update_register_events', compact('event', 'eventregistration', 'lc_eventrounds', 'clubs', 'divisions'));

    }






    public function eventRegister(Request $request)
    {
        Validator::make($request->all(), [
            'name' => 'required',
            'clubid' => 'required',
            'email' => 'required',
            'divisionid' => 'required',
        ], [
            // custom messages
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
        $evententry->enteredbyuserid = Auth::user()->userid; // set the created by as the person who is logged in
        $evententry->phone = htmlentities($request->input('phone'));
        $evententry->address = htmlentities($request->input('address'));
        $evententry->status = 'pending';
        $evententry->eventid = htmlentities($request->eventid);

        $evententry->save();
        return Redirect::route('eventdetails', $request->eventid)->with('message', 'Registration Successful');


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

}

