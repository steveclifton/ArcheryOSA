<?php

namespace App\Http\Controllers;

use App\Event;
use App\EventEntry;
use App\Mail\EntryConfirmation;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendEntryConfirmationEmail;




class SenderController extends Controller
{

    public function sendconfirmationemail(Request $request)
    {
        if ( $request->ip() != strval( getenv('IP_ADDRESS') ) ) {
            Log::info($request->ip());
            die('Invalid Request - IP Invalid');
        }

        $userid = $request->userid;
        $evententryid = $request->evententryid;
        $eventhash = $request->eventhash;

        if (empty($userid) || empty($evententryid) || empty($eventhash)) {
            die('Invalid Request');
        }

        $user = User::where('userid', $userid)->get()->first();

        if (empty($user)) {
            die('Invalid User');
        }

        $evententry = EventEntry::where('evententryid', $evententryid)->get()->first();

        if (empty($evententry) || $evententry->userid != $user->userid || $eventhash != $evententry->hash) {
            die('Invalid Evententry');
        }

        $event = Event::where('eventid', $evententry->eventid)->get()->first();

        Mail::to($evententry->email)
            ->send(new SendEntryConfirmationEmail(ucwords($event->name), ucwords($user->firstname), $this->getEventUrl($event->name)));

    } // sendEntryConfirmationEmail

    public function sendregistrationemail(Request $request)
    {

        if ( $request->ip() != strval( getenv('IP_ADDRESS') ) ) {
            Log::info($request->ip());
            die('Invalid Request - IP Invalid');
        }

        $userid = $request->userid;
        $evententryid = $request->evententryid;
        $eventhash = $request->eventhash;

        if (empty($userid) || empty($evententryid) || empty($eventhash)) {
            die('Invalid Request');
        }

        $user = User::where('userid', $userid)->get()->first();

        if (empty($user)) {
            die('Invalid User');
        }

        $evententry = EventEntry::where('evententryid', $evententryid)->get()->first();

        if (empty($evententry) || $evententry->userid != $user->userid || $eventhash != $evententry->hash) {
            die('Invalid Evententry');
        }

        $event = Event::where('eventid', $evententry->eventid)->get()->first();


        Mail::to($evententry->email)
            ->send(new EntryConfirmation(ucwords($event->name)));

    }

}
