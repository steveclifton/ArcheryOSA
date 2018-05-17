<?php

namespace App\Http\Controllers;

use App\Event;
use Illuminate\Support\Facades\Auth;

class TestController extends Controller
{

    public function __construct()
    {
        if (Auth::id() != 1) {
            die();
        }
    }

    public function test()
    {
        $event=Event::get();


        foreach ($event as $e) {
            $e->url = $this->makeurl($e->name, $e->eventid);
            $e->save();
        }
//        $users = User::get();
//        foreach ($users as $user) {
//            $user->username = $user->firstname . $user->lastname;
//            $user->username = strtolower(preg_replace("/[^a-zA-Z0-9]/", "", $user->username)) . rand(1,1440);
//
//            dump($user->username);
//            $user->save();
//        }
        dd('dead');
        dd(phpinfo());
    }
}
