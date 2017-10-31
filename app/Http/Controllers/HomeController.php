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
                        WHERE `deleted` = 0
                        AND `status` IN ('open', 'waitlist', 'pending')
                        AND `visible` = 1
                        ORDER BY `startdate` DESC
                        ");



        return view ('includes.welcome', compact('events'));
    }
}
