<?php

namespace App\Http\Controllers;

use App\EntryStatus;
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
        $events = Event::whereIn( 'status', ['open', 'waitlist', 'pending'] )->where('visible', 1)->orderBy('startdate')->get();

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
        $event = Event::where('eventid', urldecode($request->eventid))->where('name', urldecode($request->name))->get()->first();

        if (is_null($event)) {
            return Redirect::route('home');
        }

        $eventround = DB::select("SELECT r.`name`, r.`dist1`, r.`dist2`, r.`dist3`, r.`dist4`
            FROM `eventrounds` er 
            JOIN `rounds` r USING (`roundid`)
            WHERE er.`eventid` = :eventid 
            ",
            ['eventid' => $event->eventid]
        );

        $distances = $this->makeDistanceString($eventround);

        $userevententry = EventEntry::where('userid', Auth::id())->where('eventid', $event->eventid)->get()->first();

        if (!is_null($userevententry)) {
            $userevententry->status = EntryStatus::where('entrystatusid', $userevententry->entrystatusid)->pluck('name')->first();
        }

        $users = DB::select("SELECT ee.`fullname`, ee.`entrystatusid`, ee.`clubid` as club, ee.`paid`, d.`name` as division
            FROM `evententry` ee
            LEFT JOIN `divisions` d ON (ee.`divisionid` = d.`divisionid`)
            LEFT JOIN `clubs` c ON(c.`clubid` = ee.`clubid`)
            WHERE ee.`eventid` = :eventid
            ", ['eventid' => $event->eventid]);

        foreach ($users as $user) {
            $user->label = $this->getLabel($user->division);
        }

        $entrystatus = EntryStatus::get();

        return view ('publicevents.eventdetails', compact('event', 'eventround', 'distances', 'userevententry', 'users', 'entrystatus'));
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


        $users = DB::select("SELECT ee.`userid`, ee.`fullname`, ee.`entrystatusid`, ee.`clubid`, c.`name` as club, ee.`paid`, d.`name` as division
            FROM `evententry` ee
            LEFT JOIN `divisions` d ON (ee.`divisionid` = d.`divisionid`)
            LEFT JOIN `clubs` c ON(c.`clubid` = ee.`clubid`)
            WHERE ee.`eventid` = :eventid
            ", ['eventid' => $request->eventid]);

        foreach ($users as $user) {
            $user->label = $this->getLabel($user->division);
        }


        $entrystatus = EntryStatus::get();

        return view('auth.events.updateevent', compact('event', 'eventrounds', 'organisations', 'users', 'entrystatus'));
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

        $multipledivisions = 0;
        if (!empty($request->input('multipledivisions'))) {
            $multipledivisions = 1;
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
        $event->multipledivisions = $multipledivisions;
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

            return Redirect::route('updateevent', $request->eventid)->with('message', 'Update Successful');

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

    private function makeDistanceString($events)
    {

        $distarray = [];
        foreach ($events as $event) {
            for ($i = 1; $i <= 4; $i++) {
                if (!empty ($event->{'dist' . $i})) {
                    $distarray[$event->{'dist' . $i} . 'm'] = $event->{'dist' . $i} . 'm';
                }
            }
        }
        $distances = implode(', ', $distarray);

        return $distances;
    }

    private function getLabel($division) {
        switch (strtolower($division)) {
            case 'compound' :
                return 'label-primary';
                break;
            case 'recurve' :
                return 'label-success';
                break;
            case 'longbow' :
                return 'label-info';
                break;
            default :
                return 'label-warning';
                break;
        }
    }


}


