<?php

namespace App\Http\Controllers;

use App\EventEntry;
use Illuminate\Support\Facades\DB;
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

        $events = DB::select("SELECT *
                        FROM `events`
                        WHERE `deleted` = 0
                        AND `status` IN ('open', 'waitlist', 'pending')
                        AND `visible` = 1
                        ORDER BY `startdate` DESC
                        ");

        return view('publicevents.upcomingevents', compact('events'));
    }

    public function PUBLIC_getAllPreviousEventsView()
    {
        $events = DB::select("SELECT *
                        FROM `events`
                        WHERE `deleted` = 0
                        AND `status` IN ('completed')
                        AND `visible` = 1
                        ORDER BY `startdate` DESC
                        ");

        return view('publicevents.previousevents', compact('events'));
    }

    public function PUBLIC_getEventDetailsView(Request $request)
    {
        $event = Event::where('eventid', $request->eventid)->get()->first();
        if (is_null($event)) {
            return Redirect::route('home');
        }

        $eventround = DB::select("SELECT r.`name`, r.`dist1`, r.`dist2`, r.`dist3`, r.`dist4`
            FROM `eventrounds` er 
            JOIN `rounds` r USING (`roundid`)
            WHERE er.`eventid` = :eventid 
            LIMIT 1
            ", ['eventid' => $request->eventid]);

        if (empty($eventround[0])) {
            return Redirect::route('home');
        }
        $eventround = $eventround[0];

        $distances = $this->makeDistanceString($eventround);

        $userevententry = EventEntry::where('userid', Auth::id())->get()->first();

        if (is_null($userevententry)) {
            return Redirect::route('home');
        }

        return view ('publicevents.eventdetails', compact('event', 'eventround', 'distances', 'userevententry'));
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
        $organisations = Organisation::where('visible', 1)->get();
        $rounds = Round::where('visible', 1)->where('deleted', 0)->get();

        return view('auth.events.createevent', compact('divisions', 'rounds', 'organisations', 'rounds'));
    }

    public function getUpdateEventView(Request $request)
    {
        $event = Event::where('eventid', urlencode($request->eventid))->get();

        if ($event->isEmpty()) {
            return redirect('home');
        }

        $eventrounds = EventRound::where('eventid', $request->eventid)->get();

        $organisations = Organisation::where('visible', 1)->get();

        return view('auth.events.updateevent', compact('event', 'eventrounds', 'organisations'));
    }





    public function create(Request $request)
    {
        Validator::make($request->all(), [
            'name' => 'required',
            'datetime' => 'required',
            'eventtype' => 'required',
            'hostclub' => 'required',
            'location' => 'required',
            'contact' => 'required',
            'email' => 'required',
            'cost' => 'required',
            'status' => 'required',
        ])->validate();


        // Format date
        $date = explode(' - ', $request->input('datetime'));
        $startdate = Carbon::createFromFormat('d/m/Y', $date[0]);
        $enddate = Carbon::createFromFormat('d/m/Y', $date[1]);

        $closeentry = '';
        if (!empty($request->input('closeentry'))) {
            $closeentry = Carbon::createFromFormat('d/m/Y', $request->input('closeentry'));
        }

        $dayCount = $startdate->diffInDays($enddate) + 1; // add 1 day to represent the actual number of competing days

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
        $event->organisationid = htmlentities($request->input('organisationid'));
        $event->createdby = Auth::user()->userid; // set the created by as the person who is logged in
        $event->startdate = htmlentities($startdate);
        $event->enddate = htmlentities($enddate);
        $event->closeentry = htmlentities($closeentry);
        $event->daycount = htmlentities($dayCount);
        $event->hostclub = htmlentities($request->input('hostclub'));
        $event->location = htmlentities($request->input('location'));
        $event->cost = htmlentities($request->input('cost'));
        $event->bankaccount = htmlentities($request->input('bankaccount'));
        $event->schedule = htmlentities($request->input('schedule'));
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

        Validator::make($request->all(), [
            'name' => 'required',
            'datetime' => 'required',
            'eventtype' => 'required',
            'hostclub' => 'required',
            'location' => 'required',
            'contact' => 'required',
            'email' => 'required',
            'cost' => 'required',
            'status' => 'required',
        ])->validate();

        // Format date
        $date = explode(' - ', $request->input('datetime'));
        $startdate = Carbon::createFromFormat('d/m/Y', $date[0]);
        $enddate = Carbon::createFromFormat('d/m/Y', $date[1]);

        $closeentry = '';
        if (!empty($request->input('closeentry'))) {
            $closeentry = Carbon::createFromFormat('d/m/Y', $request->input('closeentry'));
        }

        // add 1 day to represent the actual number of competing days
        $dayCount = $startdate->diffInDays($enddate) + 1;
        if ($dayCount === 0) {
            $dayCount++;
        }


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
            $event->organisationid = htmlentities($request->input('organisationid'));
            $event->startdate = htmlentities($startdate);
            $event->enddate = htmlentities($enddate);
            $event->daycount = htmlentities($dayCount);
            $event->hostclub = htmlentities($request->input('hostclub'));
            $event->location = htmlentities($request->input('location'));
            $event->cost = htmlentities($request->input('cost'));
            $event->bankaccount = htmlentities($request->input('bankaccount'));
            $event->schedule = htmlentities(trim($request->input('schedule')));
            $event->visible = $visible;
            $event->save();

            return Redirect::route('events');
        }

    }

    public function delete(Request $request)
    {

        if (!empty($request->eventid) || !empty($request->eventname)) {
            $event = Event::where('eventid', $request->eventid)->where('name', urldecode($request->eventname))->take(1);

            if (!is_null($event)) {
                $eventrounds = EventRound::where('eventid', $request->eventid)->get();

                foreach ($eventrounds as $round) {
                    if (!is_null($round)) {
                        $round->delete();
                    }
                }
                
                $event->first()->delete();
            }

            return Redirect::route('events');
        }

        return Redirect::route('home');


    }

    private function makeDistanceString($event)
    {
        $distances = '';
        for ($i = 1; $i <= 4; $i++) {
            if (!empty ($event->{'dist' . $i})) {
                $distances .= $event->{'dist' . $i} . 'm';
            }

            $j = $i + 1;
            if (!empty($event->{'dist' . $j})) {
                $distances .= ',';
            }
        }
        return $distances;
    }


}


