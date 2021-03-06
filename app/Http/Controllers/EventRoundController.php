<?php

namespace App\Http\Controllers;

use App\Classes\DateRange;
use App\Classes\EventDateRange;
use App\Division;
use App\Event;
use App\EventRound;
use App\Organisation;
use App\Round;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $event = Event::where('eventid', $eventid)
                        ->get()
                        ->first();

        if (!$this->canEditEvent($event->eventid ?? -1, Auth::id())) {
            return Redirect::route('home');
        }

        $rounds = Round::where('visible', 1)->get();
        $divisions = Division::where('visible', 1)->orderBy('organisationid')->get();



        $daterange = new EventDateRange($event->startdate, $event->enddate);


        return view('auth.events.createeventround', compact('eventid', 'event', 'rounds', 'divisions', 'daterange'));
    }

    public function getUpdateRoundEventView(Request $request)
    {

        $eventround = EventRound::where('eventroundid', $request->eventroundid)->get();

        $event = Event::where('eventid', $eventround->first()->eventid)
                        ->get()
                        ->first();

        if ($eventround->isEmpty() || !$this->canEditEvent($event->eventid ?? -1, Auth::id())) {
            return redirect('divisions');
        }



        $daterange = new EventDateRange($event->startdate, $event->enddate);

        $rounds = Round::where('visible', 1)->where('deleted', 0)->get();
        $divisions = Division::where('visible', 1)->where('deleted', 0)->orderBy('organisationid')->get();
        $organisations = Organisation::where('visible', 1)->where('deleted', 0)->get();


        return view('auth.events.updateeventround', compact('divisions', 'organisations', 'rounds', 'event','eventround', 'daterange'));
    }

    public function create(Request $request)
    {
        $event = Event::where('eventid', $request->input('eventid'))
                        ->get()
                        ->first();

        if (empty($event)) {
            return redirect()->back()->with('failure', 'Error');
        }

        $round = Round::where('roundid', $request->input('roundid'))->get()->first();
        if (empty($round)) {
            return redirect()->back()->with('failure', 'Error');
        }

        $eventround = new EventRound();

        $this->validate($request, [
            'eventid' => 'required',
            'roundid' => 'required',
            'date' => 'required'
        ], [
            'roundid.required' => 'Please select a round',
        ]);

        $roundname = !empty($request->input('name')) ? $request->input('name') : Round::where('roundid', $request->input('roundid'))->pluck('name')->first();

        $eventround->name = $roundname;
        $eventround->eventid = $event->eventid;
        $eventround->location = $request->input('location');
        $eventround->roundid = $request->input('roundid');
        $eventround->schedule = $request->input('schedule');
        $eventround->date = $request->input('date');
        $eventround->visible = 1;
        $eventround->save();

        return Redirect::route('updateeventview', ['eventurl' => $event->url])->with('message', 'Round added successfully');


    }

    public function update(Request $request)
    {

        $eventround = EventRound::where('eventroundid', $request->eventroundid)->first();

        $event = Event::where('eventid', $request->input('eventid'))
            ->get()
            ->first();

        if (empty($event) || empty($eventround)) {
            return redirect()->back()->with('failure', 'Error');
        }


        $this->validate($request, [
            'roundid' => 'required',
            'date' => 'required'
        ]);


        if ($request->eventroundid == $eventround->eventroundid) {
            $roundname = !empty($request->input('name')) ? $request->input('name') : Round::where('roundid', $request->input('roundid'))->pluck('name')->first();

            $eventround->name = $roundname;
            $eventround->location = $request->input('location');
            $eventround->roundid = $request->input('roundid');
            $eventround->schedule = $request->input('schedule');
            $eventround->date = $request->input('date');

            $eventround->save();

            return Redirect::route('updateeventview', ['eventurl' => $event->url])->with('message', 'Round added successfully');
        }

        return Redirect::route('events');

    }

    public function delete(Request $request)
    {

        if (!empty($request->eventroundid) || !empty($request->eventroundname)) {
            // find record
            $eventround = EventRound::where('eventroundid', $request->eventroundid)
                                    ->where('name', urldecode($request->eventroundname) );

            $eventid = $eventround->first()->eventid;

            if (!$this->canEditEvent($eventid, Auth::id())) {
                return Redirect::route('home');
            }

            // delete record
            $eventround->first()->delete();

            $event = Event::where('eventid', $eventid)->get()->first();

            return Redirect::route('updateeventview', ['eventurl' => $event->url]);
        }


        return Redirect::route('home');

    }

}
