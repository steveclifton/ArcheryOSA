<?php

namespace App\Http\Controllers;

use App\EntryStatus;
use App\Event;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use App\EventEntry;
use App\User;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;



    public function makeurl($name, $eventid)
    {
        $url = prepurl($name) . '-' . $eventid;
        return $url;
    }

    public function geteventurlid($name)
    {
        $arr = explode('-', $name);
        return end($arr);
    }

    public function touchurl($url)
    {
        $curl = 'curl --location -k --max-time 60 --speed-time 10 --speed-limit 999999999999999 --silent ' . getenv('APP_URL') . getenv('SEND_PATH') . $url . ' > /dev/null &';
        shell_exec($curl);
    }


    public function getEventUrl($url)
    {
        return 'https://archeryosa.com/eventdetails/' . prepurl($url);
    }


    /**
     * Checks to see whether the current user can score
     */
    protected function canScore($event, $userevententry)
    {

        if ($event->scoringenabled) {

            $entrystatus = ($userevententry->entrystatusid ?? 0) == 2;
            $createdby = Auth::id() == $event->createdby;
            $usertype = !empty(Auth::user()->usertype) && Auth::user()->usertype == 1;
            $usercanscore = ($event->userscanscore == 1);

            // If an admin - admins can score for anything
            if ($usertype) {
                return true;
            }

            // if the event is created by the logged in user
            else if ($createdby == true) {
                return true;
            }

            // if users CAN score *AND* their entry status is confirmed
            else if ($usercanscore && $entrystatus) {
                return true;
            }


        } // if

        return false;

    }


    /**
     * PRIVATE
     * Returns a archer object that will contain their entry if it exists
     */
    public function getArchersEntry($eventid, $userid)
    {

        $returnObj = new \stdClass;

        $user = User::where('userid', $userid)->get()->first();

        if (empty($user)) {
            return false;
        }

        $eventregistration = EventEntry::where('eventid', $eventid)
            ->where('userid', $userid)
            ->get();

        foreach ($eventregistration as $reg) {
            $reg->status = ucwords(EntryStatus::where('entrystatusid', $reg->entrystatusid)->pluck('name')->first());
        }



        $returnObj->eventregistration = !$eventregistration->isEmpty() ? $eventregistration : [];

        $returnObj->userdivisions = [];
        foreach ($eventregistration as $registration) {
            $returnObj->userdivisions[] = $registration->divisionid;
        }

        $returnObj->usereventrounds = [];
        foreach ($eventregistration as $registration) {
            $returnObj->usereventrounds[] = $registration->eventroundid;
        }

        $returnObj->user = User::where('userid', $userid)->get()->first();

        // This makes the default email address the Person who is logged in
        if ($returnObj->user->semiaccount == 1) {
            $returnObj->user->email = User::where('userid', Auth::id())->pluck('email')->first();
        }

        return $returnObj;

    }


    /**
     * Create a random hash
     */
    public function createHash()
    {
        $hash = password_hash(rand( getenv('RAND_START'), getenv('RAND_END')), PASSWORD_DEFAULT);
        $hash = password_hash($hash, PASSWORD_DEFAULT);
        $hash = substr($hash, 7, 17);

        return str_replace(['/', '.', '#'],rand(1,999), $hash);
    }


    /**
     * Simple check to see if a user is able to edit an event
     */
    public function canEditEvent($eventid, $userid)
    {
        if (empty($eventid) || empty($userid)) {
            return false;
        }

        $user = User::where('userid', $userid)->get()->first();
        $event = Event::where('eventid', $eventid)->get()->first();


        if (!empty($user) && !empty($event)) {
            if ($user->usertype == 1 || $user->usertype == 2) {
                return true;
            }
            if ($event->createdby == $userid) {
                return true;
            }
        }

        return false;
    }

    public function canManageEvents()
    {
        if ((Auth::user()->usertype == 1 || Auth::user()->usertype == 2)) {
            return true;
        }
        return false;
    }

    public function getUsersManagedEvents()
    {
        return Event::where('createdby', Auth::id())
            ->get();
    }



}
