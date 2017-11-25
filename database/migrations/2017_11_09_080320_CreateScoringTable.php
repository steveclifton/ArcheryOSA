<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScoringTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scores', function (Blueprint $table) {
            $table->increments('scoreid');
            $table->integer('userid');
            $table->integer('enteredbyuserid');
            $table->string('eventid');
            $table->string('eventroundid');

            $table->string('distance1_label');
            $table->integer('distance1_total');
            $table->integer('distance1_hits')->default(0);
            $table->integer('distance1_10')->default(0);
            $table->integer('distance1_x')->default(0);

            $table->string('distance2_label');
            $table->integer('distance2_total');
            $table->integer('distance2_hits')->default(0);
            $table->integer('distance2_10')->default(0);
            $table->integer('distance2_x')->default(0);

            $table->string('distance3_label');
            $table->integer('distance3_total');
            $table->integer('distance3_hits')->default(0);
            $table->integer('distance3_10')->default(0);
            $table->integer('distance3_x')->default(0);

            $table->string('distance4_label');
            $table->integer('distance4_total');
            $table->integer('distance4_hits')->default(0);
            $table->integer('distance4_10')->default(0);
            $table->integer('distance4_x')->default(0);

            $table->integer('total_score')->default(0);
            $table->integer('total_hits')->default(0);
            $table->integer('total_10')->default(0);
            $table->integer('total_x')->default(0);

            $table->string('week')->default('1');
            $table->timestamps();
        });
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
