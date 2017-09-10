<?php

namespace App\Http\Controllers;

use App\Club;
use App\Organisation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Image;

class ClubController extends Controller
{
    public function getPublicViewClubs()
    {
        $clubs = Club::where('visible', 1)->orderBy('clubid', 'desc')->get();
        return view('includes.clubs', compact('clubs'));
    }

    public function getClubView()
    {
        $clubs = Club::orderBy('clubid', 'desc')->get();
        return view('admin.clubs.clubs', compact('clubs'));
    }

    public function getClubCreateView()
    {
        $organisations = Organisation::where('visible', 1)->get();
        return view('admin.clubs.createclub', compact('organisations'));
    }

    public function getUpdateClubView(Request $request)
    {
        $club = Club::where('name', urldecode($request->name))->get();
        $organisations = Organisation::where('visible', 1)->get();

        if ($club->isEmpty()) {
            return redirect('clubs');
        }

        return view('admin.clubs.updateclub', compact('club', 'organisations'));
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
        $club->organisationid = htmlentities($request->input('organisationid'));

        $club->description = htmlentities($request->input('description'));
        $club->url = htmlentities($request->input('url'));
        $club->contactname = htmlentities($request->input('contactname'));
        $club->email = htmlentities($request->input('email'));
        $club->street = htmlentities($request->input('street'));
        $club->suburb = htmlentities($request->input('suburb'));
        $club->city = htmlentities($request->input('city'));
        $club->country = htmlentities($request->input('country'));
        $club->phone = htmlentities($request->input('phone'));

//        dd(request());

        if (request()->hasFile('clubimage')) {
            $image = request()->file('clubimage');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $location = public_path('/content/clubs/original/' . $filename);
            Image::make($image)->save($location);
            $location = public_path('/content/clubs/200/' . $filename);
            Image::make($image)->resize(200,200)->save($location);
            $club->image = $filename;
        }


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
            $club->organisationid = htmlentities($request->input('organisationid'));
            $club->url = htmlentities($request->input('url'));
            $club->contactname = htmlentities($request->input('contactname'));
            $club->email = htmlentities($request->input('email'));
            $club->street = htmlentities($request->input('street'));
            $club->suburb = htmlentities($request->input('suburb'));
            $club->city = htmlentities($request->input('city'));
            $club->country = htmlentities($request->input('country'));
            $club->phone = htmlentities($request->input('phone'));
            $club->visible = $visible;

            if (request()->hasFile('clubimage')) {
                $image = request()->file('clubimage');
                $filename = time() . '.' . $image->getClientOriginalExtension();
                $location = public_path('/content/clubs/original/' . $filename);
                Image::make($image)->save($location);
                $location = public_path('/content/clubs/200/' . $filename);
                Image::make($image)->resize(200,200)->save($location);
                $club->image = $filename;
            }

            $club->save();

            return Redirect::route('clubs');
        }


    }
}

