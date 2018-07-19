<?php

namespace App\Http\Controllers;

use App\Classes\EventDateRange;
use App\Classes\UserExtended;
use App\EntryStatus;
use App\Event;
use App\EventEntry;
use App\EventRound;
use App\LeagueAverage;
use App\Round;
use App\Score;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;


/**
 * Class ScoringController
 * @package App\Http\Controllers
 *
 * 1 - Pending
 * 2 - Entered
 * 3 - Waitlisted
 * 4 - Rejected
 *
 */
class ScoringController extends Controller
{

    private $eventid;
    private $currentweek;

    /**
     * POST
     * League scoring
     * Creates the scores in the database
     *
     * @param Request $request
     * @return $this
     */
    public function enterScores(Request $request)
    {

        $event = Event::where('eventid', $request->eventid)->get()->first();
        if (empty($event)) {
            return back()->with('failure', 'Oops, Event was not found. Please contact Admin')->withInput();
        }
        if ($event->currentweek != $request->currentweek) {
            return back()->with('failure', 'Oops, Event was not found. Please contact Admin')->withInput();
        }

        $eventround = EventRound::where('eventroundid', $request->eventroundid)->get()->first();
        if (empty($eventround)) {
            return back()->with('failure', 'Oops, Event was not found. Please contact Admin')->withInput();
        }

        $round = Round::where('roundid', $eventround->roundid)->get()->first();
        if (empty($round)) {
            return back()->with('failure', 'Oops, issue with event. Please contact Admin')->withInput();
        }

        // At this point we have a valid form routed request
        $userResults = $this->getUsersFormData($request);

        foreach ($userResults as $user) {

            $evententry = EventEntry::where('userid', $user['userid'])
                ->where('evententryid', $user['evententryid'])
                ->where('divisionid', $user['divisionid'])
                ->get()
                ->first();

            if (empty($evententry)) {

                $errorstring = 'Error with score, please try again';

                $username = User::where('userid', $user['userid'])->get()->first();
                if (!empty($username)) {
                    $errorstring = 'Error with score for ' . ucwords($username->firstname) . ', please try again';
                }

                $errors[] = $errorstring;
                continue;
            }

            $result = $this->validateUsersScores($user, $round);



            if ($result === false) {
                continue;
            }
            else if (!empty($result)) {
                foreach ($result as $error) {
                    $errors[] = $error;
                }
                continue;
            }

            // Check if they have scored for this round already or not
            $score = $this->getExistingScore($user['userid'], $evententry->evententryid, $eventround->eventroundid, $evententry->divisionid, $event->currentweek);

            if (empty($score)) {
                $score = new Score();
            }

            $score = $this->setUsersScore($user, $score, $evententry, $event, $eventround, $round);

            $score->save();
        } // endforeach


        if (!empty($errors)) {
            return back()->with('failure', implode('<br>', array_slice($errors, 0, 10)))->withInput();
        }

        return back()->with('message', 'Scores entered successfully')->withInput();
    }


    /**
     * POST
     * Event Scoring
     */
    public function enterEventScores(Request $request)
    {

        $event = Event::where('eventid', $request->eventid)
                        ->get()
                        ->first();

        if (empty($event)) {
            return back()->with('failure', 'Oops, Event was not found. Please contact Admin')->withInput();
        }


        // At this point we have a valid form routed request
        $userResults = $this->getUsersFormData($request);

        foreach ($userResults as $user) {

            $evententry = EventEntry::where('userid', $user['userid'])
                                        ->where('evententryid', $user['evententryid'])
                                        ->where('divisionid', $user['divisionid'])
                                        ->get()
                                        ->first();

            if (empty($evententry)) {
                $errorstring = 'Error with score, please try again';
                $username = User::where('userid', $user['userid'])->get()->first();
                if (!empty($username)) {
                    $errorstring = 'Error with score for ' . ucwords($username->firstname) . ', please try again';
                }
                Log::info($errorstring);
                $errors[] = $errorstring;
                continue;
            }

            $eventround = EventRound::where('eventroundid', $evententry->eventroundid)
                                        ->get()
                                        ->first();
            if (empty($eventround)) {
                continue;
            }

            $round = Round::where('roundid', $eventround->roundid)
                            ->get()
                            ->first();

            // Validate scores
            $result = $this->validateUsersScores($user, $round);

            // Check if they have scored for this round already or not
            $score = $this->getExistingEventScore($user['userid'], $evententry->evententryid, $evententry->divisionid);


            if ($result === false && empty($score)) {
                continue;
            }
            else if (!empty($result)) {
                foreach ($result as $error) {
                    $errors[] = $error;
                }
                Log::info(implode(',', $result));
                continue;
            }

            if (empty($score)) {
                $score = new Score();
            }

            $score = $this->setUsersEventScore($score, $user, $evententry, $event, $eventround, $round);

            if (empty($score->total_score) && empty($score->distance1_total) && empty($score->$score->distance2_total)) {
                $score->delete();
                echo 'deleted';
            } else {
                $score->save();
                echo 'saved';
            }

        } // endforeach


        if (!empty($errors)) {
            return back()->with('failure', implode('<br>', array_slice($errors, 0, 10)))->withInput();
        }

        return back()->with('message', 'Scores entered successfully')->withInput();

    }



