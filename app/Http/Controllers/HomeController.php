<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{

    public function index()
    {

        $events = DB::select("SELECT *
                        FROM `events`
                        WHERE `status` IN ('open', 'waitlist', 'pending', 'entries-closed')
                        AND `visible` = 1
                        ORDER BY `startdate` ASC
                        ");

        $userevents = DB::select("SELECT e.`eventid`, e.`startdate`, es.`name` as usereventstatus, e.`name`, e.`status` as eventstatus, e.`url`
                        FROM `evententry` ee
                        LEFT JOIN `events` e USING (`eventid`)
                        LEFT JOIN `entrystatuses` es ON (ee.`entrystatusid` = es.`entrystatusid`)
                        WHERE ee.`userid` = '" . Auth::id(). "'
                        AND e.`status` NOT IN ('completed')
                        GROUP BY e.`eventid`
                        ");

        $prevevents = DB::select("SELECT *
                        FROM `events`
                        WHERE `deleted` = 0
                        AND `status` IN ('completed')
                        AND `visible` = 1
                        ORDER BY `startdate` DESC
                        ");

        return view ('includes.welcome', compact('events', 'userevents', 'prevevents'));

    }


}
