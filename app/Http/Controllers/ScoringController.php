<?php

namespace App\Http\Controllers;

use App\Classes\UserExtended;
use App\Event;
use App\EventEntry;
use App\EventRound;
use App\Round;
use App\Score;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ScoringController extends Controller
{


    // {eventroundid}/{eventid}/{currentweek}

    public function enterScores(Request $request)
    {
        $errors = [];

        $event = Event::where('eventid', $request->eventid)->get()->first();
        if (is_null($event)) {
            //redirect back with error
        }
        if ($event->currentweek != $request->currentweek) {
            // redirect back with error
        }

        $eventround = EventRound::where('eventroundid', $request->eventroundid)->get()->first();
        if (is_null($eventround)) {
            // redirectback
        }

        $round = Round::where('roundid', $eventround->roundid)->get()->first();


        // At this point we have a valid form routed request
        $userResults = $this->getUsersFormData($request);



        foreach ($userResults as $user) {

            $evententry = EventEntry::where('userid', $user['userid'])
                                    ->where('evententryid', $user['evententryid'])
                                    ->where('divisionid', $user['divisionid'])
                                    ->get()
                                    ->first();

            if (is_null($evententry)) {
                $errors[] = 'Error with score, please try again';
                continue;
            }

            $result = $this->validateUsersScores($user, $round);

            if (!empty($result)) {
                // There is an error with the users scores .. push to errors and ignore scoring
                continue;
            }



            // if they have a score already, update it
            $score = Score::where('userid', $user['userid'])
                            ->where('evententryid', $evententry->evententryid)
                            ->where('eventroundid', $eventround->eventroundid)
                            ->where('divisionid', $evententry->divisionid)
                            ->get()
                            ->first();

            if (is_null($score)) {
                $score = new Score();
            }



            $score = $this->setUsersScore($user, $score, $evententry, $event, $eventround, $round);
            $score->save();
        } // endforeach


        // redirect back with the score in the box (if only 1 is allowed per week)


        dd('entered them all');


        dd($request);

        // redirect back to results page with success message $errors
    }




    public function getScoringView(Request $request)
    {
        $event = Event::where('eventid', $request->eventid)->where('name', urldecode($request->eventname))->get()->first();

        if (is_null($event) || $event->scoringenabled != 1) {
            return redirect()->back()->with('failure', 'Invalid Request');
        }

        $eventround = DB::select("SELECT *
            FROM `eventrounds` er
            JOIN `rounds` r USING (`roundid`)
            WHERE er.`eventid` = :eventid
            AND er.`eventroundid` = :eventroundid
            LIMIT 1
        ", ['eventid' => $event->eventid,
            'eventroundid' => $request->eventroundid
            ]);

        $distances = $this->getDistances($eventround);
//       dd($distances, $eventround);
        // Get users that the logged in user is able to score for
        // This needs to be updated so that if can be also the organiser of the event
        $userrelations = UserExtended::getUserRelationIDs();

        $users = DB::select("SELECT ee.`eventid`, ee.`fullname`, ee.`userid`, d.`name` as divisionname, d.`divisionid`, ee.`evententryid`
            FROM `evententry` ee
            JOIN `users` u USING (`userid`)
            JOIN `divisions` d USING (`divisionid`)
            WHERE ee.`eventid` = :eventid
            AND ee.`userid` IN (". implode(',' ,$userrelations) .")
            ", ['eventid' => $event->eventid,
            ]);

        // now i need to get the round and the number of distances to create inputs


        return view('auth.events.scoring', compact('users', 'eventround', 'distances', 'event'));
    }

    private function getDistances($eventround)
    {
        $distances = [];
        foreach ($eventround as $eventround) {
            $distances['Distance-1'] = $eventround->dist1;
            if (!is_null($eventround->dist2)) {
                $distances['Distance-2'] = $eventround->dist2;
            }
            if (!is_null($eventround->dist3)) {
                $distances['Distance-3'] = $eventround->dist3;
            }
            if (!is_null($eventround->dist4)) {
                $distances['Distance-4'] = $eventround->dist4;
            }
        }
        return $distances;

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
        $count10 = $request->input('10');
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
        $score->total_hits = $user['hit']['hits'] ?? '';
        $score->total_10 = $user['count10'][10] ?? '';
        $score->total_x = $user['countx']['x'] ?? '';


        return $score;

    }

    private function validateUsersScores($user, $round)
    {
        return [];
    }
}