    public function getScoringChoiceView(Request $request)
    {
        $eventid = $this->geteventurlid($request->eventurl);



        if (empty($eventid) && !is_int($eventid)) {
            return Redirect::route('home');
        }

        $event = Event::where('eventid', $eventid)
                        ->where('eventid', $eventid)
                        ->get()
                        ->first();


        if (empty($event)) {
            return Redirect::route('home');
        }

        $this->eventid = $event->eventid;
        $this->currentweek = $event->currentweek;

        if ($event->eventtype == 1) {
            // get league scoring view
            return $this->getLeagueScoringView($event);
        }
        else {
            // normal event
            return $this->getEventScoringView($event);
        }

    }

    private function getEventScoringView($event)
    {

        if ( ($event->scoringenabled ?? -1) != 1) {
            return redirect()->back()->with('failure', 'Scoring currently disabled, please check with the event organiser');
        }

        $daterange = new EventDateRange($event->startdate, $event->enddate);
        $daterange = $daterange->getDateRange();


        $day = isset($_GET['day']) ? intval($_GET['day']) : 0;


        $rounddate = '';
        foreach ($daterange as $key => $date) {
            if ($key == $day) {
                $rounddate = strval($date);
            }
        }

        if ( $rounddate == '' ) {
            return redirect()->back()->with('failure', 'Invalid Request');
        }


        $users = DB::select("SELECT ee.*, d.`name` as divisionname, er.`name` as roundname
            FROM `evententry` ee
            JOIN `divisions` d USING (`divisionid`)
            JOIN `eventrounds` er ON (ee.`eventroundid` = er.`eventroundid`)
            WHERE ee.`eventid` = :eventid
            AND er.`date` = :date
            ", ['eventid' => $event->eventid, 'date' => $rounddate
        ]);


        $userssorted = [];
        foreach ($users as $user) {
            $result = $this->getExistingEventScore($user->userid, $user->evententryid, $user->divisionid);
            if (!empty($result)) {
                $user->result = $result;
            }

            $eventround = DB::select("SELECT r.`dist1`, r.`dist2`, r.`dist3`, r.`dist4`, er.`eventroundid`, r.`unit`
                FROM `eventrounds` er
                JOIN `rounds` r USING (`roundid`)
                JOIN `events` e USING (`eventid`)
                WHERE er.`eventroundid` = :eventroundid
                LIMIT 1
            ",
                ['eventroundid' => $user->eventroundid]
            );

            $user->eventroundobj = reset($eventround);




            if ($user->gender == 'F') {
                $user->gender = 'Female';
            } else {
                $user->gender = 'Male';
            }

            $userssorted[$user->divisionname . " " . $user->gender . " - " . $user->roundname][] = $user;
        }
        $users = $userssorted;
        ksort($users);

        $results = Score::where('eventid', $event->eventid)->get()->first();

        // User Entry
        $userevententry = EventEntry::where('userid', Auth::id())->where('eventid', $event->eventid)->get()->first();
        if (!empty($userevententry)) {
            $userevententry->status = EntryStatus::where('entrystatusid', $userevententry->entrystatusid)->pluck('name')->first();
        }


        // show scoring tab or not
        $canscore = $this->canScore($event, $userevententry);

