<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EventController extends Controller
{
    public function getEventsView()
    {
        return view('admin.events.events');
    }

    public function getCreateView(Request $request)
    {
        return view('admin.events.createevent');
    }
}


