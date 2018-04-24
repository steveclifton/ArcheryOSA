<?php

namespace App\Http\Controllers;

use App\Division;
use App\Organisation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class DivisionController extends Controller
{






    /****************************************************
     *                                                   *
     *                ADMIN / AUTH METHODS               *
     *                                                   *
     *****************************************************/


    public function getDivisionsView()
    {
        $divisions = DB::select("SELECT d.*, o.`name` as organsationname 
                            FROM `divisions` d
                            LEFT JOIN `organisations` o using (`organisationid`)
                            WHERE d.`deleted` = 0
                            ORDER BY o.`name` ASC
                            ");

        return view('admin.divisions.divisions', compact('divisions'));
    }

    public function getDivisionCreateView()
    {
        $organisations = Organisation::where('visible', 1)->where('deleted', 0)->get();

        return view('admin.divisions.createdivision', compact('organisations'));
    }

    public function getUpdateDivisionView(Request $request)
    {
        $division = Division::where('name', rawurldecode($request->name))->get();
        $organisations = Organisation::where('visible', 1)->where('deleted', 0)->get();

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
        ]);

        if ($request->divisionid == $division->divisionid) {

            $visible = 0;
            if (!empty($request->input('visible'))) {
                $visible = 1;
            }

            $organisationid = 0;
            if ($request->input('organisationid') != '0') {
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

    public function delete(Request $request)
    {
        if (!empty($request->divisionid) || !empty($request->divisionname)) {
            $division = Division::where('divisionid', $request->divisionid)->where('name', urldecode($request->divisionname) );
            $division->first()->delete();
            return Redirect::route('divisions');
        }

        return Redirect::route('home');

    }

}
