<?php

namespace App\Http\Controllers;

use App\Classes\EventDateRange;
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
use Image;

use Illuminate\Support\Facades\View;


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

        $eventid = $this->geteventurlid($request->eventurl);


        if (empty($eventid) && !is_int($eventid)) {
            return Redirect::route('home');
        }

        $data = $this->getEventData($eventid);
        if (empty($data)) {
            return Redirect::route('home');
        }

        // User Entry
        $userevententry = EventEntry::where('userid', Auth::id())
                                        ->where('eventid', $data['event']->eventid)
                                        ->get()
                                        ->first();
        if (!empty($userevententry)) {
            $userevententry->status = EntryStatus::where('entrystatusid', $userevententry->entrystatusid)->pluck('name')->first();
        }

        $data['userevententry'] = $userevententry;
        $data['users'] = $this->getEventUsers($data['event']->eventid)['users'];
        $data['entrystatus'] = $this->getEventUsers($data['event']->eventid)['entrystatus'];
        $data['canscore'] = $this->canScore($data['event'], $userevententry);

        return view ('publicevents.eventdetails', $data);
    }

    /**
     * Returns the information required for the eventdetails_details view
     */
    private function getEventData($eventid)
    {

        $event = Event::where('eventid', $eventid)
            ->get()
            ->first();

        if (empty($event)) {
            return false;
        }

        $event->numberofweeks = ceil($event->daycount / 7);

        // Event Rounds stuff
        $eventrounds = DB::select("
            SELECT r.`name`, r.`dist1`, r.`dist2`, r.`dist3`, r.`dist4`, er.`name` as roundname, er.`location`, e.`status`, er.`eventroundid`, r.`unit`
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

        return ['event' => $event, 'eventrounds' => $eventrounds];
    }

    private function getEventFormData($eventid)
    {
        $event = Event::where('eventid', $eventid)->get();

        if (empty($event)) {
            return false;
        }


        $organisations = Organisation::where('visible', 1)->get();

        $daterange = new EventDateRange($event->first()->startdate, $event->first()->enddate);
        $daterange = $daterange->getDateRange();

        $weeks = ($daterange != 0) ? count($daterange) / 7 : 1;

        $divisions = Division::where('visible', 1)->orderBy('name')->get();
        $eventdivisions = unserialize($event->first()->divisions);
        $divisions = $this->sortDivisions($eventdivisions, $divisions);



        return [
            'event' => $event,
            'organisations' => $organisations,
            'daterange' => $daterange,
            'weeks' => $weeks,
            'divisions' => $divisions,
            'eventdivisions' => $eventdivisions,
        ];

    }

    private function getEventUsers($eventid)
    {
        $data = [];
        $data['users'] = DB::select("
            SELECT ee.`userid`, ee.`created_at`,  ee.`confirmationemail`, ee.`fullname`, ee.`hash`, ee.`entrystatusid`, ee.`clubid`, 
              c.`name` as club, ee.`paid`, d.`name` as division, ee.`divisionid`, er.`name` as eventname, u.`username`
            FROM `evententry` ee
            LEFT JOIN `divisions` d ON (ee.`divisionid` = d.`divisionid`)
            LEFT JOIN `clubs` c ON(c.`clubid` = ee.`clubid`)
            LEFT JOIN `eventrounds` er ON (ee.`eventroundid` = er.`eventroundid`)
            LEFT JOIN `users` u ON(ee.`userid` = u.`userid`)
            WHERE ee.`eventid` = :eventid
            GROUP BY ee.`userid`
            ORDER BY ee.`entrystatusid`, d.`name`, ee.`fullname`
            
            ", ['eventid' => $eventid]);

        foreach ($data['users'] as $user) {
            $user->label = $this->getLabel($user->division);
        }

        $data['entrystatus'] = EntryStatus::get();

        return $data;

    }

    /****************************************************
    *                                                   *
    *                ADMIN / AUTH METHODS               *
    *                                                   *
    *****************************************************/

    public function getEventsView()
    {
        if (Auth::user()->usertype == 1) {
            $events = Event::orderBy('eventid', 'desc')
                            ->get();
        }
        else {
            $events = Event::where('createdby', Auth::user()->userid)
                            ->orderBy('eventid', 'desc')
                            ->get();
        }

        return view('auth.events.events', compact('events'));
    }

    public function getCreateView()
    {
        if (!$this->canManageEvents()) {
            return Redirect::route('home');
        }

        $divisions = Division::where('visible', 1)
                                ->where('deleted', 0)
                                ->orderBy('name')
                                ->get();

        $organisations = Organisation::where('visible', 1)
                                        ->get();

        $rounds = Round::where('visible', 1)
                            ->where('deleted', 0)
                            ->get();

        return view('auth.events.createevent', compact('divisions', 'rounds', 'organisations', 'rounds'));
    }

    public function getUpdateEventView(Request $request)
    {

        $event = Event::where('eventid', $this->geteventurlid($request->eventurl))
                        ->get()
                        ->first();


        $canedit = $this->canEditEvent( ($event->eventid ?? -1), Auth::id() );

        if (empty($event) || ! $canedit)  {
            return redirect('home');
        }


        $data = $this->getEventData($event->eventid);
        if (empty($data)) {
            return Redirect::route('home');
        }

        return view('auth.events.updateevent', $data);
    }

    public function getUserEntryDetails(Request $request)
    {
        $user = EventEntry::where('hash', $request->entryhash)
                            ->get()
                            ->first();

        $event = Event::where('eventid', $user->eventid ?? -1)
                        ->get()
                        ->first();


        $canedit = $this->canEditEvent( ($event->eventid ?? -1), Auth::id() );

        if (!$canedit)  {
            return redirect('home');
        }

        $er = new EventRegistrationController();

        $data = $er->getRegisterEventDetails($event->eventid, $user->userid );
        $data['relations'] = [];

        return view('auth.events.event_userentry', $data);

    }

    public function updateUsersEntry(Request $request)
    {
        $eventid = $this->geteventurlid($request->eventurl);

        if (empty($eventid) && !is_int($eventid)) {
            return Redirect::route('home');
        }

        $event = Event::where('eventid', $eventid)
                        ->get()
                        ->first();

        $er = new EventRegistrationController();
        // Remove the users entry
        if ($request->input('submit') == 'remove') {
            $er->deleteUserEntry($request->input('userid'), $request->input('eventid'));
        }

        /* non league */
        if ($event->eventtype == 0 && $event->multipledivisions == 0) {
            // Multiple entry comp
            $er->singleEntryUpdate($request, $event->eventid);
        }
        /* league processing */
        else {
            $er->league_eventRegister($request, $event->eventid);
        }

        return redirect()->back()->withInput()->with('message', 'Update Successful');
    }



    public function create(Request $request)
    {
        Validator::make($request->all(), [
            'name' => 'required|unique:events,name',
            'datetime' => 'required',
            'eventtype' => 'required',
            'hostclub' => 'required',
            'location' => 'required',
            'email' => 'required',
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



        $event = new Event();


        $event->name = $request->input('name');
        $event->email = $request->input('email');

        // dates
        $event->startdate = $startdate;
        $event->enddate = $enddate;
        $event->closeentry = $closeentry;
        $event->daycount = $dayCount;

        $event->eventtype = $request->input('eventtype');
        $event->status = $request->input('status');
        $event->organisationid = $request->input('organisationid');
        $event->createdby = Auth::id();

        $event->hostclub = $request->input('hostclub');
        $event->location = $request->input('location');

        $event->cost = $request->input('cost');

        $event->bankaccount = $request->input('bankaccount');
        $event->bankreference = $request->input('bankreference');
        $event->divisions = serialize('');
        $event->schedule = $request->input('schedule');
        $event->information = $request->input('information');

        $event->sponsortext = $request->input('sponsortext');
        $event->sponsorimageurl = $request->input('sponsorimageurl');

        $event->save();

        $event->url = $this->makeurl($event->name, $event->eventid);
        $event->save();


        return Redirect::route('updateeventview', ['eventurl' => $event->url]);
    }

    public function update(Request $request)
    {

        // Used for adding days to the event
        if ($request->input('submit') == 'createeventround') {

            return Redirect::route('createeventroundview', $request->eventid);
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
            'email' => 'required',
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
            $event->divisions = serialize($request->input('divisions'));
            $event->bankaccount = $request->input('bankaccount');
            $event->bankreference = $request->input('bankreference');
            $event->schedule = trim($request->input('schedule'));
            $event->information = trim($request->input('information'));

            $event->sponsortext = $request->input('sponsortext');
            $event->sponsorimageurl = $request->input('sponsorimageurl');
            $event->hash = md5($request->input('name') . time());

            $event->currentweek = $request->input('currentweek') ?? 1;


            $event->save();

            return Redirect::route('updateevent', $request->eventid)->with('message', 'Update Successful')->with('edit', true);

        }

    }

    public function updatesetup(Request $request)
    {
        $event = Event::where('eventid', $request->eventid)->first();

        if (is_null($event)) {
            return Redirect::route('events');
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

            $dobrequired = 0;
            if (!empty($request->input('dob'))) {
                $dobrequired = 1;
            }

            $sponsored = 0;
            if (!empty($request->input('sponsored'))) {
                $sponsored = 1;
            }

            $ignoregender = 0;
            if (!empty($request->input('ignoregenders'))) {
                $ignoregender = 1;
            }



            $event->multipledivisions = $multipledivisions;

            $event->scoringenabled = $scoringenabled;
            $event->userscanscore = $userscanscore;
            $event->sponsored = $sponsored;

            $event->ignoregenders = $ignoregender;
            $event->visible = $visible;
            $event->dob = $dobrequired;

            $event->save();

            return Redirect::route('updateevent', $request->eventid)->with('message', 'Update Successful');

        }


    }

    public function updatesponsorship(Request $request)
    {
        $event = Event::where('eventid', $request->eventid)->first();

        if (is_null($event)) {
            return Redirect::route('events');
        }

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

        $event->sponsortext = $request->input('sponsortext');
        $event->sponsorimageurl = $request->input('sponsorimageurl');
        $event->save();

        return Redirect::route('updateevent', $request->eventid)->with('message', 'Update Successful');
    }

    public function delete(Request $request)
    {

        $eventid = $this->geteventurlid($request->eventurl);

        if (empty($eventid) && !is_int($eventid)) {
            return Redirect::route('home');
        }


        $event = Event::where('eventid', $eventid)
                        ->take(1);

        if (!empty($event)) {
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

    private function sortDivisions($eventdivisions, $divisions)
    {
        // if nothing is selected, show everything
        if (empty($eventdivisions)) {

            return $divisions;
        }


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


    public function getEventAjaxData(Request $request)
    {

        $event = Event::where('eventid', $request->eventid)
                        ->get()
                        ->first();

        $caneditevent = $this->canEditEvent( ($event->eventid ?? -1), Auth::id() );


        // If the event is empty OR the user is not allowed to edit the event, return false
        if (empty($event) || empty($caneditevent) || empty($request->type)) {
            return response()->json([
                'success' => false,
            ]);
        }

        $data = [];
        switch ($request->type) {
            case 'summary' :
                $data = $this->getEventData($event->eventid);
                $view = View::make('includes.events.eventdetails_details', $data);
                $html = $view->render();
                break;

            case 'scoring':
                break;

            case 'edit':
                $data = $this->getEventFormData($event->eventid);
                $view = View::make('includes.adminevents.eventdetailsform', $data);
                $html = $view->render();
                break;
            case 'editsetup' :
                $data = $this->getEventFormData($event->eventid);
                $view = View::make('includes.adminevents.eventsetupform', $data);
                $html = $view->render();
                break;

            case 'rounds':
                $data['event'] = $event;
                $data['eventrounds'] = EventRound::where('eventid', $event->eventid)->get();
                $view = View::make('includes.adminevents.eventrounds', $data);
                $html = $view->render();
                break;

            case 'entries':
                $data = $this->getEventUsers($event->eventid);
                $data['event'] = $event;
                $view = View::make('includes.adminevents.evententries', $data);
                $html = $view->render();
                break;

            case 'sponsorship' :
                $data = $this->getEventFormData($event->eventid);
                $view = View::make('includes.adminevents.eventsponsorship', $data);
                $html = $view->render();
                break;

            case 'targets':
                $view = View::make('includes.adminevents.targetallocation');
                $html = $view->render();
                break;

        } // switch


        return response()->json([
            'success' => true,
            'html' => $html
        ]);



    }

}


