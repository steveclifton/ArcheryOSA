<?php

namespace App\Http\Controllers;

use App\Classes\EventDateRange;
use App\Club;
use App\EntryStatus;
use App\EventEntry;
use App\Score;
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
use Image;



/**
 * Event Types
 *  0 - Single event, less than 10 days
 *  1 - Weekly shoot, more than 10 days (Postal/League Events)
 *
 *
 *
 *
 * Class EventController
 * @package App\Http\Controllers
 */


class EventController extends Controller
{
    public function PUBLIC_getAllUpcomingEventsView()
    {
        $events = Event::whereIn( 'status', ['open', 'waitlist', 'pending', 'in-progress', 'entries-closed'] )
            ->where('visible', 1)
            ->orderBy('startdate')
            ->get();

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
        // Events
        $event = Event::where('name', urldecode($request->name))
                        ->get()
                        ->first();


        if (empty($event)) {
            return Redirect::route('home');
        }
        $event->numberofweeks = ceil($event->daycount / 7);


        // Event Rounds stuff
        $eventrounds = DB::select("SELECT r.`name`, r.`dist1`, r.`dist2`, r.`dist3`, r.`dist4`, er.`name` as roundname, er.`location`, e.`status`, er.`eventroundid`, r.`unit`
            FROM `eventrounds` er 
            JOIN `rounds` r USING (`roundid`)
            JOIN `events` e USING (`eventid`)
            WHERE er.`eventid` = :eventid 
            GROUP BY r.`name`
            ORDER BY r.`dist1` DESC
            ",
            ['eventid' => $event->eventid]
        );

        // Events rounds distances
        $event->distancestring = $this->makeDistanceString($eventrounds);


        // User Entry
        $userevententry = EventEntry::where('userid', Auth::id())->where('eventid', $event->eventid)->get()->first();
        if (!empty($userevententry)) {
            $userevententry->status = EntryStatus::where('entrystatusid', $userevententry->entrystatusid)->pluck('name')->first();
        }


        // Users
        $userids = [];
        $users = DB::select("SELECT DISTINCT ee.`userid`, ee.`fullname`, ee.`entrystatusid`, ee.`clubid` as club, ee.`paid`, d.`name` as division, u.`username`
            FROM `evententry` ee
            LEFT JOIN `divisions` d ON (ee.`divisionid` = d.`divisionid`)
            LEFT JOIN `clubs` c ON(c.`clubid` = ee.`clubid`)
            LEFT JOIN `users` u ON(ee.`userid` = u.`userid`)
            WHERE ee.`eventid` = :eventid
            ORDER BY d.`name`, ee.`fullname`
            ", ['eventid' => $event->eventid]);

        foreach ($users as $user) {
            $user->label = $this->getLabel($user->division);
            $userids[] = $user->userid;
        }




        $results = Score::where('eventid', $event->eventid)->get()->first();
        if (!empty($results)) {

            $week = '';
            if ($request->exists('week') ) {
                $week = 'AND s.`week` = ' . intval($request->input('week'));
            }

            $results = DB::select("SELECT s.*, u.`firstname`, u.`lastname`, d.`name` as divisonname
            FROM `scores` s 
            JOIN `users` u USING (`userid`)
            JOIN `divisions` d ON (s.`divisionid` = d.`divisionid`)
            WHERE s.`userid` IN (" . implode(',', $userids) . ")
            AND s.`eventid` = :eventid
            $week
            ORDER BY s.`total_score` DESC
        ", ['eventid' => $event->eventid]);

        }

        $resultdistances = $this->getDistances($eventrounds);


        // show scoring tab or not
        $canscore = $this->canScore($event, $userevententry);

        return view ('publicevents.eventdetails', compact('event', 'canscore', 'eventrounds', 'distances', 'userevententry', 'users', 'results', 'resultdistances'));
    }





    /****************************************************
    *                                                   *
    *                ADMIN / AUTH METHODS               *
    *                                                   *
    *****************************************************/

    public function getEventsView()
    {
        if (Auth::user()->usertype == 1) {
            $events = Event::orderBy('eventid', 'desc')->get();
        }
        else {
            $events = Event::where('createdby', Auth::user()->userid)->orderBy('eventid', 'desc')->get();
        }

        return view('auth.events.events', compact('events'));
    }

    public function getCreateView()
    {
        $divisions = Division::where('visible', 1)->where('deleted', 0)->orderBy('name')->get();
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

        $daterange = new EventDateRange($event->first()->startdate, $event->first()->enddate);
        $daterange = $daterange->getDateRange();

        $weeks = ($daterange != 0) ? count($daterange) / 7 : 1;

        $divisions = Division::where('visible', 1)->orderBy('name')->get();
        $eventdivisions = unserialize($event->first()->divisions);
        $divisions = $this->sortDivisions($eventdivisions, $divisions);



        $users = DB::select("
            SELECT ee.`userid`, ee.`created_at`,  ee.`confirmationemail`, ee.`fullname`, ee.`hash`, ee.`entrystatusid`, ee.`clubid`, 
              c.`name` as club, ee.`paid`, d.`name` as division, ee.`divisionid`, er.`name` as eventname
            FROM `evententry` ee
            LEFT JOIN `divisions` d ON (ee.`divisionid` = d.`divisionid`)
            LEFT JOIN `clubs` c ON(c.`clubid` = ee.`clubid`)
            LEFT JOIN `eventrounds` er ON (ee.`eventroundid` = er.`eventroundid`)
            WHERE ee.`eventid` = :eventid
            GROUP BY ee.`userid`
            ORDER BY ee.`entrystatusid`, d.`name`, ee.`fullname`
            
            ", ['eventid' => $request->eventid]);

        foreach ($users as $user) {
            $user->label = $this->getLabel($user->division);
        }


        $entrystatus = EntryStatus::get();

        return view('auth.events.updateevent', compact('event', 'divisions', 'eventdivisions', 'eventrounds', 'organisations', 'users', 'entrystatus', 'weeks'));
    }

    public function getUserEntryDetails(Request $request)
    {

        $userdetails = new \stdClass;

        $user = EventEntry::where('hash', $request->entryhash)->get()->first();
        $event = Event::where('eventid', $user->eventid)->get()->first();
        $user = EventEntry::where('userid', $user->userid)->where('eventid', $user->eventid)->get();

        $userdetails->club = Club::where('clubid', $user->first()->clubid)->pluck('name')->first();
        $userdetails->division = Division::where('divisionid', $user->first()->divisionid)->pluck('name')->first();
        if ($user->first()->paid == 0) {
            $userdetails->entrystatus = 'Not Paid';
        } else if ($user->first()->paid == 1) {
            $userdetails->entrystatus = 'Paid';
        } else {
            $userdetails->entrystatus = 'NA';
        }

        foreach ($user as $u) {
            $userdetails->rounds[] = EventRound::where('eventroundid', $u->eventroundid)->get()->first();
        }

        if (!empty($request->a)) {
            $this->touchurl('sendconfirmationemail/' . $user->first()->userid . '/' . $user->first()->evententryid . '/' . $user->first()->hash);
            foreach ($user as $u) {
                $u->confirmationemail = 1;
                $u->save();
            }
        }

        $hasemail = false;
        foreach ($user as $u) {
            if ($u->confirmationemail == 1) {
                $hasemail = true;
            }
        }
        $clubs = Club::where('organisationid', $event->organisationid)->get();
        $divArr = unserialize($event->divisions);
        $divisions = Division::whereIn('divisionid', $divArr)->orderBy('name', 'asc')->get(); // collection array of divisions
        $eventrounds = EventRound::where('eventid', $event->eventid)->get();

        $userdivisions = [];
        $usereventrounds = [];
        foreach ($user as $u) {
            $userdivisions[] = $u->divisionid;
            $usereventrounds[] = $u->eventroundid;
        }
        $organisationname = Organisation::where('organisationid', $event->organisationid)->pluck('name')->first();

        if (is_null($organisationname)) {
            $organisationname = '';
        }



        return view('auth.events.event_userentry', compact('user', 'userdetails', 'hasemail', 'event', 'clubs', 'divisions', 'userdivisions', 'usereventrounds', 'eventrounds', 'organisationname'));

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

        $scoringenabled = 0;
        if (!empty($request->input('scoringenabled'))) {
            $scoringenabled = 1;
        }

        $userscanscore = 0;
        if (!empty($request->input('userscanscore'))) {
            $userscanscore = 1;
        }

        $multipledivisions = 0;
        if (!empty($request->input('multipledivisions'))) {
            $multipledivisions = 1;
        }
        $sponsored = 0;
        if (!empty($request->input('sponsored'))) {
            $sponsored = 1;
        }

        $ignoregender = 0;
        if (!empty($request->input('ignoregenders'))) {
            $ignoregender = 1;
        }


        $event = new Event();

        if ($request->hasFile('dtimage')) {

            $image = $request->file('dtimage');
            $filename = time() . rand(0,999) . '.' . $image->getClientOriginalExtension();
            $location = public_path('content/sponsor/' . $filename);
            $locationsmall = public_path('content/sponsor/small/' . $filename);
            Image::make($image)->resize(1000,400)->save($location);
            Image::make($image)->resize(100,40)->save($locationsmall);

            $event->dtimage = $filename;
        }

        if ($request->hasFile('mobimage')) {

            $image = $request->file('mobimage');
            $filename = time() . rand(0,999) . '.' . $image->getClientOriginalExtension();
            $location = public_path('content/sponsor/' . $filename);
            $locationsmall = public_path('content/sponsor/small/' . $filename);
            Image::make($image)->resize(800,500)->save($location);
            Image::make($image)->resize(80,50)->save($locationsmall);

            $event->mobimage = $filename;
        }

        $event->name = $request->input('name');
        $event->email = $request->input('email');
        $event->contact = $request->input('contact');
        $event->eventtype = $request->input('eventtype');
        $event->status = $request->input('status');
        $event->organisationid = $request->input('organisationid');
        $event->createdby = Auth::user()->userid; // set the created by as the person who is logged in
        $event->startdate = $startdate;
        $event->enddate = $enddate;
        $event->closeentry = $closeentry;
        $event->daycount = $dayCount;
        $event->hostclub = $request->input('hostclub');
        $event->location = $request->input('location');
        $event->multipledivisions = $multipledivisions;
        $event->cost = $request->input('cost');
        $event->bankaccount = $request->input('bankaccount');
        $event->bankreference = $request->input('bankreference');
        $event->divisions = serialize($request->input('divisions'));
        $event->schedule = $request->input('schedule');
        $event->information = $request->input('information');
        $event->scoringenabled = $scoringenabled;
        $event->userscanscore = $userscanscore;
        $event->sponsored = $sponsored;
        $event->sponsortext = $request->input('sponsortext');
        $event->sponsorimageurl = $request->input('sponsorimageurl');
        $event->ignoregenders = $ignoregender;

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
            'status' => 'required'

        ],[
            'maxweeklyscores.min' => 'Max Weekly Scores must be 1 or more'
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

            $scoringenabled = 0;
            if (!empty($request->input('scoringenabled'))) {
                $scoringenabled = 1;
            }

            $userscanscore = 0;
            if (!empty($request->input('userscanscore'))) {
                $userscanscore = 1;
            }

            $multipledivisions = 0;
            if (!empty($request->input('multipledivisions'))) {
                $multipledivisions = 1;
            }

            $sponsored = 0;
            if (!empty($request->input('sponsored'))) {
                $sponsored = 1;
            }

            $ignoregender = 0;
            if (!empty($request->input('ignoregenders'))) {
                $ignoregender = 1;
            }

            if ($request->hasFile('dtimage')) {
                //clean up old image
                if (empty($event->dtimage) !== true) {
                    if (is_file(public_path('content/sponsor/' . $event->dtimage))) {
                        unlink(public_path('content/sponsor/' . $event->dtimage));
                    }
                }
                $image = $request->file('dtimage');
                $filename = time() . rand(0,999) . '.' . $image->getClientOriginalExtension();
                $location = public_path('content/sponsor/' . $filename);
                $locationsmall = public_path('content/sponsor/small/' . $filename);
                Image::make($image)->resize(1000,400)->save($location);
                Image::make($image)->resize(100,40)->save($locationsmall);

                $event->dtimage = $filename;
            }

            if ($request->hasFile('mobimage')) {
                //clean up old image
                if (empty($event->mobimage) !== true) {
                    if (is_file(public_path('content/sponsor/' . $event->mobimage))) {
                        unlink(public_path('content/sponsor/' . $event->mobimage));
                    }
                }
                $image = $request->file('mobimage');
                $filename = time() . rand(0,999) . '.' . $image->getClientOriginalExtension();
                $location = public_path('content/sponsor/' . $filename);
                $locationsmall = public_path('content/sponsor/small/' . $filename);
                Image::make($image)->resize(800,500)->save($location);
                Image::make($image)->resize(80,50)->save($locationsmall);

                $event->mobimage = $filename;
            }


            $event->name = $request->input('name');
            $event->email = $request->input('email');
            $event->contact = $request->input('contact');
            $event->eventtype = $request->input('eventtype');
            $event->closeentry = $closeentry;
            $event->status = $request->input('status');
            $event->organisationid = $request->input('organisationid');
            $event->startdate = $startdate;
            $event->enddate = $enddate;
            $event->daycount = $dayCount;
            $event->hostclub = $request->input('hostclub');
            $event->location = $request->input('location');
            $event->cost = $request->input('cost');
            $event->multipledivisions = $multipledivisions;
            $event->divisions = serialize($request->input('divisions'));
            $event->bankaccount = $request->input('bankaccount');
            $event->bankreference = $request->input('bankreference');
            $event->schedule = trim($request->input('schedule'));
            $event->information = trim($request->input('information'));
            $event->scoringenabled = $scoringenabled;
            $event->userscanscore = $userscanscore;
            $event->sponsored = $sponsored;
            $event->sponsortext = $request->input('sponsortext');
            $event->sponsorimageurl = $request->input('sponsorimageurl');
            $event->hash = md5($request->input('name') . time());
            $event->ignoregenders = $ignoregender;
            $event->currentweek = $request->input('currentweek') ?? 1;

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

    private function getDistances($eventround)
    {
        $distances = [];
        foreach ($eventround as $eventround) {
            $distances['Distance-1'] = $eventround->dist1;
            $distances['Distance-1-unit'] = $eventround->unit;
            if (!is_null($eventround->dist2)) {
                $distances['Distance-2'] = $eventround->dist2;
                $distances['Distance-2-unit'] = $eventround->unit;
            }
            if (!is_null($eventround->dist3)) {
                $distances['Distance-3'] = $eventround->dist3;
                $distances['Distance-3-unit'] = $eventround->unit;
            }
            if (!is_null($eventround->dist4)) {
                $distances['Distance-4'] = $eventround->dist4;
                $distances['Distance-4-unit'] = $eventround->unit;
            }
        }
        return $distances;

    }

    private function sortDivisions($eventdivisions, $divisions)
    {
        $first = [];
        $second = [];
        foreach ($divisions as $division) {
            if (!in_array($division->divisionid, $eventdivisions)) {
                $second[] = $division;
                continue;
            }
            $first[] = $division;
        }

        return array_merge($first, $second);
    }


}


