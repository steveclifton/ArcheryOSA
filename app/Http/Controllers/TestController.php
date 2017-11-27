<?php

namespace App\Http\Controllers;

use App\User;


class TestController extends Controller
{
    public function test()
    {
        $users = User::get();
        foreach ($users as $user) {
            $user->username = $user->firstname . $user->lastname;
            $user->username = strtolower(preg_replace("/[^a-zA-Z0-9]/", "", $user->username)) . rand(1,1440);

            dump($user->username);
            $user->save();
        }
        dd($users);
    }
}
