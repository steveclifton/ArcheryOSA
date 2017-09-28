<?php

namespace App\Http\Controllers;

use App\EventRound;
use Carbon\Carbon;
use App\Division;
use App\Organisation;
use App\Round;
use App\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{

    public function PUBLIC_getAllUpcomingEventsView()
    {
        // TODO Need to update this to be everything not cancelled/completed



        $events = Event::where('visible', 1)
                    ->where('status', 'open')
                    ->where('deleted', 0)
                    ->orderby('startdate', 'descending')
                    ->get();

        return view('publicevents.upcomingevents', compact('events'));
    }

    public function PUBLIC_getAllPreviousEventsView()
    {
        $events = Event::where('visible', 1)
                    ->where('deleted', 0)
                    ->where('status', 'completed')
                    ->orderby('startdate', 'descending')
                    ->get();

        return view('publicevents.previousevents', compact('events'));
    }




    /****************************************************
    *                                                   *
    *                ADMIN / AUTH METHODS               *
    *                                                   *
    *****************************************************/

    public function getEventsView()
    {
        $events = Event::orderBy('eventid', 'desc')->get();

        return view('auth.events.events', compact('events'));
    }

    public function getCreateView()
    {
        $divisions = Division::where('visible', 1)->where('deleted', 0)->orderBy('organisationid')->get();
        $organisations = Organisation::where('visible', 1)->where('deleted', 0)->get();
        $page_id = -1;
        $rounds = Round::where('visible', 1)->where('deleted', 0)->get();

        return view('auth.events.createevent', compact('divisions', 'rounds', 'organisations', 'rounds', 'page_id'));
    }

    public function getUpdateEventView(Request $request)
    {
        dd(phpinfo());
        $event = Event::where('eventid', urlencode($request->eventid))->get();

        if ($event->isEmpty()) {
            return redirect('divisions');
        }

        $eventrounds = EventRound::where('eventid', $request->eventid)->get();


        return view('auth.events.updateevent', compact('event', 'eventrounds'));
    }





    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'datetime' => 'required',
            'eventtype' => 'required',
            'hostclub' => 'required',
            'location' => 'required',
            'contact' => 'required',
            'email' => 'required',
            'cost' => 'required',
            'status' => 'required',
            'closeentry' => 'required'
        ]);

        // Format date
        $date = explode(' - ', $request->input('datetime'));
        $startdate = Carbon::createFromFormat('d/m/Y', $date[0]);
        $enddate = Carbon::createFromFormat('d/m/Y', $date[1]);
        $closeentry = Carbon::createFromFormat('d/m/Y', $request->input('closeentry'));

        $dayCount = $startdate->diffInDays($enddate) + 1; // add 1 day to represent the actual number of competing days



        // Validate that event is more than 10 days and is not a single event
        $validator->after(function ($validator) use ($startdate, $enddate, $request) {
            if ($startdate->diffInDays($enddate) > 9 && $request->input('eventtype') == 0) {
                $validator->errors()->add('eventerror', 'Single Events cannot be more than 10 days');
            }
        })->validate();


        $visible = 0;
        if (!empty($request->input('visible'))) {
            $visible = 1;
        }

        $event = new Event();

        $event->name = htmlentities($request->input('name'));
        $event->email = htmlentities($request->input('email'));
        $event->contact = htmlentities($request->input('contact'));
        $event->eventtype = htmlentities($request->input('eventtype'));
        $event->status = htmlentities($request->input('status'));
        $event->createdby = Auth::user()->userid;

        $event->startdate = htmlentities($startdate);
        $event->enddate = htmlentities($enddate);
        $event->closeentry = htmlentities($closeentry);
        $event->daycount = htmlentities($dayCount);
        $event->hostclub = htmlentities($request->input('hostclub'));
        $event->location = htmlentities($request->input('location'));
        $event->cost = htmlentities($request->input('cost'));
        $event->schedule = htmlentities(trim($request->input('schedule')));
        $event->visible = $visible;
        $event->save();

        return Redirect::route('updateeventview', ['eventid' => urlencode($event->eventid)]);
    }

    public function update(Request $request)
    {
        // Used for adding days to the event
        if ($request->input('submit') == 'createeventround') {
            return Redirect::route('createeventroundview', $request->input('eventid'));
        }

        $event = Event::where('eventid', $request->eventid)->first();

        if (is_null($event)) {
            return Redirect::route('events');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'datetime' => 'required',
            'eventtype' => 'required',
            'hostclub' => 'required',
            'location' => 'required',
            'contact' => 'required',
            'email' => 'required',
            'cost' => 'required',
            'status' => 'required',
            'closeentry' => 'required'
        ]);

        // Format date
        $date = explode(' - ', $request->input('datetime'));
        $startdate = Carbon::createFromFormat('d/m/Y', $date[0]);
        $enddate = Carbon::createFromFormat('d/m/Y', $date[1]);
        $closeentry = Carbon::createFromFormat('d/m/Y', $request->input('closeentry'));
        // add 1 day to represent the actual number of competing days
        $dayCount = $startdate->diffInDays($enddate) + 1;
        if ($dayCount === 0) {
            $dayCount++;
        }

        // Validate that event is more than 10 days and is not a single event
        $validator->after(function ($validator) use ($startdate, $enddate, $request) {
            if ($startdate->diffInDays($enddate) > 9 && $request->input('eventtype') == 0) {
                $validator->errors()->add('eventerror', 'Single Events cannot be more than 10 days');

            }
        })->validate();

        if ($request->eventid == $event->eventid) {

            $visible = 0;
            if (!empty($request->input('visible'))) {
                $visible = 1;
            }

            $event->name = htmlentities($request->input('name'));
            $event->email = htmlentities($request->input('email'));
            $event->contact = htmlentities($request->input('contact'));
            $event->eventtype = htmlentities($request->input('eventtype'));
            $event->closeentry = htmlentities($closeentry);
            $event->status = htmlentities($request->input('status'));
            $event->startdate = htmlentities($startdate);
            $event->enddate = htmlentities($enddate);
            $event->daycount = htmlentities($dayCount);
            $event->hostclub = htmlentities($request->input('hostclub'));
            $event->location = htmlentities($request->input('location'));
            $event->cost = htmlentities($request->input('cost'));
            $event->schedule = htmlentities(trim($request->input('schedule')));
            $event->visible = $visible;
            $event->save();

            return Redirect::route('events');
        }

    }


}


