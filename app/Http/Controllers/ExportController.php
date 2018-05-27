<?php

namespace App\Http\Controllers;

use App\Event;
use App\EventEntry;
use App\EventRound;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use League\Csv\Writer;
use Illuminate\Support\Facades\DB;
use Mpdf\Mpdf;

class ExportController extends Controller
{


    public function exportevententries(Request $request)
    {

        $event = Event::where('eventid', $request->eventid)->where('hash', $request->hash)->get()->first();
        if (empty($event)) {
            die();
        }

        $entries = DB::select("SELECT ee.`fullname`, c.`name` as clubname, ee.`email`, ee.`address`, ee.`phone`, d.`name` as divisionname, ee.`membershipcode`, ee.`paid`, ee.`gender`, ee.`notes`,
              ee.`created_at`, ee.`updated_at`
            FROM `evententry` ee
            LEFT JOIN `clubs` c using (`clubid`)
            LEFT JOIN `divisions` d ON (ee.`divisionid` = d.`divisionid`)
            WHERE ee.`eventid` = :eventid
            ", [
            'eventid' => $request->eventid
        ]);



        $csv = Writer::createFromFileObject(new \SplTempFileObject());

        $csv->insertOne(['Name', 'Club', 'Email', 'Address', 'Phone', 'Division', 'Membership', 'Paid Status', 'Gender', 'Notes', 'Created Date', 'Updated Date' ]);

        foreach ($entries as $entry) {
            if ($entry->paid == 0) {
                $entry->paid = 'No';
            } else if ($entry->paid == 1) {
                $entry->paid = 'Yes';
            } else {
                $entry->paid = 'N/A';
            }
            $entry->created_at = date('d-m-Y', strtotime($entry->created_at));
            $entry->updated_at = date('d-m-Y', strtotime($entry->updated_at));
            $csv->insertOne((array) $entry);
        }

        $csv->output(str_replace(' ', '-', $event->name) .'-' . date('d-m', time()) . '.csv');
        die();
    }


    public function exportTargetAllocations(Request $request)
    {
        $eventid = $this->geteventurlid($request->eventurl);

        $event = Event::where('eventid', $eventid)->get()->first();
        if (empty($event)) {
            return false;
        }

        $data = EventEntry::where('eventid', $eventid)->orderby('targetallocation')->get();

        $view = View::make('pdf.targetallocations', ['data' => $data]);
        $html = $view->render();

        $mpdf = new Mpdf([]);
        $mpdf->WriteHTML($html);

        $mpdf->Output('TargetAllocation.pdf', 'D');
        die();
    }
}
