<?php

namespace App\Http\Controllers;

use App\Event;
use App\EventEntry;
use App\EventRound;
use App\Score;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use League\Csv\Writer;
use Illuminate\Support\Facades\DB;
use Mpdf\Mpdf;


class ExportController extends Controller
{

    public function exportScores(Request $request)
    {

        $event = Event::where('eventid', $request->eventid)->get()->first();


        if (empty($event)) {
            die();
        }

        $results = Score::where('eventid', $event->eventid)->get()->first();

        if (!empty($results)) {

            $results = DB::select("
                SELECT s.*, ee.`fullname`, u.firstname, u.lastname, ee.membershipcode, ee.`gender`, u.`username`, d.`name` as divisonname, d.code as divisoncode, er.`name` as roundname, 
                    c.name as club, d.image as categorycode
                
                FROM `scores` s 
                JOIN `evententry` ee ON (ee.`evententryid` = s.`evententryid`)
                JOIN `eventrounds` er ON (ee.`eventroundid` = er.`eventroundid`)
                LEFT JOIN `clubs` c on (c.clubid = ee.clubid)
                JOIN `users` u ON (s.`userid` = u.`userid`)
                JOIN `divisions` d ON (ee.`divisionid` = d.`divisionid` AND s.divisionid = ee.divisionid)
                WHERE s.`eventid` = :eventid
                
                ", [
                    'eventid' => $event->eventid
                ]
            );



            $resultssorted = [];
            foreach ($results as $result) {
                $resultssorted[ $result->divisonname . " " . $result->gender][] = $result;
            }


            foreach ($resultssorted as $key => &$result) {
                usort($result, function ($a, $b) {

                    // return 1 when B greater than A
                    //dump($a, $b);
                    if (intval($b->total_score) == intval($a->total_score)) {
                        if (intval($b->total_hits) == intval($a->total_hits)) {
                            if (intval($b->total_10) > intval($a->total_10)) {
                                return 1;
                            }
                        }

                        else if (intval($b->total_hits) > intval($a->total_hits)) {
                            return 1;
                        }
                    }

                    else if (intval($b->total_score) > intval($a->total_score)) {
                        return 1;
                    }

                    return -1;

                });
            }
            ksort($resultssorted);
            $results = $resultssorted;
        }

//        dd($results);
        $final = [];
        foreach ($results as $result){
            foreach ($result as $r) {

                $bowtype = '';
                if (stripos($r->divisonname, 'compound') > 0) {
                    $bowtype = 'C';
                } else if (stripos($r->divisonname, 'recurve') > 0) {
                    $bowtype = 'R';
                } else if (stripos($r->divisonname, 'longbow') > 0) {
                    $bowtype = 'L';
                }else if (stripos($r->divisonname, 'crossbow') > 0) {
                    $bowtype = 'X';
                }else if (stripos($r->divisonname, 'barebow') > 0) {
                    $bowtype = 'BB';
                }

                $data = [];
                $data[] = strtolower($r->firstname);
                $data[] = strtolower($r->lastname);
                $data[] = strtolower($r->club ?? '');
                $data[] = $r->membershipcode;
                $data[] = strtolower($r->divisonname);
                $data[] = $r->gender;
                $data[] = $bowtype;
                $data[] = $r->categorycode;
                $data[] = $r->distance1_total ?? 0;
                $data[] = $r->distance2_total ?? 0;
                $data[] = $r->total_score ?? 0;
                $final[] = $data;
            }
        }

//    dd($final);

        $csv = Writer::createFromFileObject(new \SplTempFileObject());

        $csv->insertOne([
            'Firstname',
            'Lastname',
            'Club',
            'ANZ',
            'Division',
            'Gender',
            'Bowtype',
            'Category',
            'Dist 1',
            'Dist 2',
            'Total'

        ]);

        foreach ($final as $f) {

            $csv->insertOne($f);
        }

        $csv->output(str_replace(' ', '-', $event->name) .'-' . date('d-m', time()) . '.csv');
        die();
    }


    public function exportevententries(Request $request)
    {

        $event = Event::where('eventid', $request->eventid)->where('hash', $request->hash)->get()->first();
        if (empty($event)) {
            die();
        }

        $entries = DB::select("SELECT ee.`fullname`, c.`name` as clubname, ee.`email`, ee.`address`, ee.`phone`, d.`name` as divisionname, er.name as roundname, ee.`membershipcode`, ee.`paid`, ee.`gender`, ee.`notes`,
              ee.`created_at`, ee.`updated_at`
            FROM `evententry` ee
            LEFT JOIN `clubs` c using (`clubid`)
            LEFT JOIN `divisions` d ON (ee.`divisionid` = d.`divisionid`)
            LEFT JOIN `eventrounds` er ON (ee.eventroundid = er.eventroundid)
            WHERE ee.`eventid` = :eventid
            ", [
            'eventid' => $request->eventid
        ]);



        $csv = Writer::createFromFileObject(new \SplTempFileObject());

        $csv->insertOne(['Name', 'Club', 'Email', 'Address', 'Phone', 'Division', 'Round Name', 'Membership', 'Paid Status', 'Gender', 'Notes', 'Created Date', 'Updated Date' ]);

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

        if (empty($data)) {
            return redirect()->back()->with('failure', 'Please save target entries before exporting');
        }

        $view = View::make('pdf.targetallocations', ['data' => $data]);
        $html = $view->render();

        $mpdf = new Mpdf([]);
        $mpdf->WriteHTML($html);

        $mpdf->Output('TargetAllocation.pdf', 'D');
        die();
    }


}

