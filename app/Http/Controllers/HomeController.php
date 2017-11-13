<?php

namespace App\Http\Controllers;

use App\Event;
use App\EventEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{

    public function index()
    {
        $events = DB::select("SELECT *
                        FROM `events`
                        WHERE `status` IN ('open', 'waitlist', 'pending')
                        AND `visible` = 1
                        ORDER BY `startdate` DESC
                        ");

        $userevents = DB::select("SELECT e.`eventid`, e.`startdate`, es.`name` as usereventstatus, e.`name`, e.`status` as eventstatus
                        FROM `evententry` ee
                        LEFT JOIN `events` e USING (`eventid`)
                        LEFT JOIN `entrystatuses` es ON (ee.`entrystatusid` = es.`entrystatusid`)
                        WHERE ee.`userid` = '" . Auth::id(). "'
                        GROUP BY e.`eventid`
                        ");

        return view ('includes.welcome', compact('events', 'userevents'));
    }
}
