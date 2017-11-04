<?php
namespace App\Classes;

use DateTime;
use DateInterval;
use DatePeriod;
class EventDateRange {

    private $la_daterange;

    public function __construct($begin, $end)
    {
        try {
            $begin = new DateTime( $begin );
            $end = new DateTime( $end );
            $end = $end->modify( '+1 day' );
            $interval = new DateInterval('P1D');
            $daterange = new DatePeriod($begin, $interval ,$end);

            foreach ($daterange as $date){
                $this->la_daterange[] = $date->format("Y-m-d");
            }

        } catch (\Exception $exception) {
            $this->la_daterange = [];
        }

    }

    public function getDateRange()
    {
        return $this->la_daterange ?? [];

    }

}