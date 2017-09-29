<?php

namespace App\Http\Controllers;

use App\Division;
use App\Event;
use App\EventRound;
use App\Organisation;
use App\Round;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use DateTime;
use DateInterval;
use DatePeriod;
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

        $end = new DateTime( $event->first()->enddate );
        $end->add(new DateInterval('P1D'));

        $daterange = new DatePeriod(
            new DateTime( $event->first()->startdate ),
            new DateInterval('P1D'),
            $end
        );

        return view('auth.events.createeventround', compact('eventid', 'event', 'rounds', 'divisions', 'organisations', 'daterange'));
    }

    public function getUpdateRoundEventView(Request $request)
    {

        $eventround = EventRound::where('eventroundid', $request->eventroundid)->get();

        if ($eventround->isEmpty()) {
            return redirect('divisions');
        }

        $event = Event::find($eventround->first()->eventid);
        $rounds = Round::where('visible', 1)->where('deleted', 0)->get();
        $divisions = Division::where('visible', 1)->where('deleted', 0)->orderBy('organisationid')->get();
        $organisations = Organisation::where('visible', 1)->where('deleted', 0)->get();

        $end = new DateTime( $event->first()->enddate );
        $end->add(new DateInterval('P1D'));

        $daterange = new DatePeriod(
            new DateTime( $event->first()->startdate ),
            new DateInterval('P1D'),
            $end
        );

        return view('auth.events.updateeventround', compact('divisions', 'organisations', 'rounds', 'eventround', 'daterange'));
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
            'divisions' => 'required',
            'date' => 'required'
        ]);


        $eventround->name = htmlentities($request->input('name'));
        $eventround->eventid = htmlentities($request->input('eventid'));
        $eventround->location = htmlentities($request->input('location'));
        $eventround->organisationid = htmlentities($request->input('organisationid'));
        $eventround->roundid = htmlentities($request->input('roundid'));
        $eventround->divisions = serialize($request->input('divisions'));
        $eventround->schedule = htmlentities($request->input('schedule'));
        $eventround->date = htmlentities($request->input('date'));
        $eventround->visible = 1;
        $eventround->save();

        return Redirect::route('updateeventview', ['eventid' => urlencode($eventround->eventid)]);

    }

    public function update(Request $request)
    {

        $eventround = EventRound::where('eventroundid', $request->eventroundid)->first();

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

    public function delete(Request $request)
    {
        $eventround = EventRound::find($request->eventroundid);
        $eventround->delete();

        return Redirect::route('updateeventview', ['eventid' => urlencode($eventround->eventid)]);

    }

}
