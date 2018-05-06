<?php

namespace App\Http\Controllers;

use App\UserMemberships;
use Illuminate\Http\Request;
use App\UserOrganisation;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use App\Organisation;
use Validator;

class UserMembershipController extends Controller
{

    /**
     * GET
     * Returns the view where a user can create an organisation membership code
     */
    public function getCreateView()
    {
        $organisations = Organisation::where('visible', 1)->get();
        return view ('auth.user.addmembership', compact('organisations'));
    }

    /**
     * GET
     * Returns the view where a user can update an organisation membership code
     */
    public function getUpdateView(Request $request)
    {
        $usermembership = UserMemberships::where('userid', Auth::id())->where('usermembershipid', $request->membershipcode)->get();

        if (is_null($usermembership)) {
            return Redirect::route('home');
        }

        $organisations = Organisation::where('visible', 1)->get();
        return view ('auth.user.updatemembership', compact('organisations', 'usermembership'));
    }

    /**
     * POST
     * Creates the users membership code
     */
    public function create(Request $request)
    {

        Validator::make($request->all(), [
            'organisationid' => 'required',
            'membershipcode' => 'required|unique:usermemberships,membershipcode,usermembershipid,organisationid'
        ], [
            'membershipcode.unique' => 'The Membership Code is already in use, please contact ArcheryOSA'
        ])->validate();

        $userorg = new UserMemberships();

        $userorg->userid = Auth::id();
        $userorg->organisationid = $request->input('organisationid');
        $userorg->membershipcode = $request->input('membershipcode');
        $userorg->visible = 1;

        $userorg->save();

        return Redirect::route('profile');

    }

    /**
     * POST
     * Updates the users membership code
     */
    public function update(Request $request)
    {
        Validator::make($request->all(), [
            'organisationid' => 'required',
            'membershipcode' => 'required|unique:usermemberships,membershipcode,usermembershipid,organisationid'
        ], [
            'membershipcode.unique' => 'The Membership Code is already in use, please contact ArcheryOSA'
        ])->validate();


        $userorg = UserMemberships::where('usermembershipid', $request->usermembershipid)->first();

        if (is_null($userorg)) {
            $userorg = new UserMemberships();
        }

        $userorg->userid = Auth::id();
        $userorg->organisationid = $request->input('organisationid');
        $userorg->membershipcode = $request->input('membershipcode');
        $userorg->visible = 1;

        $userorg->save();

        return Redirect::route('profile');
    }

}
