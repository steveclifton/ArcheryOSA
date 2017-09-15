<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventDayTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eventdays', function (Blueprint $table) {
            $table->increments('eventdayid');
            $table->string('eventid');
            $table->string('name');
            $table->string('location')->nullable();
            $table->text('schedule')->nullable();
            $table->string('roundid')->nullable();
            $table->string('organisationid')->nullable();
            $table->string('divisions')->nullable();
            $table->tinyInteger('visible')->default(0);
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
