<?php

namespace App\Http\Controllers;

use App\Organisation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class OrganisationController extends Controller
{
    public function getOrganisationView()
    {
        $organisations = Organisation::orderBy('organisationid', 'desc')->get();
        return view('admin.organisations.organisation', compact('organisations'));
    }

    public function getOrganisationCreateView()
    {
        $organisations = Organisation::where('visible', 1)->get();
        return view('admin.organisations.createorganisation', compact('organisations'));
    }

    public function getUpdateOrganisationView(Request $request)
    {
        $organisation = Organisation::where('name', urldecode($request->name))->get();
        $organisations = Organisation::where('visible', 1)->get();

        if ($organisation->isEmpty()) {
            return redirect('organisations');
        }

        return view('admin.organisations.updateorganisation', compact('organisation', 'organisations'));
    }

    public function create(Request $request)
    {
        $organisation = new Organisation();

        $this->validate($request, [
            'name' => 'required|unique:organisations,name',

        ]);

        $visible = 0;
        if (!empty($request->input('visible'))) {
            $visible = 1;
        }
        $parentOrganisationid = null;
        if ($request->input('parentorganisationid') != 'null') {
            $parentOrganisationid = 1;
        }

        $organisation->name = htmlentities($request->input('name'));
        $organisation->visible = $visible;
        $organisation->parentorganisationid = $parentOrganisationid;
        $organisation->description = htmlentities($request->input('description'));
        $organisation->url = htmlentities($request->input('url'));
        $organisation->contactname = htmlentities($request->input('contactname'));
        $organisation->email = htmlentities($request->input('email'));
        $organisation->phone = htmlentities($request->input('phone'));

        $organisation->save();

        return Redirect::route('organisations');
    }

    public function update(Request $request)
    {
        $organisation = Organisation::where('organisationid', $request->organisationid)->first();

        if (is_null($organisation)) {
            return Redirect::route('organisations');
        }

        $this->validate($request, [
            'name' => 'required|unique:organisations,name,'.$organisation->organisationid.',organisationid'
        ]);

        if ($request->organisationid == $organisation->organisationid) {

            $visible = 0;
            if (!empty($request->input('visible'))) {
                $visible = 1;
            }

            $parentOrganisationid = null;
            if ($request->input('parentorganisationid') != 'null') {
                $parentOrganisationid = 1;
            }

            $organisation->name = htmlentities($request->input('name'));
            $organisation->parentorganisationid = $parentOrganisationid;
            $organisation->description = htmlentities($request->input('description'));
            $organisation->url = htmlentities($request->input('url'));
            $organisation->contactname = htmlentities($request->input('contactname'));
            $organisation->email = htmlentities($request->input('email'));
            $organisation->phone = htmlentities($request->input('phone'));
            $organisation->visible = $visible;
            $organisation->save();

            return Redirect::route('organisations');
        }


    }
}

