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

            $table->string('distance1-label');
            $table->integer('distance1-total');
            $table->integer('distance1-hits')->default(0);
            $table->integer('distance1-10')->default(0);
            $table->integer('distance1-x')->default(0);

            $table->string('distance2-label');
            $table->integer('distance2-total');
            $table->integer('distance2-hits')->default(0);
            $table->integer('distance2-10')->default(0);
            $table->integer('distance2-x')->default(0);

            $table->string('distance3-label');
            $table->integer('distance3-total');
            $table->integer('distance3-hits')->default(0);
            $table->integer('distance3-10')->default(0);
            $table->integer('distance3-x')->default(0);

            $table->string('distance4-label');
            $table->integer('distance4-total');
            $table->integer('distance4-hits')->default(0);
            $table->integer('distance4-10')->default(0);
            $table->integer('distance4-x')->default(0);

            $table->string('total-score');
            $table->string('total-hits')->default(0);
            $table->string('total-10')->default(0);
            $table->string('total-x')->default(0);

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
