<?php

namespace App\Http\Controllers;

use App\UserMemberships;
use Illuminate\Http\Request;
use App\UserOrganisation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use App\Organisation;
use Validator;

class UserMembershipController extends Controller
{
    public function getAllMemberships()
    {
        dd(Auth::user());
    }

    public function getCreateView()
    {
        $organisations = Organisation::where('visible', 1)->get();
        return view ('auth.user.addmembership', compact('organisations'));
    }

    public function getUpdateView(Request $request)
    {
        $usermembership = UserMemberships::where('userid', Auth::id())->where('usermembershipid', $request->membershipcode)->get();
        $organisations = Organisation::where('visible', 1)->get();
        return view ('auth.user.updatemembership', compact('organisations', 'usermembership'));
    }

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

    public function delete(Request $request)
    {

    }

    private function isValidMembershipcode($usermembershipid, $membershipcode, $organisationid)
    {
        $results = DB::select("SELECT *
            FROM `usermemberships`
            WHERE `organisationid` = :organisationid
            AND `membershipcode` = :membershipcode 
            LIMIT 1",
            ['organisationid' => $organisationid, 'membershipcode' => $membershipcode]
        );

        foreach ($results as $result) {

            if ($result->usermembershipid != $usermembershipid) {
                return false;
            }
        }
        return true;

    }
}
