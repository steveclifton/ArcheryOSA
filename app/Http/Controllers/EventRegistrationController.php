<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Club;
use App\Division;
use App\Event;
use App\EventRound;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventRegistrationController extends Controller
{

    public function PUBLIC_registerForEvent(Request $request)
    {
        $lc_event = Event::where('eventid', urlencode($request->eventid))->get();
        $lc_eventrounds = EventRound::where('eventid', $lc_event->first()->eventid)->get();

        $lc_divisions = Division::whereIn('divisionid', $this->processEventRoundDivisions($lc_eventrounds))->get(); // collection array of divisions
        $lc_clubs = Club::where('organisationid', $lc_event->first()->organisationid)->get();

        $la_userorganisationid = DB::select("SELECT `id`
                                            FROM `userorganisations`
                                            WHERE `userid` = " . Auth::user()->userid . "
                                            AND `organisationid` = '". $lc_event->first()->organisationid ."'
                                            LIMIT 1
                                        ");

        $ls_userorgid = $la_userorganisationid[0]->id ?? ''; // set the userorganisationid to be the return or an empty string


        return view('auth.events.registration.register_events', compact('lc_event', 'lc_eventrounds', 'lc_clubs', 'lc_divisions', 'ls_userorgid'));
    }

    public function eventRegister(Request $request)
    {
        dd($request);
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

