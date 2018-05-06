<?php

namespace App\Http\Controllers;

use App\ArcherRelation;
use App\Club;
use App\Event;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

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

    public function getArchersEntryForm(Request $request)
    {

        // Get the users id - default to logged in user
        $user = ArcherRelation::where('userid', Auth::id())
                                ->where('relationuserid', $request->userid)
                                ->get()
                                ->first();
        $userid = Auth::id();

        if (!empty($user->relationuserid)) {
            $userid = $user->relationuserid;
        }

        // Get the event
        $event = Event::where('eventid', $request->eventid)->get()->first();

        if (empty($event->eventid)) {
            return response()->json([
                'success' => false,
                'users' => 'Invalid Request, please try again later'
            ]);
        }

        // Get the users Data
        $eventregcontroller = new EventRegistrationController();
        $data = $eventregcontroller->getRegisterEventDetails($event->eventid, $userid);

        // Make the view
        $view = View::make('includes.forms.entryform', $data);
        $html = $view->render();

        // Return view
        return response()->json([
            'success' => true,
            'html' => $html,
            'existing' => empty($data['archerentry']->eventregistration) === false
        ]);

    }

}
