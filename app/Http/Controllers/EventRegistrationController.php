<?php

namespace App\Http\Controllers;

use App\Club;
use App\Division;
use App\Event;
use App\EventRound;
use Illuminate\Http\Request;

class EventRegistrationController extends Controller
{

    public function PUBLIC_registerForEvent(Request $request)
    {
        $event = Event::where('eventid', urlencode($request->eventid))->get();
        $eventrounds = EventRound::where('eventid', $event->first()->eventid);
        $divisions = Division::whereIn('divisionid', unserialize($eventrounds->first()->divisions))->get(); // array of divisions
        $clubs = Club::where('organisationid', $event->first()->organisationid)->get();
        dd($clubs);

        return view('auth.events.registration.register_events', compact('event'));
    }

    public function eventRegister(Request $request)
    {
        dd($request);
    }
}

