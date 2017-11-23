<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeagueAverageView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            CREATE VIEW leagueaverages AS
            SELECT u.`userid`, s.`eventid`, s.`eventroundid`,
                count(s.`scoreid`) as scorecount,
                avg(s.`distance1_total`) as avg_distance1_total,
                avg(s.`distance2_total`) as avg_distance2_total,
                avg(s.`distance3_total`) as avg_distance3_total,
                avg(s.`distance4_total`) as avg_distance4_total,
                avg(s.`total_score`) as avg_total_score,
                avg(s.`total_hits`) as avg_total_hits,
                avg(s.`total_10`) as avg_total_10,
                avg(s.`total_x`) as avg_total_x
            
            FROM `scores` s
            JOIN `users` u USING (`userid`)
            GROUP BY u.`userid`, s.`eventid`, s.`eventroundid`
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
