<?php

namespace App\Http\Controllers;

use App\Event;
use Illuminate\Http\Request;

class EventRegistrationController extends Controller
{

    public function PUBLIC_registerForEvent(Request $request)
    {
        $event = Event::where('eventid', urlencode($request->eventid))->get();

        return view('auth.events.registration.register_events', compact('event'));
    }

    public function eventRegister(Request $request)
    {
        dd($request);
    }
}

