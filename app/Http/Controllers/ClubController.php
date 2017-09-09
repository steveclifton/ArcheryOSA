<?php

namespace App\Http\Controllers;

use App\Club;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ClubController extends Controller
{
    public function getClubView()
    {
        $clubs = Club::orderBy('clubid', 'desc')->get();
        return view('admin.clubs.clubs', compact('clubs'));
    }

    public function getClubCreateView()
    {
        return view('admin.clubs.createclub');
    }

    public function getUpdateClubView(Request $request)
    {
        $club = Club::where('name', urldecode($request->name))->get();

        if ($club->isEmpty()) {
            return redirect('clubs');
        }

        return view('admin.clubs.updateclub', compact('club'));
    }

    public function create(Request $request)
    {
        $club = new Club();

        $this->validate($request, [
            'name' => 'required|unique:clubs,name',

        ]);

        $visible = 0;
        if (!empty($request->input('visible'))) {
            $visible = 1;
        }

        $club->name = htmlentities($request->input('name'));
        $club->visible = $visible;
        $club->description = htmlentities($request->input('description'));
        $club->url = htmlentities($request->input('url'));
        $club->contactname = htmlentities($request->input('contactname'));
        $club->email = htmlentities($request->input('email'));
        $club->phone = htmlentities($request->input('phone'));

        $club->save();

        return Redirect::route('clubs');
    }

    public function update(Request $request)
    {
        $club = Club::where('clubid', $request->clubid)->first();

        if (is_null($club)) {
            return Redirect::route('clubs');
        }

        $this->validate($request, [
            'name' => 'required|unique:clubs,name,'.$club->clubid.',clubid'
        ]);

        if ($request->clubid == $club->clubid) {

            $visible = 0;
            if (!empty($request->input('visible'))) {
                $visible = 1;
            }

            $club->name = htmlentities($request->input('name'));
            $club->description = htmlentities($request->input('description'));
            $club->url = htmlentities($request->input('url'));
            $club->contactname = htmlentities($request->input('contactname'));
            $club->email = htmlentities($request->input('email'));
            $club->street = htmlentities($request->input('street'));
            $club->suburb = htmlentities($request->input('suburb'));
            $club->city = htmlentities($request->input('city'));
            $club->country = htmlentities($request->input('country'));
            $club->phone = htmlentities($request->input('phone'));
            $club->visible = $visible;
            $club->save();

            return Redirect::route('clubs');
        }


    }
}

