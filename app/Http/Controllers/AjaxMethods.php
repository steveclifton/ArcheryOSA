<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class AjaxMethods extends Controller
{
    public function searchUserByEmail(Request $request)
    {
        $users = User::where('email', 'like', $request->input('email') . '%')->get();

        $exists = !empty($users->count()) ? true : false;

        return response()->json([
            'success' => $exists,
            'users' => $users
        ]);
    }
}
