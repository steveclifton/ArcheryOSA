<?php

namespace App\Http\Controllers\Scoring;

use App\Score;
use App\Http\Controllers\Controller;

class EventScoringController extends Controller
{
    public function deleteScore($eventid, $userid, $divisionid)
    {
        return Score::where('eventid', $eventid)
                        ->where('userid', $userid)
                        ->where('divisonid', $divisionid)
                        ->get()
                        ->delete();
    }
}
