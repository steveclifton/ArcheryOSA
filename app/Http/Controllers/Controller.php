<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
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
}
