<?php

namespace App\Http\Controllers;

use App\Division;
use App\Event;
use App\EventRound;
use App\Organisation;
use App\Round;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class EventRoundController extends Controller
{





    /*****************************************************
     *                                                   *
     *                ADMIN / AUTH METHODS               *
     *                                                   *
     *****************************************************/


    public function getCreateEventRoundView($eventid)
    {
        $rounds = Round::where('visible', 1)->where('deleted', 0)->get();
        $divisions = Division::where('visible', 1)->where('deleted', 0)->orderBy('organisationid')->get();
        $organisations = Organisation::where('visible', 1)->where('deleted', 0)->get();
        $event = Event::where('eventid', $eventid);

        $startdate = $event->first()->startdate;
        $enddate = $event->first()->enddate;


        return view('auth.events.createeventround', compact('eventid', 'rounds', 'divisions', 'organisations', 'startdate', 'enddate'));
    }

    public function getUpdateRoundEventView(Request $request)
    {

        $eventround = EventRound::where('eventroundid', $request->eventroundid)->get();

        if ($eventround->isEmpty()) {
            return redirect('divisions');
        }

        $rounds = Round::where('visible', 1)->where('deleted', 0)->get();
        $divisions = Division::where('visible', 1)->where('deleted', 0)->orderBy('organisationid')->get();
        $organisations = Organisation::where('visible', 1)->where('deleted', 0)->get();

        return view('auth.events.updateeventround', compact('divisions', 'organisations', 'rounds', 'eventround'));
    }

    public function create(Request $request)
    {

        $eventround = new EventRound();

        $this->validate($request, [
            'name' => 'required',
            'eventid' => 'required',
            'location' => 'required',
            'roundid' => 'required',
            'organisationid' => 'required',
            'divisions' => 'required'
        ]);


        $eventround->name = htmlentities($request->input('name'));
        $eventround->eventid = htmlentities($request->input('eventid'));
        $eventround->location = htmlentities($request->input('location'));
        $eventround->organisationid = htmlentities($request->input('organisationid'));
        $eventround->roundid = htmlentities($request->input('roundid'));
        $eventround->divisions = serialize($request->input('divisions'));
        $eventround->schedule = htmlentities($request->input('schedule'));
        $eventround->visible = 1;
        $eventround->save();

        return Redirect::route('updateeventview', ['eventid' => urlencode($eventround->eventid)]);

    }

    public function update(Request $request)
    {

        $eventround = EventDay::where('eventroundid', $request->eventroundid)->first();

        if (is_null($eventround)) {
            return Redirect::route('eventrounds');
        }

        $this->validate($request, [
            'name' => 'required',
            'location' => 'required',
            'roundid' => 'required',
            'organisationid' => 'required',
            'divisions' => 'required'
        ]);

//        dd('here');

        if ($request->eventroundid == $eventround->eventroundid) {

            $eventround->name = htmlentities($request->input('name'));
            $eventround->location = htmlentities($request->input('location'));
            $eventround->organisationid = htmlentities($request->input('organisationid'));
            $eventround->roundid = htmlentities($request->input('roundid'));
            $eventround->divisions = serialize($request->input('divisions'));
            $eventround->schedule = htmlentities($request->input('schedule'));

            $eventround->save();

            return Redirect::route('updateeventview', ['eventid' => urlencode($eventround->eventid)]);
        }

        return Redirect::route('events');

    }

}
