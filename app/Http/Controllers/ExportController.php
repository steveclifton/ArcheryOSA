<?php

namespace App\Http\Controllers;

use App\Event;
use App\EventEntry;
use Illuminate\Http\Request;
use League\Csv\Writer;
use Illuminate\Support\Facades\DB;

class ExportController extends Controller
{
    public function exportevententries(Request $request)
    {

        $event = Event::where('eventid', $request->eventid)->where('hash', $request->hash)->get()->first();
        if (empty($event)) {
            die();
        }

        $entries = DB::select("SELECT ee.`fullname`, c.`name` as clubname, ee.`email`, ee.`address`, ee.`phone`, d.`name` as divisionname, ee.`membershipcode`, ee.`paid`, ee.`gender`, ee.`notes`
            FROM `evententry` ee
            LEFT JOIN `clubs` c using (`clubid`)
            LEFT JOIN `divisions` d ON (ee.`divisionid` = d.`divisionid`)
            WHERE ee.`eventid` = :eventid
            ", [
            'eventid' => $request->eventid
        ]);



        $csv = Writer::createFromFileObject(new \SplTempFileObject());

        $csv->insertOne(['Name', 'Club', 'Email', 'Address', 'Phone', 'Division', 'Membership', 'Paid Status', 'Gender', 'Notes' ]);

        foreach ($entries as $entry) {
            if ($entry->paid == 0) {
                $entry->paid = 'No';
            } else if ($entry->paid == 1) {
                $entry->paid = 'Yes';
            } else {
                $entry->paid = 'N/A';
            }
            $csv->insertOne((array) $entry);
        }

        $csv->output(str_replace(' ', '-', $event->name) .'-' . date('d-m', time()) . '.csv');
        return back();
    }
}
