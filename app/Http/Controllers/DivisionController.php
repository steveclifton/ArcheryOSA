<?php

namespace App\Http\Controllers;

use App\Division;
use App\Organisation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class DivisionController extends Controller
{
    public function getDivisionsView()
    {
        $divisions = Division::orderBy('divisionid', 'desc')->get();

        return view('admin.divisions.divisions', compact('divisions'));
    }

    public function getDivisionCreateView()
    {
        $organisations = Organisation::where('visible', 1)->get();

        return view('admin.divisions.createdivision', compact('organisations'));
    }

    public function getUpdateDivisionView(Request $request)
    {
        $division = Division::where('name', urldecode($request->name))->get();
        $organisations = Organisation::where('visible', 1)->get();

        if ($division->isEmpty()) {
            return redirect('divisions');
        }

        return view('admin.divisions.updatedivision', compact('division', 'organisations'));
    }

    public function create(Request $request)
    {
        $division = new Division();

        $this->validate($request, [
            'name' => 'required|unique:divisions,name',
            'code' => 'unique:divisions,code'
        ]);

        $visible = 0;
        if (!empty($request->input('visible'))) {
            $visible = 1;
        }

        $organisationid = null;
        if ($request->input('organisationid') != 'null') {
            $organisationid = htmlentities($request->input('organisationid'));
        }

        $division->name = htmlentities($request->input('name'));
        $division->visible = $visible;
        $division->organisationid = $organisationid;
        $division->description = htmlentities($request->input('description'));
        $division->code = htmlentities($request->input('code'));
        $division->agerange = htmlentities($request->input('agerange'));

        $division->save();

        return Redirect::route('divisions');
    }

    public function update(Request $request)
    {
        $division = Division::where('divisionid', $request->divisionid)->first();

        if (is_null($division)) {
            return Redirect::route('divisions');
        }

        $this->validate($request, [
            'name' => 'required|unique:divisions,name,'. $request->divisionid. ',divisionid',
            'code' => 'required|unique:divisions,code,'.$division->code.',code',
        ]);

        if ($request->divisionid == $division->divisionid) {

            $visible = 0;
            if (!empty($request->input('visible'))) {
                $visible = 1;
            }

            $organisationid = null;
            if ($request->input('organisationid') != 'null') {
                $organisationid = htmlentities($request->input('organisationid'));
            }

            $division->name = htmlentities($request->input('name'));
            $division->visible = $visible;
            $division->organisationid = $organisationid;
            $division->description = htmlentities($request->input('description'));
            $division->code = htmlentities($request->input('code'));
            $division->agerange = htmlentities($request->input('agerange'));

            $division->save();

            return Redirect::route('divisions');
        }


    }

}