        return view('auth.events.event_scoringrounds', compact('users', 'canscore','eventround', 'distances', 'event', 'userevententry', 'results', 'daterange'));

    }

    private function getLeagueScoringView($event)
    {

        // Event Rounds stuff - ***************need to update to for events*************

        $eventround = DB::select("SELECT *
            FROM `eventrounds` er
            JOIN `rounds` r USING (`roundid`)
            WHERE er.`eventid` = :eventid
            LIMIT 1
        ", ['eventid' => $this->eventid,
        ]);


        if ( empty($eventround) || !isset($eventround[0]) ) {
            return redirect()->back()->with('failure', 'Invalid Request');
        }
        $eventround = $eventround[0];

        $distances = $this->getLeagueDistance($eventround);
        $userrelations = UserExtended::getUserRelationIDs();

        $users = DB::select("SELECT ee.`eventid`, ee.`fullname`, ee.`userid`, d.`name` as divisionname, d.`divisionid`, ee.`evententryid`
            FROM `evententry` ee
            JOIN `users` u USING (`userid`)
            JOIN `divisions` d USING (`divisionid`)
            WHERE ee.`eventid` = :eventid
            AND ee.`userid` IN (". implode(',' ,$userrelations) .")
            ", ['eventid' => $this->eventid,
        ]);


        foreach ($users as $user) {
            $result = $this->getExistingScore($user->userid, $user->evententryid, $eventround->eventroundid, $user->divisionid, $this->currentweek);
            $user->result = $result;
        }

        $results = Score::where('eventid', $this->eventid)
                            ->get()
                            ->first();

