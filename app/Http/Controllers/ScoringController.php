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
     * Creates the scores in the database
     *
     * @param Request $request
     * @return $this
     */
    public function enterScores(Request $request)
    {
        $event = Event::where('eventid', $request->eventid)->get()->first();
        if (is_null($event)) {
            return back()->with('failure', 'Oops, Event was not found. Please contact Admin')->withInput();
        }
        if ($event->currentweek != $request->currentweek) {
            return back()->with('failure', 'Oops, Event was not found. Please contact Admin')->withInput();
        }

        $eventround = EventRound::where('eventroundid', $request->eventroundid)->get()->first();
        if (is_null($eventround)) {
            return back()->with('failure', 'Oops, Event was not found. Please contact Admin')->withInput();
        }

        $round = Round::where('roundid', $eventround->roundid)->get()->first();
        if (is_null($round)) {
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

            if (is_null($evententry)) {

                $errorstring = 'Error with score, please try again';

                $username = User::where('userid', $user['userid'])->get()->first();
                if (!is_null($username)) {
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

            if (is_null($score)) {
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





    public function enterEventScores(Request $request)
    {
        $event = Event::where('eventid', $request->eventid)->get()->first();
        if (is_null($event)) {
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

            if (is_null($evententry)) {

                $errorstring = 'Error with score, please try again';

                $username = User::where('userid', $user['userid'])->get()->first();
                if (!is_null($username)) {
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

            if (is_null($score)) {
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






    public function getEventResults(Request $request)
    {

        // Events
        $event = Event::where('name', urldecode($request->eventname))
            ->get()
            ->first();

        if (is_null($event)) {
            return Redirect::route('home');
        }
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

        if (!is_null($results)) {

            $week = 'AND s.`week` = ' . $event->currentweek;
            $intweek = $event->currentweek;

            if ( $request->exists('week') ) {

                if ($request->input('week') == 'overall') {

                }

                $event->selectedweek = $request->input('week');
                $week = 'AND s.`week` = ' . intval($request->input('week'));
                $intweek = intval($request->input('week'));

            }

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

            if ($event->eventtype == 1) {

                foreach ($results as $result) {
                    $result->handicapscore = $eventroundmax - $result->avg_total_score + $result->total_score;
                    $result->weekpoints = UserController::getUserWeekPoints($result->userid, $result->divisionid, $event->eventid, $intweek);
                    $result->totalpoints = UserController::getUserTotalPoints($result->userid, $result->divisionid, $event->eventid);
                }

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
        $userevententry = EventEntry::where('userid', Auth::id())->where('eventid', $event->eventid)->get()->first();
        if (!is_null($userevententry)) {
            $userevententry->status = EntryStatus::where('entrystatusid', $userevententry->entrystatusid)->pluck('name')->first();
        }



        return view ('auth.events.event_results', compact('event', 'eventrounds', 'userevententry', 'users', 'results', 'resultdistances', 'userevententry'));

    }

    public function getLeagueEventResults(Request $request)
    {

        // Events
        $event = Event::where('name', urldecode($request->eventname))
            ->get()
            ->first();

        if (is_null($event)) {
            return Redirect::route('home');
        }

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

        if (!is_null($results)) {

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

            } else if ($request->input('week') == 'overall') {

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
        $userevententry = EventEntry::where('userid', Auth::id())->where('eventid', $event->eventid)->get()->first();
        if (!is_null($userevententry)) {
            $userevententry->status = EntryStatus::where('entrystatusid', $userevententry->entrystatusid)->pluck('name')->first();
        }



        return view ('auth.events.event_results', compact('event', 'eventrounds', 'userevententry', 'users', 'results', 'resultdistances', 'userevententry'));

    }

    public function getScoringChoiceView(Request $request)
    {
        $event = Event::where('name', urldecode($request->eventname))
            ->get()
            ->first();


        if (is_null($event)) {
            return Redirect::route('home');
        }

        $this->eventid = $event->eventid;
        $this->currentweek = $event->currentweek;

        if ($event->eventtype == 1) {
            // get league scoring view
            return $this->getLeagueScoringData($event);
        } else {

            return $this->getScoringView($event);
        }

    }

    public function getScoringView($event)
    {

        if ( ($event->scoringenabled ?? -1) != 1) {
            return redirect()->back()->with('failure', 'Invalid Request');
        }

        $this->eventid = $event->eventid;



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


        $eventrounds = DB::select("SELECT r.`name`, r.`dist1`, r.`dist2`, r.`dist3`, r.`dist4`, er.`name` as roundname, er.`location`, e.`status`, er.`eventroundid`, r.`unit`, er.`divisions`
            FROM `eventrounds` er 
            JOIN `rounds` r USING (`roundid`)
            JOIN `events` e USING (`eventid`)
            WHERE er.`eventid` = :eventid 
            AND er.`date` = :rounddate
            ",
            ['eventid' => $event->eventid, 'rounddate' => $rounddate]
        );



        // unserialise the divisions
        foreach ($eventrounds as $eventround ) {
            $eventround->divisions = unserialize($eventround->divisions);
            $result = DB::select("SELECT count(*) as `count` FROM `evententry` WHERE `divisionid` IN (" . implode(',', $eventround->divisions ?? ['-1'] ). ")");
            $eventround->usercount = $result[0]->count?? 0;
        }



        if ( is_null($eventrounds) ) {
            return redirect()->back()->with('failure', 'Invalid Request');
        }

        // do a check to see who is allow to score,
        //  - if its the individual , get just their results and their user relations
        // - if its the admin , get all users and all their scores

//        $userrelations = UserExtended::getUserRelationIDs();
//


        $users = DB::select("SELECT ee.*, d.`name` as divisionname, d.`divisionid`
            FROM `evententry` ee
            JOIN `users` u USING (`userid`)
            JOIN `divisions` d USING (`divisionid`)
            WHERE ee.`eventid` = :eventid
            ", ['eventid' => $event->eventid,
            ]);

        foreach ($users as $user) {
            foreach ($eventrounds as $eventround) {
                $result = $this->getExistingScore($user->userid, $user->evententryid, $eventround->eventroundid, $user->divisionid);
                if (!is_null($result)) {
                    $user->result[$eventround->eventroundid] = $result;
                }
            }
        }

        // User Entry
        $userevententry = EventEntry::where('userid', Auth::id())->where('eventid', $event->eventid)->get()->first();
        if (!is_null($userevententry)) {
            $userevententry->status = EntryStatus::where('entrystatusid', $userevententry->entrystatusid)->pluck('name')->first();
        }



//        dd($eventrounds);


        return view('auth.events.event_scoringrounds', compact('users', 'eventround', 'eventrounds', 'distances', 'event', 'userevententry', 'results', 'daterange'));
    }




    private function getLeagueScoringData($event)
    {

        // Event Rounds stuff - ***************need to update to for events*************

        $eventround = DB::select("SELECT *
            FROM `eventrounds` er
            JOIN `rounds` r USING (`roundid`)
            WHERE er.`eventid` = :eventid
            LIMIT 1
        ", ['eventid' => $this->eventid,
        ]);


        if ( is_null($eventround) || !isset($eventround[0]) ) {
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

        $results = Score::where('eventid', $this->eventid)->get()->first();

        // User Entry
        $userevententry = EventEntry::where('userid', Auth::id())
            ->whereIn('entrystatusid', [2] )
            ->where('eventid', $this->eventid)
            ->get()
            ->first();


        if (!is_null($userevententry)) {
            $userevententry->status = EntryStatus::where('entrystatusid', $userevententry->entrystatusid)->pluck('name')->first();
        } else {
            return back()->with('failure', 'Unable to score at this time')->withInput();
        }


        return view('auth.events.event_league_scoringrounds', compact('users', 'eventround', 'eventrounds', 'distances', 'event', 'userevententry', 'results'));

    }



//    private function getScoringData($event)
//    {
//
//        // Event Rounds stuff - ***************need to update to for events*************
//        $eventrounds = DB::select("SELECT r.`name`, r.`dist1`, r.`dist2`, r.`dist3`, r.`dist4`, er.`name` as roundname, er.`location`, e.`status`, er.`eventroundid`, r.`unit`
//            FROM `eventrounds` er
//            JOIN `rounds` r USING (`roundid`)
//            JOIN `events` e USING (`eventid`)
//            WHERE er.`eventid` = :eventid
//            ",
//            ['eventid' => $this->eventid]
//        );
//
//        // User Entry
//        $userevententry = EventEntry::where('userid', Auth::id())
//            ->whereIn('entrystatusid', [2] )
//            ->where('eventid', $this->eventid)
//            ->get()
//            ->first();
//
//
//        if (!is_null($userevententry)) {
//            $userevententry->status = EntryStatus::where('entrystatusid', $userevententry->entrystatusid)->pluck('name')->first();
//        } else {
//            return back()->with('failure', 'Unable to score at this time')->withInput();
//        }
//
//
//        return view ('auth.events.event_scoringrounds', compact('event', 'eventrounds', 'userevententry' ));
//    }

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
            $userResults[$index]['total'] = $total[$userevent] ?? '';
            $userResults[$index]['hits'] = $hits[$userevent] ?? '';
            $userResults[$index]['count10'] = $count10[$userevent] ?? '';
            $userResults[$index]['countx'] = $countx[$userevent] ?? '';
        }
        return $userResults;

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
        $score->total_score = $user['total']['total'] ?? '';
        $score->total_hits = $user['hits']['hits'] ?? '';
        $score->total_10 = $user['count10'][10] ?? '';
        $score->total_x = $user['countx']['x'] ?? '';


        return $score;

    }

    private function validateUsersScores($userscores, $round)
    {
        if (empty($userscores['total']['total'])) {
            return false;
        }

        $user_totalscore = 0;
        $user_totalhits = 0;
        $user_total10 = 0;
        $user_totalx = 0;

        $errors = [];


        if (!is_null($round->dist1max) && isset($userscores['distance1']['total'])) {

            if ($userscores['distance1']['total'] > $round->dist1max) {
                $errors[] = 'Score for ' . $round->dist1 . $round->unit . ' must be less than ' . $round->dist1max;
            } else {
                $user_totalscore += $userscores['distance1']['total'];
                $user_totalhits += $userscores['distance1']['hits'] ?? 0;
                $user_total10 += $userscores['distance1'][10] ?? 0;
                $user_totalx += $userscores['distance1']['x'] ?? 0;
            }
        }

        if (!is_null($round->dist2max) && isset($userscores['distance2']['total'])) {
            if ($userscores['distance2']['total'] > $round->dist2max) {
                $errors[] = 'Score for ' . $round->dist2 . $round->unit . ' must be less than ' . $round->dist2max;
            } else {
                $user_totalscore += $userscores['distance2']['total'];
                $user_totalhits += $userscores['distance2']['hits'];
                $user_total10 += $userscores['distance2'][10];
                $user_totalx += $userscores['distance2']['x'];
            }
        }

        if (!is_null($round->dist3max) && isset($userscores['distance3']['total'])) {
            if ($userscores['distance3']['total'] > $round->dist3max) {
                $errors[] = 'Score for ' . $round->dist3 . $round->unit . ' must be less than ' . $round->dist3max;
            } else {
                $user_totalscore += $userscores['distance3']['total'];
                $user_totalhits += $userscores['distance3']['hits'];
                $user_total10 += $userscores['distance3'][10];
                $user_totalx += $userscores['distance3']['x'];
            }
        }

        if (!is_null($round->dist4max) && isset($userscores['distance4']['total'])) {
            if ($userscores['distance4']['total'] > $round->dist4max) {
                $errors[] = 'Score for ' . $round->dist4 . $round->unit . ' must be less than ' . $round->dist4max;
            } else {
                $user_totalscore += $userscores['distance4']['total'];
                $user_totalhits += $userscores['distance4']['hits'];
                $user_total10 += $userscores['distance4'][10];
                $user_totalx += $userscores['distance4']['x'];
            }
        }



        if ($user_totalscore > $round->totalmax || $userscores['total']['total'] > $round->totalmax) {
            $errors[] = 'Total score must be less than ' . $round->totalmax;
        }

        if (!is_null($round->totalhits)) {
            if ($user_totalhits > $round->totalhits || $userscores['hits']['hits'] > $round->totalhits) {
                $errors[] = 'Total hits must be less than ' . $round->totalhits;
            }
        }

        if (!is_null($round->total10)) {
            if (intval($user_total10) > $round->total10  || $userscores['count10'][10] > $round->total10) {
                $errors[] = 'Total 10-count must be less than ' . $round->total10;
            }
        }

        if (!is_null($round->totalx)) {
            if (intval($user_totalx) > $round->totalx  || $userscores['countx']['x'] > $round->totalx) {
                $errors[] = 'Total X-count must be less than ' . $round->totalx;
            }
        }


        return $errors;
    }

}
