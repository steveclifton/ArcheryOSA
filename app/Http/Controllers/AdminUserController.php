<?php

namespace App\Http\Controllers;

use App\UserTypes;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\DB;

class AdminUserController extends Controller
{
    public function getAllUsers()
    {
        $users = DB::select("SELECT u.*, count(e.`evententryid`) as eventcount, ut.`name` as usertype 
                    FROM `users` u 
                    LEFT JOIN `evententry` e USING (`userid`)
                    LEFT JOIN `usertypes` ut ON (u.`usertype` = ut.`usertypeid` )
                    GROUP BY u.`userid`
            ");

//        dd($users);
        return view('admin.users.users', compact('users'));
    }

    public function getUserProfile(Request $request)
    {
        $user = User::where('userid', $request->userid)->get()->first();

        $usertypes = UserTypes::get();

        return view('admin.users.userprofile', compact('user', 'usertypes'));
    }

    public function updateUsersProfile(Request $request)
    {
        dd($request);
    }
}
