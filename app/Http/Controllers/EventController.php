<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EventController extends Controller
{
    public function getEventsView()
    {
        return view('events.events');
    }

    public function create(Request $request)
    {
        dd($request);
    }
}


