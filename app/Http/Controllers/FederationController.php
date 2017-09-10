<?php

namespace App\Http\Controllers;

use App\Federation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class FederationController extends Controller
{
    public function getFederationView()
    {
        $federations = Federation::orderBy('federationid', 'desc')->get();

        return view('admin.federations.federation', compact('federations'));
    }

    public function getFederationCreateView()
    {
        return view('admin.federations.createfederation');
    }

    public function getUpdateFederationView(Request $request)
    {
        $federation = Federation::where('name', urldecode($request->name))->get();

        if ($federation->isEmpty()) {
            return redirect('federations');
        }

        return view('admin.federations.updatefederation', compact('federation'));
    }

    public function create(Request $request)
    {
        $federation = new Federation();

        $this->validate($request, [
            'name' => 'required|unique:federations,name',

        ]);

        $visible = 0;
        if (!empty($request->input('visible'))) {
            $visible = 1;
        }

        $federation->name = htmlentities($request->input('name'));
        $federation->visible = $visible;
        $federation->description = htmlentities($request->input('description'));
        $federation->url = htmlentities($request->input('url'));
        $federation->contactname = htmlentities($request->input('contactname'));
        $federation->email = htmlentities($request->input('email'));
        $federation->phone = htmlentities($request->input('phone'));

        $federation->save();

        return Redirect::route('federations');
    }

    public function update(Request $request)
    {
        $federation = Federation::where('federationid', $request->federationid)->first();

        if (is_null($federation)) {
            return Redirect::route('federations');
        }

        $this->validate($request, [
            'name' => 'required|unique:federations,name,'.$federation->federationid.',federationid'
        ]);

        if ($request->federationid == $federation->federationid) {

            $visible = 0;
            if (!empty($request->input('visible'))) {
                $visible = 1;
            }

            $federation->name = htmlentities($request->input('name'));
            $federation->description = htmlentities($request->input('description'));
            $federation->url = htmlentities($request->input('url'));
            $federation->contactname = htmlentities($request->input('contactname'));
            $federation->email = htmlentities($request->input('email'));
            $federation->phone = htmlentities($request->input('phone'));
            $federation->visible = $visible;
            $federation->save();

            return Redirect::route('federations');
        }


    }
}