//        // User Entry
//        $userevententry = EventEntry::where('userid', Auth::id())
//            ->whereIn('entrystatusid', [2] )
//            ->where('eventid', $this->eventid)
//            ->get()
//            ->first();
//
//        if (empty($userevententry)) {
//            if (!empty($users)) {
//
//                $userevententry->status = 1;
//            }
//        }
//        else {
//            if (!empty($userevententry)) {
//                $userevententry->status = EntryStatus::where('entrystatusid', $userevententry->entrystatusid)->pluck('name')->first();
//            } else {
//                return back()->with('failure', 'Unable to score at this time')->withInput();
//            }
//        }




        // show scoring tab or not
        $canscore = true;//$this->canScore($event, $userevententry);

        return view('auth.events.event_league_scoringrounds', compact('users', 'canscore', 'eventround', 'eventrounds', 'distances', 'event', 'results'));

    }




    public function getResults(Request $request)
    {

        $eventid = $this->geteventurlid($request->eventurl);

        if (empty($eventid) && !is_int($eventid)) {
            return Redirect::route('home');
        }

        $event = Event::where('eventid', $eventid)
                        ->get()
                        ->first();

        if (empty($event)) {
            return Redirect::route('home');
        }

        if ($event->eventtype == 1) {
            // is a league round
            return $this->getLeagueEventResults($request, $event);
        } else {
            // non league
            return $this->getEventResults($event);
        }

    }


    public function getEventResults($event)
    {

        $daterange = new EventDateRange($event->startdate, $event->enddate);
        $daterange = $daterange->getDateRange();

        if ($_GET['day'] ?? '' == 'overall') {
            return $this->getoveralleventresults($event);
        }


        $day = isset($_GET['day']) ? intval($_GET['day']) : 0;


        $rounddate = '';
        foreach ($daterange as $key => $date) {
            if ($key == $day) {
                $rounddate = strval($date);
            }
        }

        if ( $rounddate == '' ) {
            return redirect()->back()->with('failure', 'Invalid Request');
        }


        $results = Score::where('eventid', $event->eventid)->get()->first();


        if (!empty($results)) {

            $results = DB::select("
                SELECT s.*, ee.`fullname`, ee.`gender`, u.`username`, d.`name` as divisonname, er.`name` as roundname
                FROM `scores` s 
                JOIN `evententry` ee ON (ee.`evententryid` = s.`evententryid`)
                JOIN `eventrounds` er ON (ee.`eventroundid` = er.`eventroundid`)
                JOIN `users` u ON (s.`userid` = u.`userid`)
                JOIN `divisions` d ON (ee.`divisionid` = d.`divisionid` AND s.divisionid = ee.divisionid)
                WHERE s.`eventid` = :eventid
                AND er.`date` = :date
                
                ", ['eventid' => $event->eventid, 'date' => $rounddate]

            );


            $resultssorted = [];
            foreach ($results as $result) {
                if ($result->gender == 'F') {
                    $result->gender = 'Female';
                } else {
                    $result->gender = 'Male';
                }
                $resultssorted[$result->roundname . ' '.$result->divisonname . " " . $result->gender][] = $result;
            }

            foreach ($resultssorted as $key => &$result) {
                usort($result, function ($a, $b) {

                    // return 1 when B greater than A
                    //dump($a, $b);
                    if (intval($b->total_score) == intval($a->total_score)) {
                        if (intval($b->total_hits) == intval($a->total_hits)) {
                            if (intval($b->total_10) > intval($a->total_10)) {
                                return 1;
                            }
                        }

                        else if (intval($b->total_hits) > intval($a->total_hits)) {
                            return 1;
                        }
                    }

                    else if (intval($b->total_score) > intval($a->total_score)) {
                        return 1;
                    }

                    return -1;

                });
            }

            $results = $resultssorted;
        }



        $eventrounds = DB::select("
            SELECT r.`name`, r.`dist1`, r.`dist2`, r.`dist3`, r.`dist4`, er.`name` as roundname, 
                  er.`location`, e.`status`, er.`eventroundid`, r.`unit`
            FROM `eventrounds` er
            JOIN `rounds` r USING (`roundid`)
            JOIN `events` e USING (`eventid`)
            WHERE er.`eventid` = :eventid
            ",
            ['eventid' => $event->eventid]
        );

        $resultdistances = $this->getDistances($eventrounds);

        // User Entry
        $userevententry = EventEntry::where('userid', Auth::id())
                                    ->where('eventid', $event->eventid)
                                    ->get()
                                    ->first();

        if (!empty($userevententry)) {
            $userevententry->status = EntryStatus::where('entrystatusid', $userevententry->entrystatusid)->pluck('name')->first();
        }

        $daterange = new EventDateRange($event->startdate, $event->enddate);
        $daterange = $daterange->getDateRange();


        $canscore = $this->canScore($event, $userevententry);



        return view ('publicevents.event_results', compact('daterange','event', 'canscore', 'eventrounds', 'userevententry', 'users', 'results', 'resultdistances', 'userevententry'));

    }

    public function getoveralleventresults($event)
    {

        $results = Score::where('eventid', $event->eventid)->get()->first();


        if (!empty($results)) {

            $results = DB::select("
                SELECT s.*, ee.`fullname`, ee.`gender`, u.`username`, d.`name` as divisonname, er.`name` as roundname
                FROM `scores` s 
                JOIN `evententry` ee ON (ee.`evententryid` = s.`evententryid`)
                JOIN `eventrounds` er ON (ee.`eventroundid` = er.`eventroundid`)
                JOIN `users` u ON (s.`userid` = u.`userid`)
                JOIN `divisions` d ON (ee.`divisionid` = d.`divisionid` AND s.divisionid = ee.divisionid)
                WHERE s.`eventid` = :eventid
                ", [
                    'eventid' => $event->eventid
                ]
            );



            $resultssorted = [];
            foreach ($results as $result) {
                if ($result->gender == 'F') {
                    $result->gender = 'Female';
                } else {
                    $result->gender = 'Male';
                }
                $resultssorted[ $result->divisonname . " " . $result->gender][] = $result;
            }

            foreach ($resultssorted as $key => &$result) {
                usort($result, function ($a, $b) {

                    // return 1 when B greater than A
                    //dump($a, $b);
                    if (intval($b->total_score) == intval($a->total_score)) {
                        if (intval($b->total_hits) == intval($a->total_hits)) {
                            if (intval($b->total_10) > intval($a->total_10)) {
                                return 1;
                            }
                        }

                        else if (intval($b->total_hits) > intval($a->total_hits)) {
                            return 1;
                        }
                    }

                    else if (intval($b->total_score) > intval($a->total_score)) {
                        return 1;
                    }

                    return -1;

                });
            }

            $results = $resultssorted;
        }

        $eventrounds = DB::select("
            SELECT r.`name`, r.`dist1`, r.`dist2`, r.`dist3`, r.`dist4`, er.`name` as roundname, 
                  er.`location`, e.`status`, er.`eventroundid`, r.`unit`
            FROM `eventrounds` er
            JOIN `rounds` r USING (`roundid`)
            JOIN `events` e USING (`eventid`)
            WHERE er.`eventid` = :eventid
            ",
            ['eventid' => $event->eventid]
        );

        $resultdistances = $this->getDistances($eventrounds);

        // User Entry
        $userevententry = EventEntry::where('userid', Auth::id())
            ->where('eventid', $event->eventid)
            ->get()
            ->first();

        if (!empty($userevententry)) {
            $userevententry->status = EntryStatus::where('entrystatusid', $userevententry->entrystatusid)->pluck('name')->first();
        }

        $daterange = new EventDateRange($event->startdate, $event->enddate);
        $daterange = $daterange->getDateRange();
        $canscore = $this->canScore($event, $userevententry);
        return view ('publicevents.event_results', compact('daterange','event', 'canscore', 'eventrounds', 'userevententry', 'users', 'results', 'resultdistances', 'userevententry'));
    }

    public function getLeagueEventResults(Request $request, $event)
    {
        $event->numberofweeks = ceil($event->daycount / 7);

        // Event Rounds stuff
        $eventrounds = DB::select("SELECT r.`totalmax`, r.`name`, r.`dist1`, r.`dist2`, r.`dist3`, r.`dist4`, er.`name` as roundname, er.`location`, e.`status`, er.`eventroundid`, r.`unit`
            FROM `eventrounds` er 
            JOIN `rounds` r USING (`roundid`)
            JOIN `events` e USING (`eventid`)
            WHERE er.`eventid` = :eventid 
            ",
            ['eventid' => $event->eventid]
        );

        $eventroundmax = $eventrounds[0]->totalmax ?? -1;

        // Users
        $userids = [];
        $users = DB::select("SELECT ee.`userid`, ee.`fullname`, ee.`entrystatusid`, ee.`clubid` as club, ee.`paid`, d.`name` as division
            FROM `evententry` ee
            LEFT JOIN `divisions` d ON (ee.`divisionid` = d.`divisionid`)
            LEFT JOIN `clubs` c ON(c.`clubid` = ee.`clubid`)
            WHERE ee.`eventid` = :eventid
            ORDER BY d.`name`, ee.`fullname`
            ", ['eventid' => $event->eventid]);

        foreach ($users as $user) {
            $user->label = $this->getLabel($user->division);
            $userids[] = $user->userid;
        }


        $results = Score::where('eventid', $event->eventid)->get()->first();

        if (!empty($results)) {

            $week = 'AND s.`week` = ' . $event->currentweek;
            $intweek = $event->currentweek;

            if ($request->exists('week')) {
                $event->selectedweek = $request->input('week');
                $week = 'AND s.`week` = ' . intval($request->input('week'));
                $intweek = intval($request->input('week'));
            }

            if ($request->input('week') != 'overall') {

                $results = DB::select("SELECT s.*, u.`firstname`, u.`lastname`, u.`username`, d.`name` as divisonname, la.*
                FROM `scores` s 
                JOIN `users` u USING (`userid`)
                JOIN `divisions` d ON (s.`divisionid` = d.`divisionid`)
                LEFT JOIN `leagueaverages` la ON (s.`userid` = la.`userid` AND s.`eventid` = la.`eventid` AND la.`divisionid` = s.`divisionid`)
                WHERE s.`userid` IN (" . implode(',', $userids) . ")
                AND s.`eventid` = :eventid
                $week
                ORDER BY s.`total_score` DESC"
                    , ['eventid' => $event->eventid]

                );

            }
            else if ($request->input('week') == 'overall') {

                $results = DB::select("SELECT u.`firstname`, u.`lastname`, u.`username`, d.`name` as divisonname, la.*
                FROM `scores` s 
                JOIN `users` u USING (`userid`)
                JOIN `divisions` d ON (s.`divisionid` = d.`divisionid`)
                LEFT JOIN `leagueaverages` la ON (s.`userid` = la.`userid` AND s.`eventid` = la.`eventid` AND la.`divisionid` = s.`divisionid`)
                WHERE s.`userid` IN (" . implode(',', $userids) . ")
                AND s.`eventid` = :eventid
                GROUP BY u.`username`, d.`name`"
                    , ['eventid' => $event->eventid]

                );
            }

            foreach ($results as $result) {

                if ($request->input('week') != 'overall') {
                    $result->handicapscore = $eventroundmax - $result->avg_total_score + $result->total_score;
                    $result->weekpoints = UserController::getUserWeekPoints($result->userid, $result->divisionid, $event->eventid, $intweek);
                }

                $result->totalpoints = UserController::getUserTotalPoints($result->userid, $result->divisionid, $event->eventid);
                $result->top10scores = UserController::getUserTop10Scores($result->userid, $result->divisionid, $event->eventid);

            }

            if ($request->input('week') == 'overall') {

                usort($results, function ($a, $b) {

                    // return 1 when B greater than A

                    if ($b->totalpoints == $a->totalpoints) {
                        if ($b->avg_total_score > $a->avg_total_score) {
                            return 1;
                        }

                        return -1;
                    }

                    if ($b->totalpoints > $a->totalpoints) {
                        return 1;
                    }

                    return -1;

                });

            }


            $resultssorted = [];
            foreach ($results as $result) {
                $resultssorted[$result->divisonname][] = $result;
            }

            ksort($resultssorted);
            $results = $resultssorted;

        }




        $resultdistances = $this->getDistances($eventrounds);



        // User Entry
        $userevententry = EventEntry::where('userid', Auth::id())
                                    ->where('eventid', $event->eventid)
                                    ->get()
                                    ->first();

        if (!empty($userevententry)) {
            $userevententry->status = EntryStatus::where('entrystatusid', $userevententry->entrystatusid)->pluck('name')->first();
        }

        // show scoring tab or not
        $canscore = $this->canScore($event, $userevententry);
        
        return view ('publicevents.event_league_results', compact('event', 'canscore', 'eventrounds', 'userevententry', 'users', 'results', 'resultdistances', 'userevententry'));

    }


    private function setUsersScore($user, $score, $evententry, $event, $eventround, $round)
    {
        $score->userid = $user['userid'];
        $score->enteredbyuserid = Auth::id();
        $score->evententryid = $evententry->evententryid;
        $score->eventid = $event->eventid;
        $score->eventroundid = $eventround->eventroundid;
        $score->divisionid = $evententry->divisionid;
        $score->distanceunit = $round->unit;
        $score->week = $event->currentweek;


        // Distances
        $score->distance1_label = $round->dist1 ?? '';
        $score->distance1_total = $user['distance1']['total'] ?? '';
        $score->distance1_hits = $user['distance1']['hits'] ?? '';
        $score->distance1_10 = $user['distance1'][10] ?? '';
        $score->distance1_x = $user['distance1']['x'] ?? '';

        $score->distance2_label = $round->dist2 ?? '';
        $score->distance2_total = $user['distance2']['total'] ?? '';
        $score->distance2_hits = $user['distance2']['hits'] ?? '';
        $score->distance2_10 = $user['distance2'][10] ?? '';
        $score->distance2_x = $user['distance2']['x'] ?? '';

        $score->distance3_label = $round->dist3 ?? '';
        $score->distance3_total = $user['distance3']['total'] ?? '';
        $score->distance3_hits = $user['distance3']['hits'] ?? '';
        $score->distance3_10 = $user['distance3'][10] ?? '';
        $score->distance3_x = $user['distance3']['x'] ?? '';

        $score->distance4_label = $round->dist4 ?? '';
        $score->distance4_total = $user['distance4']['total'] ?? '';
        $score->distance4_hits = $user['distance4']['hits'] ?? '';
        $score->distance4_10 = $user['distance4'][10] ?? '';
        $score->distance4_x = $user['distance4']['x'] ?? '';


        // Totals
        $score->total_score = $user['total'] ?? '';
        $score->total_hits = $user['hits'] ?? '';
        $score->total_10 = $user['count10'] ?? '';
        $score->total_x = $user['countx'] ?? '';


        return $score;

    }

    private function setUsersEventScore($score, $user, $evententry, $event, $eventround, $round)
    {
        $score->userid = $user['userid'];
        $score->enteredbyuserid = Auth::id();
        $score->evententryid = $evententry->evententryid;
        $score->eventid = $event->eventid;
        $score->eventroundid = $eventround->eventroundid;
        $score->roundid = $round->roundid;
        $score->divisionid = $evententry->divisionid;
        $score->distanceunit = $round->unit;


        // Distances
        $score->distance1_label = $round->dist1 ?? 0;
        $score->distance1_total = $user['distance1']['total'] ?? 0;
        $score->distance1_hits = $user['distance1']['hits'] ?? 0;
        $score->distance1_10 = $user['distance1'][10] ?? 0;
        $score->distance1_x = $user['distance1']['x'] ?? 0;

        $score->distance2_label = $round->dist2 ?? 0;
        $score->distance2_total = $user['distance2']['total'] ?? 0;
        $score->distance2_hits = $user['distance2']['hits'] ?? 0;
        $score->distance2_10 = $user['distance2'][10] ?? 0;
        $score->distance2_x = $user['distance2']['x'] ?? 0;

        $score->distance3_label = $round->dist3 ?? 0;
        $score->distance3_total = $user['distance3']['total'] ?? 0;
        $score->distance3_hits = $user['distance3']['hits'] ?? 0;
        $score->distance3_10 = $user['distance3'][10] ?? 0;
        $score->distance3_x = $user['distance3']['x'] ?? 0;

        $score->distance4_label = $round->dist4 ?? 0;
        $score->distance4_total = $user['distance4']['total'] ?? 0;
        $score->distance4_hits = $user['distance4']['hits'] ?? 0;
        $score->distance4_10 = $user['distance4'][10] ?? 0;
        $score->distance4_x = $user['distance4']['x'] ?? 0;


        // Totals
        $score->total_score = $user['total'] ?? 0;
        $score->total_hits = $user['hits'] ?? 0;
        $score->total_10 = $user['count10'] ?? 0;
        $score->total_x = $user['countx'] ?? 0;


        return $score;

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
            if (!empty($eventround->dist2)) {
                $distances['Distance-2'] = $eventround->dist2;
                $distances['Distance-2-unit'] = $eventround->unit;
            }
            if (!empty($eventround->dist3)) {
                $distances['Distance-3'] = $eventround->dist3;
                $distances['Distance-3-unit'] = $eventround->unit;
            }
            if (!empty($eventround->dist4)) {
                $distances['Distance-4'] = $eventround->dist4;
                $distances['Distance-4-unit'] = $eventround->unit;
            }
        }
        return $distances;

    }

    private function getLeagueDistance($eventround)
    {
        return ['Distance-1' => $eventround->dist1, 'Distance-1-Unit' => $eventround->unit];
    }

    private function getExistingScore($userid, $evententryid, $eventroundid, $divisionid, $week = 1)
    {
        //dump($userid, $evententryid, $eventroundid, $divisionid, $week);
        return Score::where('userid', $userid)
            ->where('evententryid', $evententryid)
            ->where('eventroundid', $eventroundid)
            ->where('divisionid', $divisionid)
            ->where('week', $week)
            ->get()
            ->first();



    }

    private function getExistingEventScore($userid, $evententryid, $divisionid)
    {

        return Score::where('userid', $userid)
                        ->where('evententryid', $evententryid)
                        ->where('divisionid', $divisionid)
                        ->get()
                        ->first();



    }


    private function getUsersFormData($request)
    {

        $userid = $request->input('userid');
        $evententry = $request->input('evententryid');
        $divisionid = $request->input('divisionid');
        $distance1 = $request->input('distance1');
        $distance2 = $request->input('distance2');
        $distance3 = $request->input('distance3');
        $distance4 = $request->input('distance4');
        $total = $request->input('total');
        $hits = $request->input('hits');
        $count10 = $request->input(10);
        $countx = $request->input('x');


        $userResults = [];

        foreach ($evententry as $userevent) {

            if (empty($userevent)) {
                continue;
            }
            $index = uniqid();
            $userResults[$index]['userid'] = $userid[$userevent] ?? '';
            $userResults[$index]['evententryid'] = $userevent ?? '';
            $userResults[$index]['divisionid'] = $divisionid[$userevent] ?? '';
            $userResults[$index]['distance1'] = $distance1[$userevent] ?? '';
            $userResults[$index]['distance2'] = $distance2[$userevent] ?? '';
            $userResults[$index]['distance3'] = $distance3[$userevent] ?? '';
            $userResults[$index]['distance4'] = $distance4[$userevent] ?? '';
            $userResults[$index]['total'] = $total[$userevent]['total'] ?? '';
            $userResults[$index]['hits'] = $hits[$userevent]['hits'] ?? '';
            $userResults[$index]['count10'] = $count10[$userevent][10] ?? '';
            $userResults[$index]['countx'] = $countx[$userevent]['x'] ?? '';
        }

        return $userResults;

    }


    private function validateUsersScores($userscores, $round)
    {
        if (empty($userscores['total'])) {
            return false;
        }


        $user_totalscore = 0;
        $user_totalhits = 0;
        $user_total10 = 0;
        $user_totalx = 0;

        $errors = [];

        if (!empty($round->dist1max) && isset($userscores['distance1']['total'])) {

            if ($userscores['distance1']['total'] > $round->dist1max) {
                $errors[] = 'Score for ' . $round->dist1 . $round->unit . ' must be less than ' . $round->dist1max;
            }
            else if ($userscores['distance1']['total'] < 0) {
                $errors[] = 'Score for ' . $round->dist1 . $round->unit . ' must be more than zero';
            }
            else {
                $user_totalscore += $userscores['distance1']['total'];
                $user_totalhits += $userscores['distance1']['hits'] ?? 0;
                $user_total10 += $userscores['distance1'][10] ?? 0;
                $user_totalx += $userscores['distance1']['x'] ?? 0;
            }
        }

        if (!empty($round->dist2max) && isset($userscores['distance2']['total'])) {
            if ($userscores['distance2']['total'] > $round->dist2max) {
                $errors[] = 'Score for ' . $round->dist2 . $round->unit . ' must be less than ' . $round->dist2max;
            }
            else if ($userscores['distance2']['total'] < 0) {
                $errors[] = 'Score for ' . $round->dist2 . $round->unit . ' must be more than zero';
            }
            else {
                $user_totalscore += $userscores['distance2']['total'];
                $user_totalhits += $userscores['distance2']['hits'];
                $user_total10 += $userscores['distance2'][10];
                $user_totalx += $userscores['distance2']['x'];
            }
        }

        if (!empty($round->dist3max) && isset($userscores['distance3']['total'])) {
            if ($userscores['distance3']['total'] > $round->dist3max) {
                $errors[] = 'Score for ' . $round->dist3 . $round->unit . ' must be less than ' . $round->dist3max;
            }
            else if ($userscores['distance3']['total'] < 0) {
                $errors[] = 'Score for ' . $round->dist3 . $round->unit . ' must be more than zero';
            }
            else {
                $user_totalscore += $userscores['distance3']['total'];
                $user_totalhits += $userscores['distance3']['hits'];
                $user_total10 += $userscores['distance3'][10];
                $user_totalx += $userscores['distance3']['x'];
            }
        }

        if (!empty($round->dist4max) && isset($userscores['distance4']['total'])) {
            if ($userscores['distance4']['total'] > $round->dist4max) {
                $errors[] = 'Score for ' . $round->dist4 . $round->unit . ' must be less than ' . $round->dist4max;
            }
            else if ($userscores['distance4']['total'] < 0) {
                $errors[] = 'Score for ' . $round->dist4 . $round->unit . ' must be more than zero';
            }
            else {
                $user_totalscore += $userscores['distance4']['total'];
                $user_totalhits += $userscores['distance4']['hits'];
                $user_total10 += $userscores['distance4'][10];
                $user_totalx += $userscores['distance4']['x'];
            }
        }



        if ($user_totalscore > $round->totalmax || $userscores['total'] > $round->totalmax) {
            $errors[] = 'Total score must be less than ' . $round->totalmax;
        }

        if (!empty($round->totalhits)) {
            if ($user_totalhits > $round->totalhits || $userscores['hits'] > $round->totalhits) {
                $errors[] = 'Total hits must be less than ' . $round->totalhits;
            }
        }

        if (!empty($round->total10)) {
            if (intval($user_total10) > $round->total10  || $userscores['count10'] > $round->total10) {
                $errors[] = 'Total 10-count must be less than ' . $round->total10;
            }
        }

        if (!empty($round->totalx)) {
            if (intval($user_totalx) > $round->totalx  || $userscores['countx'] > $round->totalx) {
                $errors[] = 'Total X-count must be less than ' . $round->totalx;
            }
        }

        return $errors;
    }

}
