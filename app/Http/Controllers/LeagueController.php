<?php

namespace App\Http\Controllers;

use App\Division;
use App\Event;
use App\LeaguePoint;
use App\Score;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeagueController extends Controller
{

    public function processLeagueResults(Request $request)
    {

        // Get the event
        $event = Event::where('eventid', $request->eventid)
                        ->where('hash', $request->hash)
                        ->get()
                        ->first();



        // Get the Event Round
        $eventround = DB::select("SELECT r.`totalmax`
            FROM `eventrounds` er 
            JOIN `rounds` r USING (`roundid`)
            JOIN `events` e USING (`eventid`)
            WHERE er.`eventid` = :eventid 
            ",
            ['eventid' => $event->eventid]
        );

        $eventroundmax = $eventround[0]->totalmax ?? -1;



        // Get this weeks scores
        $scores = Score::where('eventid', $event->eventid)
                        ->where('week', $event->currentweek)
                        ->orderBy('divisionid')
                        ->orderBy('total_score', 'desc')
                        ->get();
       //dump($scores);

        // Get all the score averages for the comp
        $scoreaverages = DB::select("SELECT *
                                FROM `leagueaverages`
                                WHERE `eventid` = :eventid"
                          ,['eventid' => $event->eventid]);

        //dump($scoreaverages);
        $sortedscores = [];

        // loop through all THIS WEEKS SCORES
        foreach ($scores as $score) {

            // Find the user id, once found, the number of score entries must be more than 1 to be able to get points
            foreach ($scoreaverages as $average) {

                if (($average->userid == $score->userid && $average->divisionid == $score->divisionid)&& $average->scorecount > 1) {
                    $score->avg_total_score = $average->avg_total_score;
                    $score->avg_total_hits = $average->avg_total_hits;
                    $score->avg_total_10 = $average->avg_total_10;
                    $score->avg_total_x = $average->avg_total_x;
                    $score->handicap_value = $eventroundmax - $average->avg_total_score;
                    $score->handicap_score = $score->total_score + $score->handicap_value;

                    // Add to the array
                    $sortedscores[$score->divisionid][] = $score;

                }
            }
        }
        //dd($sortedscores);
        // To here we have the all the info we need to assign points

        foreach ($sortedscores as $divisionid => $divisionscores) {

            // Sort the divisions scores from highest to lowest
            usort($divisionscores, function($a, $b) {

                // return 1 when B greater than A

                if ($b->handicap_score == $a->handicap_score) {
                    if ($b->avg_total_score == $a->avg_total_score) {
                        if ($b->total_x > $a->total_x) {
                            return 1;
                        }
                        return -1;
                    }

                    if ($b->avg_total_score > $a->avg_total_score) {
                        return 1;
                    }
                    return -1;
                }

                // B greater than A
                if ($b->handicap_score > $a->handicap_score) {
                    return 1;
                }
                return -1;

            });

            //dd($divisionscores);

            // Start at 10 points, loop through awarding points . Stop when at xero
            $points = 10;
            foreach ($divisionscores as $divisionscore) {
                $leaguepoint = new LeaguePoint();
                $leaguepoint->userid = $divisionscore->userid;
                $leaguepoint->eventid = $divisionscore->eventid;
                $leaguepoint->divisionid = $divisionid;
                $leaguepoint->week = $divisionscore->week;
                $leaguepoint->points = $points--;

                $leaguepoint->save();

                if ($points == 0) {
                    break;
                }

            }
        }

        $event->currentweek++;
        $event->save();
        return redirect()->back()->with('message', 'Updated Succesfully');

    }


}
