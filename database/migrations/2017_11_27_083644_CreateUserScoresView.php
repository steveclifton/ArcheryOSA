<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserScoresView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
        CREATE VIEW `userscores`
            AS 
            SELECT 
            u.`userid` as user_id,
            u.`firstname`, 
            u.`lastname`, 
            u.`image`, 
            u.`username`, 
            e.`name` as eventname,
            e.`eventtype`, 
            d.`name` as divisionname, 
            r.name as roundname,
            la.`avg_distance1_total`, 
            la.`avg_total_score`,
            la.`avg_total_hits`,
            la.`avg_total_10`,
            la.`avg_total_x`,
            s.*
            FROM `scores` s 
            JOIN `divisions` d ON (s.`divisionid` = d.`divisionid`)
            JOIN `events` e ON (s.`eventid` = e.`eventid`)
            JOIN `eventrounds` er ON (e.`eventid` = er.`eventid`)
            JOIN `rounds` r ON (r.`roundid` = er.`roundid`)
            JOIN `users` u ON (s.`userid` = u.`userid`)
            LEFT JOIN `leagueaverages` la ON (
                    s.`userid` = la.`userid` AND
                    s.`eventid` = la.`eventid` AND
                    s.`divisionid` = la.`divisionid`
                )
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
