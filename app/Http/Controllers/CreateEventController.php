<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CreateEventController extends Controller
{
    public function getCreateView()
    {
        return view('events.create-event');
    }

    public function create(Request $request)
    {
        dd($request);
    }
}
