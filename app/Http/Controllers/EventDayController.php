<?php

namespace App\Http\Controllers;

use App\Division;
use App\EventDay;
use App\Organisation;
use App\Round;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;


class EventDayController extends Controller
{
    public function getUpdateDayEventView(Request $request)
    {
        $eventday = EventDay::where('eventdayid', $request->eventdayid)->get();

        if ($eventday->isEmpty()) {
            return redirect('divisions');
        }

        $rounds = Round::where('visible', 1)->get();
        $divisions = Division::where('visible', 1)->orderBy('organisationid')->get();
        $organisations = Organisation::where('visible', 1)->get();

        return view('auth.events.updateeventday', compact('divisions', 'organisations', 'rounds', 'eventday'));
    }

    public function update(Request $request)
    {

        $eventday = EventDay::where('eventdayid', $request->eventdayid)->first();

        if (is_null($eventday)) {
            return Redirect::route('eventdays');
        }

        $this->validate($request, [
            'name' => 'required',
            'location' => 'required',
            'roundid' => 'required',
            'organisationid' => 'required',
            'divisions' => 'required'
        ]);

//        dd('here');

        if ($request->eventdayid == $eventday->eventdayid) {

            $eventday->name = htmlentities($request->input('name'));
            $eventday->location = htmlentities($request->input('location'));
            $eventday->organisationid = htmlentities($request->input('organisationid'));
            $eventday->roundid = htmlentities($request->input('roundid'));
            $eventday->divisions = serialize($request->input('divisions'));
            $eventday->schedule = htmlentities($request->input('schedule'));

            $eventday->save();

            return Redirect::route('updateeventview', ['eventid' => urlencode($eventday->eventid)]);
        }

        return Redirect::route('events');

    }








}






