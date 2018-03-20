<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Process\Process;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function touchurl($url)
    {
        $curl = 'curl --location -k --max-time 60 --speed-time 10 --speed-limit 999999999999999 --silent ' . getenv('APP_URL') . getenv('SEND_PATH') . $url . ' > /dev/null &';
        shell_exec($curl);
    }


    public function getEventUrl($eventname)
    {
        return 'https://archeryosa.com/eventdetails/' . urlencode($eventname);
    }

    protected function canScore($event, $userevententry)
    {
        $canscore = false;
        if ($event->scoringenabled) {

            if (
                    ($userevententry->entrystatusid ?? 0) == 2
                    || Auth::id() == $event->createdby
                    || !empty(Auth::user()->usertype)
                    && Auth::user()->usertype == 1
                )
                    {
                        $canscore = true;
                    } // if

        } // if

        return $canscore;

    } //canScore
}
