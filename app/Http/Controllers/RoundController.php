<?php

namespace App\Http\Controllers;

use App\Round;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class RoundController extends Controller
{
    public function getRoundsView()
    {
        $rounds = Round::orderBy('roundid', 'desc')->get();

        return view('admin.rounds.rounds', compact('rounds'));
    }

    public function getRoundCreateView()
    {
        return view('admin.rounds.createround');
    }

    public function updateRound(Request $request)
    {
        $round = Round::where('name', urldecode($request->name))->get();
        return view('admin.rounds.createround', compact('round'));
    }

    public function create(Request $request)
    {
        $round = new Round();

        $this->validate($request, [
            'name' => 'required|unique:rounds,name',
        ]);

        $visible = 0;
        if (!empty($request->input('visible'))) {
            $visible = 1;
        }

        $round->name = htmlentities($request->input('name'));
        $round->visible = $visible;
        $round->description = htmlentities($request->input('round'));


        $round->save();

        return Redirect::route('rounds');
    }

    public function update(Request $request)
    {
        $round = Round::where('roundid', $request->roundid)->first();

        if (is_null($round)) {
            return Redirect::route('round');
        }

        $this->validate($request, [
            'name' => 'required|unique:rounds,name,'. $request->roundid. ',roundid',
        ]);

        if ($request->roundid == $round->roundid) {

            $visible = 0;
            if (!empty($request->input('visible'))) {
                $visible = 1;
            }

            $round->name = htmlentities($request->input('name'));
            $round->visible = $visible;
            $round->description = htmlentities($request->input('description'));
            $round->code = htmlentities($request->input('code'));

            $round->save();

            return Redirect::route('rounds');
        }


    }



}
