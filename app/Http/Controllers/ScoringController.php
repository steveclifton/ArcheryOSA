<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ScoringController extends Controller
{
    public function getScoringView(Request $request)
    {
        return view('auth.events.scoring');
    }
}
