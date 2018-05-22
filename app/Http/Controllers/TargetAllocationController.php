<?php

namespace App\Http\Controllers;

use App\EventEntry;
use App\EventRound;


class TargetAllocationController extends Controller
{

    public function getTargetAllocations($eventid, $date, $data)
    {

        if (empty($date)) {
            $date = reset($data['daterange']);
        }

        $rounds = EventRound::where('eventid', $eventid)
                                ->where('date', $date)
                                ->orderBy('date')
                                ->get();

        $roundids = [];
        foreach ($rounds as $r) {
            $roundids[] = $r->eventroundid;
        }

        $users = EventEntry::wherein('eventroundid', $roundids)->orderby('targetallocation')->get();

        return $users;

    }


}
