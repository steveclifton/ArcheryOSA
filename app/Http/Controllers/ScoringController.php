<?php

namespace App\Http\Controllers;

use App\Classes\UserExtended;
use App\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScoringController extends Controller
{

    public function enterScores(Request $request)
    {
        dd($request);


        // redirect back to results page with success message
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

        $users = DB::select("SELECT ee.`eventid`, ee.`fullname`, ee.`userid`, d.`name` as divisionname
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

}
