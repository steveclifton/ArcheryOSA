<?php

namespace App\Http\Controllers;

use App\Rules\UserOrgIDConflict;
use App\UserOrganisation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use App\Organisation;
class UserOrganisationController extends Controller
{
    public function getAllOrganisations()
    {
        dd(Auth::user());
    }

    public function getCreateView()
    {
        $organisations = Organisation::where('visible', 1)->get();
        return view ('auth.user.addorganisation', compact('organisations'));
    }

    public function create(Request $request)
    {

        $this->validate($request, [
            'organisationid' => 'required',
            'id' => 'required|unique:userorganisations,id,organisationid',
        ],[
            'id.unique' => 'This Organisation ID already exists, please contact ArcheryOSA Admin'
        ]);

        $userorg = new UserOrganisation();

        $userorg->userid = Auth::id();
        $userorg->organisationid = $request->input('organisationid');
        $userorg->id = $request->input('id');
        $userorg->visible = 1;

        $userorg->save();

        return Redirect::route('profile');

    }

    public function update(Request $request)
    {

    }

    public function delete(Request $request)
    {

    }
}
