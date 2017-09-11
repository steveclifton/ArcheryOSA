<?php

namespace App\Http\Controllers;

use App\Organisation;
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
        $organisations = Organisation::where('visible', 1)->get();
        return view('admin.rounds.createround', compact('organisations'));
    }

    public function getUpdateRoundView(Request $request)
    {
        $round = Round::where('name', urldecode($request->name))->get();

        $organisations = Organisation::where('visible', 1)->get();


        if ($round->isEmpty()) {
            return redirect('rounds');
        }

        return view('admin.rounds.updateround', compact('round', 'organisations'));
    }

    public function create(Request $request)
    {
        $round = new Round();

        $this->validate($request, [
            'name' => 'required|unique:rounds,name',
            'unit' => 'required',
            'code' => 'required',
            'dist1' => 'required|integer|between:0,100',
            'dist1max' => 'required|integer|min:0',
            'dist2' => 'integer|nullable|between:0,100',
            'dist2max' => 'integer|nullable|min:0',
            'dist3' => 'integer|nullable|between:0,100',
            'dist3max' => 'integer|nullable|min:0',
            'dist4' => 'integer|nullable|between:0,100',
            'dist4max' => 'integer|nullable|min:0',
            'totalmax' => 'required|integer|min:0',

        ], [
            'dist1.integer' => 'Invalid Distance',
            'dist1max.integer' => 'Invalid max score',
            'dist2.integer' => 'Invalid Distance',
            'dist2max.integer' => 'Invalid max score',
            'dist3.integer' => 'Invalid Distance',
            'dist3max.integer' => 'Invalid max score',
            'dist4.integer' => 'Invalid Distance',
            'dist4max.integer' => 'Invalid max score',
            'totalmax' => 'Invalid max score',
            'totalx' => 'Invalid max x-count',
            'total10' => 'Invalid max 10-count',
            'totalx' => 'required|integer|min:0',
            'total10' => 'required|integer|min:0',
        ]);

        $visible = 0;
        if (!empty($request->input('visible'))) {
            $visible = 1;
        }

        $round->name = htmlentities($request->input('name'));
        $round->unit = htmlentities($request->input('unit'));
        $round->code = htmlentities($request->input('code'));
        $round->organisationid = htmlentities($request->input('parentorganisationid'));
        $round->visible = $visible;
        $round->description = htmlentities($request->input('description'));
        $round->dist1 = htmlentities($request->input('dist1'));
        $round->dist1max = htmlentities($request->input('dist1max'));
        $round->dist2 = !empty($request->input('dist2')) ? htmlentities($request->input('dist2')) : null;
        $round->dist2max = !empty($request->input('dist2max')) ? htmlentities($request->input('dist2max')) : null;
        $round->dist3 = !empty($request->input('dist3')) ? htmlentities($request->input('dist3')) : null;
        $round->dist3max = !empty($request->input('dist3max')) ? htmlentities($request->input('dist3max')) : null;
        $round->dist4 = !empty($request->input('dist4')) ? htmlentities($request->input('dist4')) : null;
        $round->dist4max = !empty($request->input('dist4max')) ? htmlentities($request->input('dist4max')) : null;
        $round->totalmax = htmlentities($request->input('totalmax'));
        $round->totalx = htmlentities($request->input('totalx'));
        $round->total10 = htmlentities($request->input('total10'));

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
            'name' => 'required|unique:rounds,name,'.$round->roundid.',roundid',
            'unit' => 'required',
            'code' => 'required',
            'dist1' => 'required|integer|between:0,100',
            'dist1max' => 'required|integer|min:0',
            'dist2' => 'integer|nullable|between:0,100',
            'dist2max' => 'integer|nullable|min:0',
            'dist3' => 'integer|nullable|between:0,100',
            'dist3max' => 'integer|nullable|min:0',
            'dist4' => 'integer|nullable|between:0,100',
            'dist4max' => 'integer|nullable|min:0',
            'totalmax' => 'required|integer|min:0',
            'totalx' => 'required|integer|min:0',
            'total10' => 'required|integer|min:0',

        ], [
            'dist1.integer' => 'Invalid Distance',
            'dist1max.integer' => 'Invalid max score',
            'dist2.integer' => 'Invalid Distance',
            'dist2max.integer' => 'Invalid max score',
            'dist3.integer' => 'Invalid Distance',
            'dist3max.integer' => 'Invalid max score',
            'dist4.integer' => 'Invalid Distance',
            'dist4max.integer' => 'Invalid max score',
            'totalmax' => 'Invalid max score',
            'totalx' => 'Invalid max x-count',
            'total10' => 'Invalid max 10-count'
        ]);

        if ($request->roundid == $round->roundid) {

            $visible = 0;
            if (!empty($request->input('visible'))) {
                $visible = 1;
            }

            $round->name = htmlentities($request->input('name'));
            $round->unit = htmlentities($request->input('unit'));
            $round->code = htmlentities($request->input('code'));
            $round->organisationid = htmlentities($request->input('parentorganisationid'));
            $round->visible = $visible;
            $round->description = htmlentities($request->input('description'));
            $round->dist1 = htmlentities($request->input('dist1'));
            $round->dist1max = htmlentities($request->input('dist1max'));
            $round->dist2 = !empty($request->input('dist2')) ? htmlentities($request->input('dist2')) : null;
            $round->dist2max = !empty($request->input('dist2max')) ? htmlentities($request->input('dist2max')) : null;
            $round->dist3 = !empty($request->input('dist3')) ? htmlentities($request->input('dist3')) : null;
            $round->dist3max = !empty($request->input('dist3max')) ? htmlentities($request->input('dist3max')) : null;
            $round->dist4 = !empty($request->input('dist4')) ? htmlentities($request->input('dist4')) : null;
            $round->dist4max = !empty($request->input('dist4max')) ? htmlentities($request->input('dist4max')) : null;
            $round->totalmax = htmlentities($request->input('totalmax'));
            $round->totalx = htmlentities($request->input('totalx'));
            $round->total10 = htmlentities($request->input('total10'));


            $round->save();

            return Redirect::route('rounds');
        }


    }



}
